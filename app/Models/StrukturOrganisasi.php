<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StrukturOrganisasi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'ormawa_id',
        'jabatan_id',
        'periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    // Auto-generate periode sebelum menyimpan jika belum diset
    protected static function booted()
    {
         static::creating(function ($struktur) {
        if (empty($struktur->periode) && !empty($struktur->tanggal_mulai)) {
            $tahun = date('Y', strtotime($struktur->tanggal_mulai));
            $struktur->periode = $tahun . '-' . ($tahun + 1);
        }
    });

    static::updating(function ($struktur) {
        if (
            $struktur->isDirty('status') &&
            $struktur->status === 'nonactive' &&
            empty($struktur->tanggal_selesai)
        ) {
            $struktur->tanggal_selesai = now();
        }
    });
    }

    // Relasi opsional (jika kamu perlukan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ormawa()
    {
        return $this->belongsTo(Ormawa::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
