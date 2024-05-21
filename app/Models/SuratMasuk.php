<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'no_surat',
        'tanggal_surat_masuk',
        'document_path',
    ];

    public function detailBarangs()
    {
        return $this->hasMany(DetailBarang::class, 'no_surat');
    }
}
