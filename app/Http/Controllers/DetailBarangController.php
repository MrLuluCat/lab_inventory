<?php

namespace App\Http\Controllers;

use App\Models\DetailBarang;
use App\Models\SuratMasuk;
use App\Models\Barang;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class DetailBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = DetailBarang::query();

        $detailBarang = $query->with('ruangan', 'suratMasuk')->get();

        return view('pages.detail_barang.index', compact('detailBarang'));
    }

    public function create()
    {
        $barangs = Barang::all();
        $suratMasuks = SuratMasuk::all();
        $ruangans = Ruangan::all();

        return view('pages.detail_barang.create', compact('barangs', 'suratMasuks', 'ruangans'));
    }


    public function store(Request $request)
    {

        // dd($request->all());
        
        // // Validasi input
        // $request->validate([
        //     // 'quantity' => 'required|array',
        //     'merek' => 'required|array',
        //     'kode_inventaris' => 'required|array',
        //     'no_surat' => 'required|exists:surat_masuk,id',
        //     'kondisi' => 'required|string',
        //     'lokasi' => 'required|exists:ruangan,id',
        // ]);

        foreach ($request->quantity as $barang_id => $quantity) {
            
            $barang = Barang::findOrFail($barang_id);
            $barang->quantity += $quantity;
            $barang->barang_tersedia += $quantity;
            $barang->save();

            for ($i = 0; $i < $quantity; $i++) {
                DetailBarang::create([
                    'no_surat' => $request->no_surat,
                    'id_jenis_barang' => $barang_id,
                    'merek' => $request->merek[$barang_id][$i],
                    'kode_inventaris' => $request->kode_inventaris[$barang_id][$i],
                    'kondisi' => $request->kondisi,
                    'lokasi' => $request->lokasi,
                ]);
            }
        }

        return redirect()->to('detail_barang')->withSuccess('Detail Barang created successfully.');
    }


    public function show(DetailBarang $detailBarang)
    {
        return view('pages.detail_barang.show', compact('detailBarang'));
    }

    public function edit(DetailBarang $detailBarang)
    {
        $barangs = Barang::all();
        return view('pages.detail_barang.edit', compact('detailBarang', 'barangs'));
    }

    public function update(Request $request, DetailBarang $detailBarang)
    {
        $request->validate([
            'no_surat' => 'required|exists:surat_masuk,id',
            'id_jenis_barang' => 'required|exists:barang,id',
            'merek' => 'required',
            'kode_inventaris' => 'required|unique:detail_barang,kode_inventaris,' . $detailBarang->id,
            'kondisi' => 'required',
            'lokasi' => 'required|exists:ruangan,id',
        ]);

        $detailBarang->update($request->all());
        return redirect()->to('detail_barang')->withSuccess('Detail Barang updated successfully.');

    }

    public function destroy(DetailBarang $detailBarang)
    {
        $detailBarang->delete();
        return redirect()->to('detail_barang')->withSuccess('Detail Barang deleted successfully.');

    }

    public function getSuratMasukData($id)
    {
        // Ambil data Detail Barang berdasarkan id
        $detailBarang = DetailBarang::find($id);

        // Cek apakah data Detail Barang ditemukan
        if ($detailBarang) {
            // Ambil data Surat Masuk berdasarkan no_surat dari Detail Barang
            $suratMasuk = SuratMasuk::where('id', $detailBarang->no_surat)->get();

            // Kembalikan response dalam format JSON untuk DataTables
            return response()->json(['data' => $suratMasuk]);
        }

        // Jika tidak ditemukan, kembalikan response kosong
        return response()->json(['data' => []]);
    }

}
