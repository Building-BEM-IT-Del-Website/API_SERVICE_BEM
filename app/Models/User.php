<?php

namespace App\Models;

use App\Traits\HasStrukturPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, HasStrukturPermission, HasRoles;

     protected $guard_name = 'api';

    protected $fillable = [
        'username',
        'nama_lengkap',
        'status',
        'avatar',
        'email',
        'password',
        'role_id',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'deleted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'roles' => $this->getRoleNames(),
        ];
    }

    // Semua struktur organisasi user
    public function strukturOrganisasi()
    {
        return $this->hasMany(StrukturOrganisasi::class, 'user_id');
    }

    // Struktur organisasi aktif
    public function strukturOrganisasiAktif()
    {
        return $this->hasMany(StrukturOrganisasi::class, 'user_id')->where('status', 'active');
    }

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }
}
