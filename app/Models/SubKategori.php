<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubKategori extends Model
{
      use SoftDeletes;

    protected $fillable = [
        'nama_sub_kategori',
        'create_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
}
