<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Ormawa;
use Illuminate\Http\Request;

class OrmawaController extends Controller
{
    public function listAll(){
        $ormawas = Ormawa::with('jenis_ormawa')->where('status', 'active')->groupBy('jenisOrmawa.nama')->get();
    
        return response()->json([
            'success' => true,
            'data' => $ormawas
        ]);
    }
}
