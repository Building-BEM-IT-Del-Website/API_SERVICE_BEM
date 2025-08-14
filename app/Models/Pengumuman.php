<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
     use SoftDeletes;

    protected $table = 'pengumuman';

    protected $fillable = [
        'nama_pengumuman',
        'deskripsi',
        'kategoris_id',
        'file_paths',
        'tipe_pengumuman',
        'tanggal_mulai',
        'tanggal_berakhir',
        'create_by'
    ];

    protected $casts = [
        'file_paths' => 'array',
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategoris_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
}
