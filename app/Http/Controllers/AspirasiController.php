<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AspirasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $aspirasis = Aspirasi::all();
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Daftar semua aspirasi',
        //     'data' => $aspirasis
        // ], 200);
        $user = Auth::user();

        if ($user && $user->hasAnyRole(['admin', 'kemahasiswaan'])) {
            // Admin dan kemahasiswaan lihat semua aspirasi
            $aspirasis = Aspirasi::all();
        } else {
            // Publik/mahasiswa hanya lihat yang status "In Progress"
            $aspirasis = Aspirasi::whereIn('status', ['In Progress', 'Approved', 'Completed'])->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar aspirasi',
            'data' => $aspirasis
        ], 200);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            // 'status' => 'required|in:Pending,Approved,Rejected,Completed,In Progress',
            'nama' => 'nullable|string|max:255',
            'respon' => 'nullable|string',
        ]);

        // $data['create_by'] = auth()->id();

        // $aspirasi = Aspirasi::create($data);
        $aspirasi = Aspirasi::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil ditambahkan',
            'data' => $aspirasi
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $aspirasi = Aspirasi::find($id);
        if (!$aspirasi) {
            return response()->json([
                'success' => false,
                'message' => 'Aspirasi tidak ditemukan',
                'data' => null
            ], 404);
        }

        if (auth()->check() && is_null($aspirasi->Reader)) {
            $aspirasi->update([
                'status' => 'In Progress',
                'read_by' => auth()->$id(),
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Detail aspirasi',
            'data' => $aspirasi
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aspirasi $aspirasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Aspirasi $aspirasi)
    // {
    //     if (!$aspirasi) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Aspirasi tidak ditemukan',
    //             'data' => null
    //         ], 404);
    //     }
    //     $data = $request->validate([
    //         'judul' => 'sometimes|required|string|max:255',
    //         'deskripsi' => 'sometimes|required|string',
    //         'status' => 'sometimes|required|in:Pending,Approved,Rejected,Completed,In Progress',
    //         'nama' => 'nullable|string|max:255',
    //         'respon' => 'nullable|string',
    //     ]);
    //     $aspirasi->update($data);
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Aspirasi berhasil diperbarui',
    //         'data' => $aspirasi
    //     ], 200);
    // }
    public function update(Request $request, Aspirasi $aspirasi)
{
    $data = $request->validate([
        'judul' => 'sometimes|required|string|max:255',
        'deskripsi' => 'sometimes|required|string',
        'status' => 'sometimes|required|in:Pending,Approved,Rejected,Completed,In Progress',
        'nama' => 'nullable|string|max:255',
        'respon' => 'nullable|string',
    ]);

    $user = Auth::user();

    // Jika ada field status yang diubah, cek role admin
    if (isset($data['status']) && (!$user || !$user->hasRole('admin'))) {
        return response()->json([
            'success' => false,
            'message' => 'Hanya admin yang dapat mengubah status',
        ], 403);
    }

    $aspirasi->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Aspirasi berhasil diperbarui',
        'data' => $aspirasi
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aspirasi $aspirasi)
    {
        if (!$aspirasi) {
            return response()->json([
                'success' => false,
                'message' => 'Aspirasi tidak ditemukan',
                'data' => null
            ], 404);
        }
        $aspirasi->delete();
        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil dihapus (soft delete)',
            'data' => null
        ], 200);

    }
    /**
     * Display a listing of the trashed resources.
     */
    public function trashed()
    {
        $aspirasis = Aspirasi::onlyTrashed()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar aspirasi yang terhapus',
            'data' => $aspirasis
        ], 200);
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore($id)
    {
        $aspirasi = Aspirasi::onlyTrashed()->find($id);
        if (!$aspirasi) {
            return response()->json([
                'success' => false,
                'message' => 'Aspirasi tidak ditemukan di trash',
                'data' => null
            ], 404);
        }
        $aspirasi->restore();
        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil direstore',
            'data' => $aspirasi
        ], 200);
    }

    /**
     * Permanently delete the specified resource from trash.
     */
    public function forceDelete($id)
    {
        $aspirasi = Aspirasi::onlyTrashed()->find($id);
        if (!$aspirasi) {
            return response()->json([
                'success' => false,
                'message' => 'Aspirasi tidak ditemukan di trash',
                'data' => null
            ], 404);
        }
        $aspirasi->forceDelete();
        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil dihapus permanen',
            'data' => null
        ], 200);
    }
}

