<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tstokmutasih extends Model
{
    use HasFactory;

    protected $table = 'tstokmutasih';
    protected $guarded = [
        'gudang_tujuan',
    ];

    public function gudangAsal()
    {
        return $this->hasOne(Mgudang::class, 'id', 'gudangasal_id');
    }

    public function gudangTujuan()
    {
        return $this->hasOne(Mgudang::class, 'id', 'gudangtujuan_id');
    }

    public function stokMutasiDetail()
    {
        return $this->hasMany(Tstokmutasid::class, 'stokmutasih_id', 'id');
    }

    public static function generateKode($prefix)
    {
        $kode = $prefix;
        $result = static::query()->where('kode', 'LIKE', $prefix . '%')->get();

        $numerator = count($result) + 1;
        $kode .= str_pad($numerator, 4, '0', STR_PAD_LEFT);

        return $kode;
    }
}
