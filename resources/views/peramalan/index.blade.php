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
                <div class="row">
                    <div class="col-md-6">
                        <p class="m-3 font-weight-bold"><strong>parameter : a = {{ session('parameter_a') }}</strong></p>
                        @if ($message = Session::get('success_setup_param_a'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <form action="{{ url('setup_a') }}" method="post">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control @error('parameter_a') is-invalid @enderror"
                                    placeholder="Setup Parameter a" aria-label="Setup Parameter a"
                                    aria-describedby="button-addon2" name="parameter_a"
                                    value="{{ session('parameter_a') }}">
                                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Setup</button>
                                @error('parameter_a')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <p class="m-3 font-weight-bold"><strong>parameter : x = 1 - a =
                                {{ session('parameter_x') }}</strong></p>
                        @if ($message = Session::get('success_setup_param_x'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <form action="{{ url('setup_x') }}" method="post">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control @error('parameter_x') is-invalid @enderror"
                                    placeholder="Setup Parameter x" aria-label="Setup Parameter x"
                                    aria-describedby="button-addon2" name="parameter_x"
                                    value="{{ session('parameter_x') }}">
                                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Setup</button>
                                @error('parameter_x')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @foreach ($produk as $key => $value)
                    @php
                        $q = DB::select(
                            "SELECT SUM(lembar) AS jumlah, YEAR(tggl_transaksi) as tahun, MONTH(tggl_transaksi) AS bulan FROM data_penjualans WHERE 
                            data_produk_id='" .
                                $value->id .
                                "' AND YEAR(tggl_transaksi)=" .
                                date('Y') .
                                ' GROUP BY YEAR(tggl_transaksi),MONTH(tggl_transaksi)',
                        );
                        $peramln = [];
                        $mad = [];
                        $mse = [];
                        $mape = [];
                        $x1 = [];
                        $x2 = [];
                    @endphp
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="3">{{ $value->nama_produk }}/{{ $value->code }}</th>
                            </tr>
                            <tr>
                                <th width="10%">Data</th>
                                <th width="2%">F</th>
                                <th width="30%">Calculate</th>
                                <th>MAD</th>
                                <th>MSE</th>
                                <th>MAPE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < count($q); $i++)
                                @php
                                    if ($i == 0) {
                                        array_push($peramln, $q[$i]->jumlah);
                                    }
                                    $a = session('parameter_a');
                                    $x = session('parameter_x');
                                    array_push($x1, $a * $q[$i]->jumlah);
                                    array_push($x2, $x * $peramln[$i]);
                                    array_push($peramln, round($x1[$i] + $x2[$i]));
                                    array_push($mad, $q[$i]->jumlah - $peramln[$i]);
                                    array_push($mse, ($q[$i]->jumlah - $peramln[$i])*($q[$i]->jumlah - $peramln[$i]));
                                    array_push($mape, ($q[$i]->jumlah - $peramln[$i]) / $q[$i]->jumlah);
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
                                    switch ((int) $q[$i]->bulan) {
                                        case 1:
                                            $bulan = 'Januari';
                                            break;
                                        case 2:
                                            $bulan = 'Februari';
                                            break;
                                        case 3:
                                            $bulan = 'Maret';
                                            break;
                                        case 4:
                                            $bulan = 'April';
                                            break;
                                        case 5:
                                            $bulan = 'Mei';
                                            break;
                                        case 6:
                                            $bulan = 'Juni';
                                            break;
                                        case 7:
                                            $bulan = 'Juli';
                                            break;
                                        case 8:
                                            $bulan = 'Agustus';
                                            break;
                                        case 9:
                                            $bulan = 'September';
                                            break;
                                        case 10:
                                            $bulan = 'Oktober';
                                            break;
                                        case 11:
                                            $bulan = 'November';
                                            break;
                                        case 12:
                                            $bulan = 'Desember';
                                            break;
                                    
                                        default:
                                            $bulan = '-';
                                            break;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <p><strong>{{ $q[$i]->tahun }}/{{ $bulan }}</strong></p>
                                        <p><strong>Penjualan : </strong><strong class="text-danger">{{ $q[$i]->jumlah }}</strong></p>
                                    </td>
                                    <td>
                                        @if ($i == 0)
                                            <strong>-</strong>
                                        @else
                                            <strong class="text-danger">{{ count($peramln) > 0 ? $peramln[$i] : 0 }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>F{{ $i + 2 }} = a x i + ( i - a )
                                            F{{ $i + 1 }}</strong><br />
                                        <strong>= ({{ $a }} x {{ $q[$i]->jumlah }}) + ({{ $x }}
                                            x
                                            {{ $peramln[$i] }})</strong><br />
                                        <strong>= {{ $x1[$i] }} + {{ $x2[$i] }}</strong><br />
                                        <strong>= </strong><strong class="text-danger">{{ $peramln[$i + 1] }}</strong>
                                    </td>
                                    <td>
                                        <strong>= {{ $q[$i]->jumlah }} - {{ $peramln[$i] }}</strong><br/>
                                        <strong>= </strong><strong class="text-danger">{{ $mad[$i] }}</strong>
                                    </td>
                                    <td>
                                        <strong>= ({{ $q[$i]->jumlah }} - {{ $peramln[$i] }})<sup>2</sup></strong><br/>
                                        <strong>= </strong><strong class="text-danger">{{ $mse[$i] }}</strong>
                                    </td>
                                    <td>
                                        <strong>= ({{ $q[$i]->jumlah }} - {{ $peramln[$i] }}) / {{ $q[$i]->jumlah }}</strong><br/>
                                        <strong>= </strong><strong class="text-danger">{{ $mape[$i] }}</strong>
                                    </td>
                                </tr>
                            @endfor
                            <tr>
                                <td colspan="3">
                                    @if (count($peramln) > 0)
                                        Diketahui untuk peramalan persediaan stok barang
                                        <strong class="text-danger">{{ $value->nama_produk }}/{{ $value->code }}</strong>
                                        dibulan berikutnya
                                        adalah <strong class="text-danger">{{ end($peramln) }}</strong>.
                                        <br/> Rata-rata <strong>MAD = {{ array_sum($mad) }}:{{ count($mad)-1 }}=</strong><strong class="text-danger">{{ array_sum($mad)/(count($mad)-1) }}</strong>
                                        <br/> Rata-rata <strong>MSE = {{ array_sum($mse) }}:{{ count($mad)-1 }}=</strong><strong class="text-danger">{{ array_sum($mse)/(count($mad)-1) }}</strong>
                                        <br/> Rata-rata <strong>MAPE = {{ array_sum($mape) }}:{{ count($mad)-1 }}=</strong><strong class="text-danger">{{ array_sum($mape)/(count($mad)-1) }}</strong>
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
