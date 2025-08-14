<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aspirasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aspirasi';

    protected $fillable = [
        'judul',
        'deskripsi',
        'type',
        'nama',
        'respon',
    ];

    protected $dates = ['deleted_at'];
}
