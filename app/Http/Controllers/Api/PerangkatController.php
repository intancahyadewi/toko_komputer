<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perangkat;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    // Melihat semua perangkat (Admin dan User)
    public function index()
    {
        return response()->json(Perangkat::all());
    }

    // Melihat satu perangkat (Admin dan User)
    public function show($nomor_seri)
    {
        return response()->json(
            Perangkat::findOrFail($nomor_seri)
        );
    }

    // Menambahkan perangkat (Admin dan User)
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_seri' => 'required|string|max:50|unique:perangkat,nomor_seri',
            'merek' => 'nullable|string|max:50',
            'model' => 'nullable|string|max:50',
            'kondisi' => 'nullable|string|max:100',
            'status_kepemilikan' => 'nullable|string|max:50',
        ]);

        // Pemilik otomatis berdasarkan akun yang login
        $data['user_id'] = $request->user()->id;

        $perangkat = Perangkat::create($data);

        return response()->json([
            'message' => 'Perangkat berhasil ditambahkan',
            'data' => $perangkat
        ], 201);
    }

    // Mengubah perangkat
    public function update(Request $request, $nomor_seri)
    {
        $perangkat = Perangkat::findOrFail($nomor_seri);
        $user = $request->user();

        // User hanya boleh mengubah perangkat miliknya sendiri
        // Admin boleh mengubah semua perangkat
        if ($user->role !== 'admin' &&
            $perangkat->user_id !== $user->id) {

            return response()->json([
                'message' => 'Anda hanya boleh mengubah perangkat milik sendiri'
            ], 403);
        }

        $data = $request->validate([
            'merek' => 'sometimes|nullable|string|max:50',
            'model' => 'sometimes|nullable|string|max:50',
            'kondisi' => 'sometimes|nullable|string|max:100',
            'status_kepemilikan' => 'sometimes|nullable|string|max:50',
        ]);

        $perangkat->update($data);

        return response()->json([
            'message' => 'Perangkat berhasil diperbarui',
            'data' => $perangkat
        ]);
    }

    // Menghapus perangkat (Khusus Admin)
    public function destroy(Request $request, $nomor_seri)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses khusus Admin'
            ], 403);
        }

        $perangkat = Perangkat::findOrFail($nomor_seri);

        $perangkat->delete();

        return response()->json([
            'message' => 'Perangkat berhasil dihapus'
        ]);
    }
}
