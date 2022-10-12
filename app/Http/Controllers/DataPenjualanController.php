<?php

namespace App\Http\Controllers;

use App\Models\DataPenjualan;
use App\Models\DataProduk;
use Illuminate\Http\Request;

class DataPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DataPenjualan::with('produk')->latest()->paginate(10);
        return view('penjualan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produk = DataProduk::latest()->get();
        return view('penjualan.create', compact('produk'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data_produk_id' => 'required|numeric',
            'tggl_transaksi' => 'required|date',
            'lembar' => 'required|numeric',
            'ket' => 'required|max:255',
        ]);
        $input = $request->all();
        $input['tggl_transaksi'] = date("Y-m-d", strtotime($input['tggl_transaksi']));
        $find = DataProduk::find($input['data_produk_id']);
        if ($input['lembar'] <= $find->stok) {
            if ($find->update(['stok' => $find->stok - $input['lembar']])) {
                DataPenjualan::create($input);
                return redirect('penjualan/create')->with('success', 'Data berhasil ditambahkan');
            }
        }
        return redirect('penjualan/create')->with('error', 'Data gagal ditambahkan, jumlah yang dipesan melebihi jumlah stok persediaan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataPenjualan  $dataPenjualan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DataPenjualan::find($id);
        $produk = DataProduk::latest()->get();
        return view('penjualan.edit', compact('data', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataPenjualan  $dataPenjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'data_produk_id' => 'required|numeric',
            'tggl_transaksi' => 'required|date',
            'lembar' => 'required|numeric',
            'ket' => 'required|max:255',
        ]);
        $input = $request->all();
        $input['tggl_transaksi'] = date("Y-m-d", strtotime($input['tggl_transaksi']));
        $find = DataProduk::find($input['data_produk_id']);
        if ($input['lembar'] <= $find->stok) {
            if ($find->update(['stok' => $find->stok - $input['lembar']])) {
                DataPenjualan::find($id)->update($input);
                return redirect('penjualan/create')->with('success', 'Data berhasil diperbaharui');
            }
        }
        return redirect('penjualan/create')->with('error', 'Data gagal ditambahkan, jumlah yang dipesan melebihi jumlah stok persediaan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataPenjualan  $dataPenjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = DataPenjualan::find($id);
        $find->delete();
        return redirect('penjualan')->with('status', 'Data berhasil dihapus');
    }
}
