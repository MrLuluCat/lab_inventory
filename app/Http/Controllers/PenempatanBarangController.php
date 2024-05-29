<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use App\Models\PenempatanBarang;
use App\Models\TransaksiPemindahan;
use App\Models\Barang; // Model untuk jenis barang

class PenempatanBarangController extends Controller
{
    public function index()
    {
        $penempatanBarang = PenempatanBarang::with('detailBarang', 'ruangan')->get();
        return view('pages.penempatan_barang.index', compact('penempatanBarang'));
    }

    public function create()
    {
        $ruangan = Ruangan::all();
        $jenisBarang = Barang::all(); // Mengambil data jenis barang
        $detailBarang = DetailBarang::all();
        return view('pages.penempatan_barang.create', compact('ruangan', 'jenisBarang', 'detailBarang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ruangan_asal' => 'required|exists:ruangan,id',
            'id_ruangan_tujuan' => 'required|exists:ruangan,id',
            'tanggal_penempatan' => 'required|date',
            'pc_no' => 'required|array',
            'pc_no.*' => 'required|string|max:255',
            'id_jenis_barang' => 'required|array',
            'id_jenis_barang.*' => 'required|array',
            'id_jenis_barang.*.*' => 'required|exists:barang,id',
            'id_detail_barang' => 'required|array',
            'id_detail_barang.*' => 'required|array',
            'id_detail_barang.*.*' => 'required|exists:detail_barang,id',
        ]);

        $transaksiPemindahan = TransaksiPemindahan::create([
            'id_ruangan_asal' => $request->id_ruangan_asal,
            'id_ruangan_tujuan' => $request->id_ruangan_tujuan,
            'tanggal_pemindahan' => $request->tanggal_penempatan,
        ]);

        foreach ($request->pc_no as $pcIndex => $pc_no) {
            foreach ($request->id_detail_barang[$pcIndex] as $detailIndex => $id_detail_barang) {
                // Perbarui kondisi detail barang menjadi 'digunakan'
                $detailBarang = DetailBarang::find($id_detail_barang);
                $detailBarang->kondisi = 'digunakan';
                $detailBarang->save();

                // Perbarui jumlah barang yang tersedia dan digunakan
                $barang = Barang::find($request->id_jenis_barang[$pcIndex][$detailIndex]);
                $barang->barang_tersedia -= 1;
                $barang->barang_digunakan += 1;
                $barang->save();

                // Buat penempatan barang baru
                PenempatanBarang::create([
                    'id_transaksi_pemindahan' => $transaksiPemindahan->id,
                    'tanggal_penempatan' => $request->tanggal_penempatan,
                    'id_detail_barang' => $id_detail_barang,
                    'id_ruangan' => $request->id_ruangan_tujuan,
                    'pc_no' => $pc_no,
                ]);

                // Perbarui kondisi ruangan tujuan
                $ruanganTujuan = Ruangan::find($request->id_ruangan_tujuan);
                $ruanganTujuan->status_ruangan = 'digunakan';
                $ruanganTujuan->save();

                // Perbarui kondisi ruangan asal jika diperlukan
                $ruanganAsal = Ruangan::find($request->id_ruangan_asal);
                if ($ruanganAsal->id != $ruanganTujuan->id) {
                    $ruanganAsal->status_ruangan = 'kosong';
                    $ruanganAsal->save();
                }
            }
        }

        return redirect()->route('penempatan_barang.index')->with('success', 'Penempatan Barang created successfully.');
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
}
