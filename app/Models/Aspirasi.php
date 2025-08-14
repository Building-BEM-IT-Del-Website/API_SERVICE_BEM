<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aspirasi extends Model
{
    use SoftDeletes;

    protected $table = 'aspirasi';
    protected $fillable = [
        'judul',
        'deskripsi',
        'nama',
        'respon',
        'read_by',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function Reader(){
        return $this->belongsTo(User::class, 'read_by');
    }
}
