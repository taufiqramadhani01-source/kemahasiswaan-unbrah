<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenKategori extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'dokumen_kategori_id');
    }
}
