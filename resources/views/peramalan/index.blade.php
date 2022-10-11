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
    <h2> BARANG <?php echo $c['id_produk']; ?></h2>
    <table style="width:50%">
        <tr>
            <th class="text-center" width="100">Periode (Bulan)</th>
            <th class="text-center" width="100">Penjualan</th>
            <th class="text-center" width="100">Peramalan</th>
            <th class="text-center" width="100">Error</th>
            <th class="text-center" width="100">|Error|</th>
            <th class="text-center" width="100">Error^2</th>
            <th class="text-center" width="100">|%Error|</th>

        </tr>
        @php
            $jumlah = 0;
            $jumlah2 = 0;
        @endphp
        @foreach ($dataramal as $key => $value)
        <tr>
            <td>".($value+1)."</td>
            @if($value>=count($arrsementara))
            <td>-</td>
            @else
            <td>{{ $arrsementara[$value] }}</td>
            @endif
            <td>{{ $value }}</td>
            @if($value==0)
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            @elseif($value>count($error))
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            @else
            <td>{{ $error[$value-1] }}</td>
            <td>{{ abs($error[$value-1]) }}</td>
            <td>{{ $error2[$value-1] }}</td>
            {{ $d=(abs($error[$value-1])/$arrsementara[$value])*100 }}
            <td>{{ $d }}%</td>
            @endif
            </tr>
        @if(isset($error2[$value-1]))
        @php
            $jumlah=$jumlah+$error2[$value-1];
        $jumlah2=$jumlah2+$d;
        @endphp
        @endif
        @endforeach
        <tr>
            <td colspan='5'><b>Jumlah</b></td>
            <td>{{ $jumlah }}</td>
            <td>{{ $jumlah2 }}%</td>
            </tr>
        <tr>
            <td colspan='5'><b>Nilai Error<b></td>
            <td>{{ $rmse }}</td>
            <td>{{ $jumlah2/count($error2) }}%</td>
            </tr>
        <tr>
            <td colspan='5'></td>
            <td align='center'><b>RSME</b></td>
            <td align='center'><b>MAPE</b></td>
            </tr>
    </table>
    <br><br>
@endsection

@section('js')
    @parent
@endsection
