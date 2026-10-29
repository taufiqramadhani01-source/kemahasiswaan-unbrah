<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'logo_path', 'image_path', 'description', 'structure', 'focus_items', 'order'])]
class Ormawa extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'structure'   => 'array',
            'focus_items' => 'array',
        ];
    }

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
