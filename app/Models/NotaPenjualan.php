<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaPenjualan extends Model
{
    protected $table = 'nota_penjualan';

    protected $primaryKey = 'nomor_nota';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'nomor_nota',
        'tanggal_transaksi',
        'metode_pembayaran',
        'total_biaya',
        'id_staf',
        'id_pelanggan',
        'nomor_seri',
        'user_id',
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
