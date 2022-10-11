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
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">Data Produk</h6>
                    </div>
                    <div class="col">
                        <form action="{{ route('produk.index') }}" method="get">
                            <div class="input-group mb-3">
                                <input type="text" name="cari" class="form-control"
                                    placeholder="Cari berdasarkan nama produk">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-outline-secondary">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if ($message = Session::get('status'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="card-body">
                @if (count($data) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" width="1%">Nama Produk</th>
                                    <th scope="col" width="1%">Ukuran</th>
                                    <th scope="col" width="1%">Harga</th>
                                    <th scope="col" width="1%">Stok</th>
                                    <th scope="col" width="1%">Keterangan</th>
                                    <th scope="col" width="1%">Gambar</th>
                                    <th scope="col" width="1%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $k)
                                    <tr>
                                        <th scope="row">{{ $k->nama_produk }}</th>
                                        <td>{{ $k->ukuran }}</td>
                                        <td>{{ $k->harga }}</td>
                                        <td>{{ $k->stok }}</td>
                                        <td>{{ $k->keterangan }}</td>
                                        <td>
                                            <div class="text-center">
                                                <img src="{{ asset('img/produk/' . $k->img) }}" class="rounded"
                                                    alt="{{ $k->img }}" width="50%">
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ url('produk/' . $k->id . '/edit') }}"
                                                class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm" href="{{ url('produk/' . $k->id) }}"
                                                onclick="event.preventDefault();
                                                 document.getElementById('delete-form').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="delete-form" action="{{ url('produk/' . $k->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex ">
                            {{ $data->links() }}
                        </div>
                    </div>
                @else
                    <p> no data found </p>
                @endif
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')
    @parent
@endsection
