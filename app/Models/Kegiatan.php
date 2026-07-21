<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'banner_path', 'description', 'date', 'location', 'ormawa_id'])]
class Kegiatan extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function ormawa()
    {
        return $this->belongsTo(Ormawa::class);
    }
}
