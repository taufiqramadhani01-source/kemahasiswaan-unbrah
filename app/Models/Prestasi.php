<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['student_name', 'title', 'level', 'year', 'certificate_path'])]
class Prestasi extends Model
{
    use HasFactory;
}
