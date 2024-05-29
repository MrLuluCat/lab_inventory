<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'kategori_barang',
        'total',
        'stock',
        'used',
        'unused',
    ];

    public function detailBarangs()
    {
        return $this->hasMany(DetailBarang::class, 'id_jenis_barang');
    }
}
