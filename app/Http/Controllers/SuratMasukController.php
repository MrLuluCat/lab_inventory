<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::with(['detailBarangs.barang', 'detailBarangs.ruangan'])->get();
        return view('pages.surat_masuk.index', compact('suratMasuk'));
    }


    public function create()
    {
        return view('pages.surat_masuk.create');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('document_path')) {
            $file = $request->file('document_path');
            $originalFilename = $file->getClientOriginalName();
            $path = $file->storeAs('documents', $originalFilename, 'public');
        }

        SuratMasuk::create([
            'no_surat' => $request->no_surat,
            'tanggal_surat_masuk' => $request->tanggal_surat_masuk,
            'document_path' => $path,
        ]);

        return redirect()->to('surat_masuk')->withSuccess('Surat Masuk created successfully.');
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        return view('pages.surat_masuk.edit', compact('suratMasuk'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $request->validate([
            'no_surat' => 'required|unique:surat_masuk,no_surat,' . $suratMasuk->id,
            'tanggal_surat_masuk' => 'required|date',
        ]);

        $suratMasuk->update($request->all());

        return redirect()->route('surat_masuk.index')->with('success', 'Surat Masuk updated successfully.');
    }

    public function destroy($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);

        if ($suratMasuk->document_path) {
            Storage::disk('public')->delete($suratMasuk->document_path);
        }

        $suratMasuk->delete();

        return redirect()->to('surat_masuk')->withSuccess('Surat Masuk deleted successfully.');
    }

    public function getDetailBarangData($id)
    {
        $suratMasuk = SuratMasuk::find($id);

        if ($suratMasuk) {
            $detailBarang = DetailBarang::with('barang', 'ruangan')
                ->where('no_surat', $suratMasuk->id)
                ->get();

            return response()->json(['data' => $detailBarang]);
        }

        return response()->json(['data' => []]);
    }
}
