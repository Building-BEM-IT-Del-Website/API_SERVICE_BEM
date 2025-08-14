<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class OrmawaJabatanPermission extends Model
{
      protected $table = 'ormawa_jabatan_permissions';

    public $timestamps = true;

    // Relasi ke Permission
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    // Relasi ke Ormawa
    public function ormawa()
    {
        return $this->belongsTo(Ormawa::class, 'ormawa_id');
    }

    // Relasi ke Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
