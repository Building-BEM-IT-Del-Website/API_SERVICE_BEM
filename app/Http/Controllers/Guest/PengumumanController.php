<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
    /**
     * Mengambil daftar pengumuman dengan filter dan pagination.
     */
    public function index(Request $request)
    {
        $query = Pengumuman::query();

        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('nama_pengumuman', 'like', $searchTerm)
                    ->orWhere('deskripsi', 'like', $searchTerm);
            });
        });

        $query->when($request->filled('year') && $request->filled('month'), function ($q) use ($request) {
            $q->whereYear('created_at', $request->year)
                ->whereMonth('created_at', $request->month);
        });

        $pengumuman = $query->with('kategori')
            ->latest()
            ->paginate(3) // <-- Menampilkan 3 item per halaman
            ->appends($request->query()); // <-- Sangat penting agar filter tetap ada saat pindah halaman

        return response()->json([
            'success' => true,
            'data' => $pengumuman
        ]);
    }

    public function show($id)
    {
        $pengumuman = Pengumuman::with(['kategori', 'creator', 'ormawa'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pengumuman
        ]);
    }

    /**
     * Mengambil daftar arsip unik (Bulan & Tahun).
     */
    public function archives()
    {
        $archives = Pengumuman::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereNotNull('created_at')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $archives,
        ]);
    }
}