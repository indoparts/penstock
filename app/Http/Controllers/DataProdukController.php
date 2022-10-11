<?php

namespace App\Http\Controllers;

use App\Models\DataProduk;
use App\Models\Peramalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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

    public function perhitungan()
    {
        $a = 0.9;
        $arr = array();
        $sql2 = DataProduk::get();
        foreach ($sql2 as $c) {
            $sql = DB::select("SELECT SUM(lembar) AS jumlah, YEAR(tggl_transaksi) as tahun,MONTH(tggl_transaksi) AS bulan FROM data_penjualans WHERE 
    data_produk_id=" . $c['id'] . " GROUP BY YEAR(tggl_transaksi),MONTH(tggl_transaksi)");
            $arrsementara = array();
            $periode = array();
            foreach ($sql as $row) {
                array_push($arrsementara, $row->jumlah); // menginput data penjualan per bulan kedalam array untuk proses perhitungan selanjutnya
                array_push($periode, $row->tahun . '-' . $row->bulan . '-01');
                $bulan = $row->bulan;
                $tahun = $row->tahun;
            }
            $dataramal = array('0', $arrsementara[0]);
            Peramalan::create([
                'data_produk_id' => $c['id'],
                'tahun' => $periode[0],
                'penjualan' => 0,
                'peramalan' => 0,
            ]);
            // Peramalan::create([
            //     'data_produk_id' => $c['id'],
            //     'tahun' => $periode[1],
            //     'penjualan' => $arrsementara[0],
            //     'peramalan' => $arrsementara[0],
            // ]);
            if (count($arrsementara) > 1) {
                $error = array($arrsementara[1] - $arrsementara[0]);
                $error2 = array(pow($error[0], 2));  // menghitung nilai error^2 perbulan
            }
            $ramal = $arrsementara[0];
            for ($i = 2; $i <= count($arrsementara); $i++) {
                $x1 = $arrsementara[$i - 1];  //mendapatkan nilai penjualan bulan 3 dan seterusnya
                $ramal = $a * $x1 + (1 - $a) * $ramal; // perhitungan nilai peramalan bulan 3 dan seterusnya berdasarkan metode
                array_push($dataramal, $ramal);
                Peramalan::create([
                    'data_produk_id' => $c['id'],
                    'tahun' => $periode[$i],
                    'penjualan' => $$ramal,
                    'peramalan' => $$ramal,
                ]);
                if ($i < count($arrsementara)) {
                    $err = $arrsementara[$i] - $ramal;
                    $err2 = pow($err, 2); //menghitung nilai error^2 perbulan
                    array_push($error, $err); //simpan nilai error ke array
                    array_push($error2, $err2); //simpan nilai error^2 ke array
                }
            }
            // $jml_err = array_sum($error2);
            // $rmse = pow(($jml_err / (count($arrsementara) - 1)), 0.5);
        }
        return view('peramalan.index', compact('c', 'dataramal'));
    }
}
