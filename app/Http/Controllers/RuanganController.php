<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Models\PenempatanBarang;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangan = Ruangan::all();
        return view('pages.ruangan.index', compact('ruangan'));
    }

    public function create()
    {
        return view('pages.ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        Ruangan::create([
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan created successfully.');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('pages.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update([
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas' => $request->kapasitas,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan updated successfully.');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('ruangan.index')->with('success', 'Ruangan deleted successfully.');
    }

    public function getPenempatanByRuangan($ruanganId)
    {
        // Ambil data penempatan berdasarkan ruangan tujuan di transaksi pemindahan
        $penempatanBarang = PenempatanBarang::whereHas('transaksiPemindahan', function ($query) use ($ruanganId) {
            $query->where('id_ruangan_tujuan', $ruanganId);
        })
            ->groupBy('pc_number')
            ->select('pc_number')
            ->get();

        return response()->json(['data' => $penempatanBarang]);
    }

    public function getDetailBarang($ruanganId, $pcNumber)
    {
        // Ambil data barang yang ditempatkan di ruangan dan PC tertentu
        $penempatanBarang = PenempatanBarang::with(['detailBarang.barang'])
            ->where('pc_number', $pcNumber)
            ->whereHas('detailBarang', function ($query) use ($ruanganId) {
                $query->where('lokasi', $ruanganId);
            })
            ->get();

        return response()->json(['data' => $penempatanBarang]);
    }

}
