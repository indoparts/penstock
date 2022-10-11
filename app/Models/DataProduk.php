<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'ukuran',
        'harga',
        'stok',
        'keterangan',
        'img',
    ];

    public function penjualan()
    {
        return $this->belongsTo(DataPenjualan::class, 'data_produk_id', 'id');
    }
    public function peramalan()
    {
        return $this->belongsTo(Peramalan::class, 'data_produk_id', 'id');
    }
    public function preOrder()
    {
        return $this->belongsTo(PreOrder::class, 'data_produk_id', 'id');
    }
}
