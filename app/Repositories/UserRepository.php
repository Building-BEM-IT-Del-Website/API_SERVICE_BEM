<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll()
    {
        return User::with('roles')->get(); // gunakan eager loading jika pakai spatie/permission
    }
}
