@extends('layouts.app')
@section('title')
Index
@endsection
@section('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
    <div class="container-fluid">
        @if ($message = Session::get('status'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Penjualan</h6>
            </div>
            <div class="card-body">
                @if (count($data) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" width="15%">Nama Produk</th>
                                    <th scope="col" width="15%">Tgl. Transaksi</th>
                                    <th scope="col">QTY</th>
                                    <th scope="col">Ket</th>
                                    <th scope="col" width="20%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $k)
                                    <tr>
                                        <th>{{ $k->produk->nama_produk }}</th>
                                        <th>{{ $k->tggl_transaksi }}</th>
                                        <td>{{ $k->lembar }}</td>
                                        <td>{{ $k->ket }}</td>
                                        <td>
                                            <a href="{{ url('penjualan/' . $k->id . '/edit') }}"
                                                class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm"
                                                onclick="event.preventDefault();
                                                 document.getElementById('delete-form').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="delete-form" action="{{ url('penjualan/' . $k->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex">
                            {{ $data->links() }}
                        </div>
                    </div>
                @else
                    <p> no data found </p>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('js')
    @parent
@endsection
