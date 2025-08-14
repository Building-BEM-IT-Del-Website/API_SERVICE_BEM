<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sub_kategoris_id',
        'nama',
        'deskripsi',
        'create_by',
    ];

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class, 'sub_kategoris_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
}
