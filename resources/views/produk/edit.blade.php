@extends('layouts.app')
@section('title')
update
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

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form ubah data produk</h6>
            </div>
            @if ($message = Session::get('status'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="card-body">
                <form action="{{ url('produk/' . $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Nama Produk</label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                name="nama_produk" required value="{{ $data->nama_produk }}">
                            @error('nama_produk')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Ukuran</label>
                            <input type="text" class="form-control @error('ukuran') is-invalid @enderror" name="ukuran"
                                required value="{{ $data->ukuran }}">
                            @error('ukuran')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputAddress">Harga</label>
                            <input type="text" class="form-control @error('harga') is-invalid @enderror"
                                placeholder="Rp." name="harga" required value="{{ $data->harga }}">
                            @error('harga')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Stok</label>
                            <input type="number" class="form-control @error('stok') is-invalid @enderror" name="stok"
                                required value="{{ $data->stok }}">
                            @error('stok')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputAddress2">Gambar</label>
                            <input type="file" class="form-control @error('img') is-invalid @enderror" name="img"
                                required>
                            @error('img')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <img src="{{ asset('img/produk/' . $data->img) }}" class="rounded" alt="{{ $data->img }}"
                                width="50%">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress2">Keterangan</label>
                        <textarea name="keterangan" id="" cols="30" rows="10"
                            class="form-control @error('keterangan') is-invalid @enderror">{{ $data->keterangan }}</textarea>
                        @error('keterangan')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')
    @parent
@endsection
