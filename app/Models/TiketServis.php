<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketServis extends Model
{
    protected $table = 'tiket_servis';

    protected $primaryKey = 'nomor_tiket';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'nomor_tiket',
        'tanggal_masuk',
        'tanggal_selesai',
        'keluhan_kerusakan',
        'id_pelanggan',
        'id_teknisi',
        'nomor_seri',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'nomor_seri', 'nomor_seri');
    }
}
