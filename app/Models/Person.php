<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';

    protected $primaryKey = 'id_person';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_person',
        'nama',
        'no_telepon',
    ];

    public function pelanggan()
    {
        return $this->hasOne(
            Pelanggan::class,
            'id_pelanggan',
            'id_person'
        );
    }
}
