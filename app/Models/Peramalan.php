<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peramalan extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_produk_id',
        'tahun',
        'penjualan',
        'peramalan',
    ];

    public function produk()
    {
        return $this->hasMany(DataProduk::class, 'id', 'data_produk_id');
    }
}
