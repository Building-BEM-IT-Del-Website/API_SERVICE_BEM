<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kalender extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kalender';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_berakhir',
        'sumber',
        'create_by',
    ];
    protected $hidden = [
        'create_by',
        'deleted_at',
    ];

    protected $casts = [
        'sumber' => 'array',
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'create_by');
    }

}
