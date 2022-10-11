<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPenjualan extends Model
{
    use HasFactory;
    protected $fillable = [
        'data_produk_id',
        'tggl_transaksi',
        'lembar',
        'ket',
    ];

    public function produk()
    {
        return $this->hasOne(DataProduk::class, 'id', 'data_produk_id');
    }
}
