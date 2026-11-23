<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeknisiController extends Controller
{
    // GET - Melihat semua data teknisi
    public function index()
    {
        return response()->json(
            Teknisi::with('person')->get()
        );
    }

    // GET - Melihat satu data teknisi
    public function show($id)
    {
        $teknisi = Teknisi::with('person')->findOrFail($id);

        return response()->json($teknisi);
    }

    // POST - Menambahkan data teknisi
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_teknisi' => 'required|string|max:10|unique:teknisi,id_teknisi|unique:person,id_person',
            'nama' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'bidang_keahlian' => 'nullable|string|max:100',
            'status_ketersediaan' => 'nullable|string|max:30',
        ]);

        $teknisi = DB::transaction(function () use ($data, $request) {

            Person::create([
                'id_person' => $data['id_teknisi'],
                'nama' => $data['nama'],
                'no_telepon' => $data['no_telepon'] ?? null,
            ]);

            return Teknisi::create([
                'id_teknisi' => $data['id_teknisi'],
                'bidang_keahlian' => $data['bidang_keahlian'] ?? null,
                'status_ketersediaan' => $data['status_ketersediaan'] ?? null,
                'user_id' => $request->user()->id,
            ]);
        });

        return response()->json([
            'message' => 'Teknisi berhasil ditambahkan',
            'data' => $teknisi->load('person')
        ], 201);
    }

    // PUT - Mengubah data teknisi
    public function update(Request $request, $id)
    {
        $teknisi = Teknisi::findOrFail($id);

        $user = $request->user();

        // User hanya boleh mengubah data miliknya
        if ($user->role !== 'admin' &&
            $teknisi->user_id != $user->id) {

            return response()->json([
                'message' => 'Anda hanya boleh mengubah teknisi milik sendiri'
            ], 403);
        }

        $data = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'no_telepon' => 'sometimes|nullable|string|max:20',
            'bidang_keahlian' => 'sometimes|nullable|string|max:100',
            'status_ketersediaan' => 'sometimes|nullable|string|max:30',
        ]);

        DB::transaction(function () use ($teknisi, $data) {

            // Data yang disimpan pada tabel person
            $personData = array_intersect_key(
                $data,
                array_flip(['nama', 'no_telepon'])
            );

            if (!empty($personData)) {
                Person::where(
                    'id_person',
                    $teknisi->id_teknisi
                )->update($personData);
            }

            // Data yang disimpan pada tabel teknisi
            $teknisiData = array_diff_key(
                $data,
                array_flip(['nama', 'no_telepon'])
            );

            if (!empty($teknisiData)) {
                $teknisi->update($teknisiData);
            }
        });

        return response()->json([
            'message' => 'Teknisi berhasil diperbarui',
            'data' => $teknisi->fresh('person')
        ]);
    }

    // DELETE - Hanya Admin boleh menghapus teknisi
    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses khusus Admin'
            ], 403);
        }

        $teknisi = Teknisi::findOrFail($id);

        $teknisi->delete();

        return response()->json([
            'message' => 'Teknisi berhasil dihapus'
        ]);
    }
}
