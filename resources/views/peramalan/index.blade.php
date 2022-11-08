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
                <p class="m-0 font-weight-bold"><strong>parameter : a = 0.1</strong></p>
                <p class="m-0 font-weight-bold"><strong>parameter : x = 1 - a = 0.9</strong></p>
            </div>
            <div class="card-body">
                @foreach ($produk as $key => $value)
                    @php
                        $q = DB::select(
                            "SELECT SUM(lembar) AS jumlah, YEAR(tggl_transaksi) as tahun,MONTH(tggl_transaksi) AS bulan FROM data_penjualans WHERE 
                            data_produk_id='" .
                                $value->id .
                                "' GROUP BY YEAR(tggl_transaksi),MONTH(tggl_transaksi)",
                        );
                        $peramln = [];
                        $x1 = [];
                        $x2 = [];
                    @endphp
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="3">{{ $value->nama_produk }}/{{ $value->code }}</th>
                            </tr>
                            <tr>
                                <th>Data</th>
                                <th>Peramalan</th>
                                <th>Calculate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < count($q); $i++)
                                @php
                                    if ($i == 0) {
                                        array_push($peramln, $q[$i]->jumlah);
                                    }
                                    $a = 0.1;
                                    $x = 1 - $a;
                                    array_push($x1, $a * $q[$i]->jumlah);
                                    array_push($x2, $x * $peramln[$i]);
                                    array_push($peramln, round($x1[$i] + $x2[$i]));
                                    $cek = DB::select("SELECT count(data_produk_id) AS totalcount FROM peramalans WHERE data_produk_id='" . $value->id . "' AND YEAR(tahun)=" . date('Y') . '');
                                    if ($cek[0]->totalcount < count($q)) {
                                        DB::table('peramalans')->insert([
                                            'data_produk_id' => $value->id,
                                            'tahun' => date('Y-m-d'),
                                            'penjualan' => $q[$i]->jumlah,
                                            'peramalan' => count($peramln) > 0 ? $peramln[$i + 1] : 0,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]);
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <p><strong>Date :</strong>{{ $q[$i]->tahun }}/{{ $q[$i]->bulan }}</p>
                                        <p><strong>Jumlah :</strong>{{ $q[$i]->jumlah }}</p>
                                    </td>
                                    <td>
                                        @if ($i == 0)
                                            <strong>-</strong>
                                        @else
                                            <strong>{{ count($peramln) > 0 ? $peramln[$i] : 0 }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        <p><strong>F{{ $i + 2 }} = a x i + ( i - a ) F{{ $i + 1 }}</strong>
                                        </p>
                                        <p><strong>= ({{ $a }} x {{ $q[$i]->jumlah }}) + ({{ $x }} x
                                                {{ $peramln[$i] }})</strong></p>
                                        <p><strong>= {{ $x1[$i] }} + {{ $x2[$i] }}</strong></p>
                                        <p><strong>= {{ $peramln[$i + 1] }}</strong></p>
                                    </td>
                                </tr>
                            @endfor
                            <tr>
                                <td colspan="3">
                                    @if ((count($peramln) > 0))
                                        Diketahui untuk peramalan persediaan stok barang <strong>{{ $value->nama_produk }}/{{ $value->code }}</strong> dibulan berikutnya adalah <strong>{{ $peramln[11] }}</strong>.
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('js')
    @parent
@endsection
