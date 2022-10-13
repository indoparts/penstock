<?php

namespace App\Http\Controllers;

use App\Models\DataProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DataProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DataProduk::latest()->simplePaginate(10);
        if (request()->has('cari')) {
            $data = DataProduk::where('nama_produk', 'like', request()->cari)->simplePaginate(10);
        }
        return view('produk.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('produk.create');
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
            'nama_produk' => 'required|unique:data_produks|max:100',
            'ukuran' => 'required|max:20',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'keterangan' => 'required|max:255',
            'img' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
        ]);
        try {
            $input = $request->all();
            $image = $request->file('img');
            $photoname = date('YmdHis') . '.' . $image->extension();
            $filePath = public_path('/img/produk/');
            $image->move($filePath, $photoname);
            $input['img'] = $photoname;
            DataProduk::create($input);
            return redirect('produk/create')->with('status', 'Data berhasil ditambahkan')->with('image', $photoname);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataProduk  $dataProduk
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DataProduk::find($id);
        return view('produk.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataProduk  $dataProduk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|max:100|unique:data_produks,nama_produk,' . $id,
            'ukuran' => 'required|max:20',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'keterangan' => 'required|max:255',
            'img' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
        ]);
        try {
            $input = $request->all();
            $find = DataProduk::find($id);
            if (File::exists(public_path('img/produk/' . $find->img))) {
                File::delete(public_path('img/produk/' . $find->img));
            }
            $image = $request->file('img');
            $photoname = date('YmdHis') . '.' . $image->extension();
            $filePath = public_path('/img/produk/');
            $image->move($filePath, $photoname);
            $input['img'] = $photoname;
            DataProduk::find($id)->update($input);
            return redirect('produk/' . $id . '/edit')->with('status', 'Data berhasil diperbaharui')->with('image', $photoname);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataProduk  $dataProduk
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = DataProduk::find($id);
        if (File::exists(public_path('img/produk/' . $find->img))) {
            File::delete(public_path('img/produk/' . $find->img));
        }
        try {
            $find->delete();
            return redirect('produk')->with('status', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
