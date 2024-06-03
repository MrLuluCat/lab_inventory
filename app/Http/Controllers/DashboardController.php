<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\SuratMasuk;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use App\Models\PenempatanBarang;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surat_masuk_count = SuratMasuk::count();
        $barang_count = Barang::count();
        $detail_barang_count = DetailBarang::count();
        $penempatan_barang_count = PenempatanBarang::count();
        $ruangan_count = Ruangan::count();

        $barangs = Barang::all();
        $ruangans = Ruangan::all();

        return view('pages.dashboard', compact(
            'surat_masuk_count',
            'barang_count',
            'detail_barang_count',
            'penempatan_barang_count',
            'ruangan_count',
            'barangs',
            'ruangans'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
