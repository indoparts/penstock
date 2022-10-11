<?php

namespace App\Http\Controllers;

use App\Models\DataPenjualan;
use App\Models\DataProduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $penjualan = DataPenjualan::count();
        $produk = DataProduk::count();

        $chart = DataPenjualan::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(tggl_transaksi) as month_name"))
            ->whereYear('tggl_transaksi', date('Y'))
            ->groupBy(DB::raw("Month(tggl_transaksi)"))
            ->pluck('count', 'month_name');
        $labels = $chart->keys();
        $data = $chart->values();
        return view('home', compact('penjualan', 'produk', 'labels', 'data'));
    }
}
