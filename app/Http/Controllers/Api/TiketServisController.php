<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TiketServis;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TiketServisController extends Controller
{
    // Admin dan User dapat melihat semua tiket
    public function index()
    {
        return response()->json(TiketServis::all());
    }

    // Admin dan User dapat melihat detail tiket
    public function show($nomor_tiket)
    {
        return response()->json(
            TiketServis::findOrFail($nomor_tiket)
        );
    }

    // Admin dan User dapat membuat tiket
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_tiket' => 'required|string|max:20|unique:tiket_servis,nomor_tiket',
            'tanggal_masuk' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_masuk',
            'keluhan_kerusakan' => 'nullable|string',
            'id_pelanggan' => 'required|string|exists:pelanggan,id_pelanggan',
            'id_teknisi' => 'required|string|exists:teknisi,id_teknisi',
            'nomor_seri' => 'required|string|exists:perangkat,nomor_seri',
        ]);

        // Pemilik tiket otomatis dari akun yang login
        $data['user_id'] = $request->user()->id;

        $tiket = TiketServis::create($data);

        return response()->json([
            'message' => 'Tiket servis berhasil ditambahkan',
            'data' => $tiket
        ], 201);
    }

    // Admin boleh mengubah semua tiket
    // User hanya boleh mengubah tiket miliknya
    public function update(Request $request, $nomor_tiket)
    {
        $tiket = TiketServis::findOrFail($nomor_tiket);
        $user = $request->user();

        if ($user->role !== 'admin' &&
            $tiket->user_id != $user->id) {
            return response()->json([
                'message' => 'Anda hanya boleh mengubah tiket servis milik sendiri'
            ], 403);
        }

        $data = $request->validate([
            'tanggal_masuk' => 'sometimes|nullable|date',
            'tanggal_selesai' => 'sometimes|nullable|date',
            'keluhan_kerusakan' => 'sometimes|nullable|string',
            'id_pelanggan' => 'sometimes|required|string|exists:pelanggan,id_pelanggan',
            'id_teknisi' => 'sometimes|required|string|exists:teknisi,id_teknisi',
            'nomor_seri' => 'sometimes|required|string|exists:perangkat,nomor_seri',
        ]);

        $tiket->update($data);

        return response()->json([
            'message' => 'Tiket servis berhasil diperbarui',
            'data' => $tiket
        ]);
    }

    // Hanya Admin dapat menghapus tiket
    public function destroy(Request $request, $nomor_tiket)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses khusus Admin'
            ], 403);
        }

        $tiket = TiketServis::findOrFail($nomor_tiket);
        $tiket->delete();

        return response()->json([
            'message' => 'Tiket servis berhasil dihapus'
        ]);
    }
}
