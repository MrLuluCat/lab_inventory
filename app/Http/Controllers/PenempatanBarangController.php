<?php

namespace App\Http\Controllers;

use App\Models\PenempatanBarang;
use App\Models\DetailBarang;
use App\Models\Ruangan;
use App\Models\Barang; // Model untuk jenis barang
use Illuminate\Http\Request;

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
            'id_ruangan' => 'required|exists:ruangan,id',
            'tanggal_penempatan' => 'required|date',
            'pc_no' => 'required|array',
            'pc_no.*' => 'required|string|max:255',
            'id_jenis_barang' => 'required|array',
            'id_jenis_barang.*' => 'required|exists:barang,id',
            'id_detail_barang' => 'required|array',
            'id_detail_barang.*' => 'required|exists:detail_barang,id',
        ]);

        foreach ($request->id_detail_barang as $index => $id_detail_barang) {
            PenempatanBarang::create([
                'id_detail_barang' => $id_detail_barang,
                'id_ruangan' => $request->id_ruangan,
                'pc_no' => $request->pc_no[$index],
                'tanggal_penempatan' => $request->tanggal_penempatan,
            ]);
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
        $penempatanBarang->update([
            'id_detail_barang' => $request->id_detail_barang,
            'id_ruangan' => $request->id_ruangan,
            'pc_no' => $request->pc_no,
            'tanggal_penempatan' => $request->tanggal_penempatan,
        ]);

        return redirect()->route('penempatan_barang.index')->with('success', 'Penempatan Barang updated successfully.');
    }

    public function destroy($id)
    {
        $penempatanBarang = PenempatanBarang::findOrFail($id);
        $penempatanBarang->delete();

        return redirect()->route('penempatan_barang.index')->with('success', 'Penempatan Barang deleted successfully.');
    }
}
