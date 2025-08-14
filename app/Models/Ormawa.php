<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ormawa extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'nama',
        'jenis_ormawa_id',
        'deskripsi',
        'logo',
        'visi',
        'misi',
        'status',
    ];

    // Relasi ke jenis ormawa
    public function jenisOrmawa()
    {
        return $this->belongsTo(JenisOrmawa::class);
    }

    // Jika ada relasi ke user (misal 1 ormawa punya banyak user)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
