<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $fillable = [
        'dokumen_kategori_id',
        'kategori',
        'judul',
        'deskripsi',
        'url_file',
        'tipe_file',
        'ukuran_file',
        'is_active',
        'order',
    ];

    public function kategoriRelation()
    {
        return $this->belongsTo(DokumenKategori::class, 'dokumen_kategori_id');
    }

    // Daftar kategori yang tersedia
    public static function kategoriList(): array
    {
        return [
            'hasimaru'         => 'Pedoman Hasimaru',
            'saps'             => 'Pedoman SAPS',
            'peraturan-ormawa' => 'Peraturan Ormawa',
            'sop'              => 'SOP',
            'sk-ormawa'        => 'SK Ormawa',
        ];
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::kategoriList()[$this->kategori] ?? $this->kategori;
    }
}
