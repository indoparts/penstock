<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tggl_transaksi',
        'data_produk_id',
        'qty',
        'customer',
        'ket',
    ];

    public function produk()
    {
        return $this->hasMany(DataProduk::class, 'id', 'data_produk_id');
    }
}
