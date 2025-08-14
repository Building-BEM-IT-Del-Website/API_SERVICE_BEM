<?php

namespace App\Traits;

use App\Models\StrukturOrganisasi;
use Illuminate\Support\Facades\DB;

trait HasStrukturPermission
{
     public function hasStrukturPermission($permission)
    {
        $struktur = $this->strukturOrganisasi;

        if (!$struktur) {
            return false;
        }

        $jabatanId = $struktur->jabatan_id;
        $ormawaId = $struktur->ormawa_id;

        return DB::table('ormawa_jabatan_permissions')
            ->where('jabatan_id', $jabatanId)
            ->where('ormawa_id', $ormawaId)
            ->whereHas('permission', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }
    }

