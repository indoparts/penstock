<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Administrator',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('123456')
        // ]);
        $penjualan = [
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('01/01/2021')),
                'lembar' => 62,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('02/01/2021')),
                'lembar' => 86,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('03/01/2021')),
                'lembar' => 78,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('04/01/2021')),
                'lembar' => 93,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('05/01/2021')),
                'lembar' => 65,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('06/01/2021')),
                'lembar' => 77,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('07/01/2021')),
                'lembar' => 79,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('08/01/2021')),
                'lembar' => 87,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('09/01/2021')),
                'lembar' => 92,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('10/01/2021')),
                'lembar' => 95,
                'ket' => '-',
            ],
            [
                'data_produk_id' => 1,
                'tggl_transaksi' => date('Y-m-d', strtotime('11/01/2021')),
                'lembar' => 88,
                'ket' => '-',
            ]
        ];
        \App\Models\DataPenjualan::insert($penjualan);
    }
}
