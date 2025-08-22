<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PengumumanController extends Controller
{
    public function index()
    {
        return response()->json(Pengumuman::all(), 200);
    }
    public function store(Request $request)
    {
            // dd($request->all(), $request->file('files')); // <--- tambah ini

        $validator = Validator::make($request->all(), [
            'nama_pengumuman' => 'required|string|unique:pengumuman,nama_pengumuman',
            'deskripsi' => 'nullable|string',
            'kategoris_id' => 'required|exists:kategoris,id',
            'tipe_pengumuman' => 'nullable|in:Reminder,Info,Penting,Umum,Darurat,Lainnya',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date',
            'ormawa_id' => 'nullable|integer',
            'files' => 'nullable',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }


        $data = $validator->validated();
        $user = Auth::user();

        // Cek ormawa_id dikirim atau ambil default dari struktur organisasi user
        if (!empty($data['ormawa_id'])) {
            // Hanya user dengan jabatan Ketua yang valid
            $exists = DB::table('struktur_organisasis')
            ->join('jabatan', 'struktur_organisasis.jabatan_id', '=', 'jabatan.id')
            ->where('struktur_organisasis.user_id', $user->id)
                ->where('struktur_organisasis.ormawa_id', $data['ormawa_id'])
                ->where('struktur_organisasis.status', 'active')
                ->where('jabatan.nama', 'Ketua')
                ->exists();

                if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak memiliki hak sebagai Ketua di ormawa yang dipilih',
                    'data' => null
                ], 403);
            }
        } else {
            // Ambil ormawa_id default dari struktur organisasi user yang Ketua
            $ormawaAktif = DB::table('struktur_organisasis')
                ->join('jabatan', 'struktur_organisasis.jabatan_id', '=', 'jabatan.id')
                ->where('struktur_organisasis.user_id', $user->id)
                ->where('struktur_organisasis.status', 'active')
                ->where('jabatan.nama', 'Ketua')
                ->orderBy('struktur_organisasis.tanggal_mulai', 'desc')
                ->select('struktur_organisasis.ormawa_id')
                ->first();

            if (!$ormawaAktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak memiliki hak sebagai Ketua di ormawa manapun',
                    'data' => null
                ], 403);
            }

            $data['ormawa_id'] = $ormawaAktif->ormawa_id;
        }

        // Upload file jika ada
        $filePaths = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('public/pengumuman');
                $filePaths[] = str_replace('public/', '/storage/', $path);
            }
        }

        // Set default dan nullable
        $data['file_paths'] = $filePaths ?: null;
        $data['create_by'] = $user->id;
        $data['tipe_pengumuman'] = $data['tipe_pengumuman'] ?? 'Umum';
        $data['deskripsi'] = $data['deskripsi'] ?? '';

        $nullableFields = ['tanggal_mulai', 'tanggal_berakhir'];
        foreach ($nullableFields as $field) {
            if (!isset($data[$field])) $data[$field] = null;
        }

        // Simpan ke DB
        $pengumuman = Pengumuman::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dibuat',
            'data' => $pengumuman
        ], 201);
    }


    public function show($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return response()->json($pengumuman, 200);
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $data = $request->validate([
            'nama_pengumuman' => 'required|string|unique:pengumuman,nama_pengumuman,' . $id,
            'deskripsi' => 'nullable|string',
            'kategoris_id' => 'nullable|exists:kategoris,id',
            'file_paths' => 'nullable|array',
            'tipe_pengumuman' => 'in:Reminder,Info,Penting,Umum,Darurat,Lainnya',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date',
        ]);

        $pengumuman->update($data);
        return response()->json($pengumuman, 200);
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();
        return response()->json(['message' => 'Pengumuman berhasil dihapus'], 200);
    }

    public function trashed()
    {
        return response()->json(Pengumuman::onlyTrashed()->get(), 200);
    }

    public function restore($id)
    {
        $pengumuman = Pengumuman::onlyTrashed()->findOrFail($id);
        $pengumuman->restore();
        return response()->json(['message' => 'Pengumuman berhasil direstore'], 200);
    }

    public function forceDelete($id)
    {
        $pengumuman = Pengumuman::onlyTrashed()->findOrFail($id);
        $pengumuman->forceDelete();
        return response()->json(['message' => 'Pengumuman dihapus permanen'], 200);
    }
}
