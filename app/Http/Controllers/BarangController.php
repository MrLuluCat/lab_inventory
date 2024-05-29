<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('pages.barang.index', compact('barang'));
    }

    public function create()
    {
        return view('pages.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_barang' => 'required|string|max:255'
        ]);
        
        $default_value = '0';

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'kategori_barang' => $request->kategori_barang,
            'total' => $default_value,
            'stock' => $default_value,
            'used' => $default_value,
            'unused' => $default_value,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang created successfully.');
    }

     public function show()
     {
        //
     }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('pages.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_barang' => 'required|string|max:255'
        ]);

        $default_value = 0;

        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kategori_barang' => $request->kategori_barang,
            'total' => $default_value,
            'stock' => $default_value,
            'used' => $default_value,
            'unused' => $default_value,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang updated successfully.');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang deleted successfully.');
    }
}
