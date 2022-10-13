@extends('layouts.app')

@section('title')
    Peramalan Persediaan
@endsection
@section('css')
    @parent
@endsection
@section('sidebar')
    @include('components.sidebar')
    @parent
@endsection
@section('navbar')
    @parent
    @include('components.navbar')
@endsection

@section('content')
<section>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Peramalan</h1>
        <a href="{{ route('perhitungan_export') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Export Excel</a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Penjualan</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah Penjualan</th>
                </tr>
                
                @foreach ($peramalan['penjualan'] as $k =>$v)
                    <tr>
                        <td>{{ $v['produk'] }}</td>
                        <td>{{ $v['penjualan'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</section>
<section>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Peramalan</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah Persediaan</th>
                </tr>
                
                @foreach ($peramalan['peramalan'] as $k =>$v)
                    <tr>
                        <td>{{ $v['produk'] }}</td>
                        <td>{{ $v['pembelian'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</section>
@endsection

@section('js')
    @parent
@endsection
