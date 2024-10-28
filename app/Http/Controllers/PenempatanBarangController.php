<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use App\Models\PenempatanBarang;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPemindahan;
use App\Models\Barang; // Model untuk jenis barang

class PenempatanBarangController extends Controller
{
    public function index()
    {
        // Mengambil semua data TransaksiPemindahan dengan relasi yang diperlukan
        $transaksiPemindahan = TransaksiPemindahan::with(['ruanganAsal', 'ruanganTujuan'])->get();

        // Mengambil semua data PenempatanBarang dengan relasi ke detail barang dan ruangan
        $penempatanBarang = PenempatanBarang::with(['detailBarang.barang', 'detailBarang.ruangan'])->paginate(10);

        // Mengirim data ke view index
        return view('pages.penempatan_barang.index', compact('transaksiPemindahan', 'penempatanBarang'));
    }

    public function create()
    {
        $ruangan = Ruangan::all();

        // Get unique item types for 'jenisBarang'
        $jenisBarang = Barang::select('id', 'nama_barang')->distinct()->get();

        // Get only items in storage with specific details
        $detailBarang = DetailBarang::where('status', 'in storage')
            ->join('barang', 'detail_barang.id_jenis_barang', '=', 'barang.id')
            ->select('detail_barang.*', 'barang.nama_barang')
            ->get();

        return view('pages.penempatan_barang.create', compact('ruangan', 'jenisBarang', 'detailBarang'));
    }


    public function store(Request $request)
    {

        dd($request->all());

        // Validasi input form
        $request->validate([
            'id_ruangan_asal' => 'required|exists:ruangan,id',
            'id_ruangan_tujuan' => 'required|exists:ruangan,id',
            'tanggal_penempatan' => 'required|date',
            'pc_number' => 'required|array',
            'pc_number.*' => 'required|string',
            'id_jenis_barang' => 'required|array',
            'id_detail_barang' => 'required|array',
            'id_jenis_barang.*' => 'required|array',
            'id_detail_barang.*' => 'required|array',
        ]);

        // // Custom validation for uniqueness in each PC's items
        // foreach ($request->id_jenis_barang as $index => $jenisBarangSet) {
        //     $itemCounts = array_count_values($jenisBarangSet); // Count occurrences of each item type

        //     foreach ($itemCounts as $item => $count) {
        //         // Check if the item is 'RAM' and occurs more than twice
        //         if ($item === 'RAM' && $count > 2) {
        //             return redirect()->back()->withErrors(['error' => "PC nomor {$request->pc_number[$index]} memiliki lebih dari 2 RAM. Maksimal 2 RAM diizinkan."])->withInput();
        //         }

        //         // Check for any other item occurring more than once
        //         if ($item !== 'RAM' && $count > 1) {
        //             return redirect()->back()->withErrors(['error' => "PC nomor {$request->pc_number[$index]} memiliki item {$item} yang sama lebih dari satu kali. Item lain hanya diperbolehkan satu per PC."])->withInput();
        //         }
        //     }
        // }

        try {
            DB::beginTransaction(); // Memulai transaksi database

            // Hitung total item yang dipindahkan
            $totalItems = 0;
            foreach ($request->id_detail_barang as $detailBarangSet) {
                $totalItems += count($detailBarangSet);
            }

            // Buat entri di tabel TransaksiPemindahan
            $transaksiPemindahan = TransaksiPemindahan::create([
                'id_ruangan_asal' => $request->id_ruangan_asal,
                'id_ruangan_tujuan' => $request->id_ruangan_tujuan,
                'tanggal_pemindahan' => $request->tanggal_penempatan,
                'jumlah_item' => $totalItems, // jumlah total barang yang dipindahkan
            ]);

            // Looping PC yang diinputkan
            foreach ($request->pc_number as $index => $pcNumber) {
                // Looping setiap barang yang terhubung ke PC berdasarkan index
                foreach ($request->id_detail_barang[$index] as $detailBarangId) {
                    // Update status detail barang ke "in use"
                    $detailBarang = DetailBarang::find($detailBarangId);
                    if ($detailBarang) {
                        $detailBarang->status = 'in use';
                        $detailBarang->lokasi = $request->id_ruangan_tujuan;
                        $detailBarang->save();

                        // Update stock barang di tabel Barang
                        $barang = $detailBarang->barang;
                        if ($barang) {
                            $barang->stock -= 1;
                            $barang->used += 1;
                            $barang->save();
                        }

                        // Simpan ke tabel PenempatanBarang
                        PenempatanBarang::create([
                            'id_transaksi_pemindahan' => $transaksiPemindahan->id,
                            'id_detail_barang' => $detailBarangId,
                            'pc_number' => $pcNumber,
                        ]);
                    }
                }
            }

            DB::commit(); // Selesaikan transaksi
            return redirect()->route('penempatan_barang.index')->with('success', 'Penempatan barang berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika terjadi kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        $penempatanBarang = PenempatanBarang::findOrFail($id);
        $ruangan = Ruangan::all();
        $jenisBarang = Barang::all();
        $detailBarang = DetailBarang::all();
        return view('pages.penempatan_barang.edit', compact('penempatanBarang', 'ruangan', 'jenisBarang', 'detailBarang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_ruangan' => 'required|exists:ruangan,id',
            'tanggal_penempatan' => 'required|date',
            'pc_no' => 'required|string|max:255',
            'id_jenis_barang' => 'required|exists:barang,id',
            'id_detail_barang' => 'required|exists:detail_barang,id',
        ]);

        $penempatanBarang = PenempatanBarang::findOrFail($id);

        // Perbarui kondisi detail barang menjadi 'digunakan'
        $detailBarang = DetailBarang::find($request->id_detail_barang);
        $detailBarang->kondisi = 'digunakan';
        $detailBarang->save();

        // Perbarui jumlah barang yang tersedia dan digunakan
        $barang = Barang::find($request->id_jenis_barang);
        $barang->barang_tersedia -= 1;
        $barang->barang_digunakan += 1;
        $barang->save();

        $penempatanBarang->update([
            'id_detail_barang' => $request->id_detail_barang,
            'id_ruangan' => $request->id_ruangan,
            'pc_no' => $request->pc_no,
            'tanggal_penempatan' => $request->tanggal_penempatan,
        ]);

        // Perbarui kondisi ruangan
        $ruangan = Ruangan::find($request->id_ruangan);
        $ruangan->status_ruangan = 'digunakan';
        $ruangan->save();

        return redirect()->route('penempatan_barang.index')->with('success', 'Penempatan Barang updated successfully.');
    }

    public function destroy($id)
    {
        $penempatanBarang = PenempatanBarang::findOrFail($id);
        $penempatanBarang->delete();

        return redirect()->route('penempatan_barang.index')->with('success', 'Penempatan Barang deleted successfully.');
    }

    public function getPenempatanBarangByTransaksi($id)
    {
        // Ambil data penempatan barang berdasarkan ID transaksi pemindahan
        $penempatanBarang = PenempatanBarang::with(['detailBarang.barang'])
            ->where('id_transaksi_pemindahan', $id)
            ->get();

        // Pastikan respon dalam format JSON
        return response()->json([
            'data' => $penempatanBarang
        ], 200, ['Content-Type' => 'application/json']);
    }
}
