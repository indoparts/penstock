<?php

namespace App\Http\Controllers;

use App\Models\DataPenjualan;
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
        $data = DataPenjualan::latest()->paginate(10);
        return view('penjualan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('penjualan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataPenjualan  $dataPenjualan
     * @return \Illuminate\Http\Response
     */
    public function show(DataPenjualan $dataPenjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataPenjualan  $dataPenjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(DataPenjualan $dataPenjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataPenjualan  $dataPenjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataPenjualan $dataPenjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataPenjualan  $dataPenjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataPenjualan $dataPenjualan)
    {
        //
    }
}
