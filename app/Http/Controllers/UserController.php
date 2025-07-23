<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur search (berdasarkan nama_lengkap atau email misalnya)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Pagination default 10 per halaman
        $users = $query->latest()->paginate(10);

        return UserResource::collection($users);
    }


    public function store(Request $request)
{
    $validated = $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'username'     => 'required|string|max:255|unique:users',
        'email'        => 'required|email|unique:users',
        'status'       => 'required|in:aktif,nonaktif',
        'password'     => 'required|string|min:6',
        'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('avatar')) {
        $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
    } else {
        $validated['avatar'] = 'avatars/default.png'; // default avatar
    }

    $validated['password'] = Hash::make($validated['password']);

    $user = User::create($validated);

    return new UserResource($user);
}


    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'sometimes|string|max:255',
            'username'     => ['sometimes', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email'        => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'status'       => 'sometimes|in:active,inactive',
            'password'     => 'nullable|string|min:6',
            'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User soft deleted.']);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return new UserResource($user);
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->forceDelete();

        return response()->json(['message' => 'User permanently deleted.']);
    }
}
