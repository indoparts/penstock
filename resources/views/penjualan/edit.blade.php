@extends('layouts.app')
@section('title')
Update
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
                <form action="{{ url('penjualan/' . $data->id) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputAddress">Produk</label>
                            <select class="form-control @error('data_produk_id') is-invalid @enderror"
                                name="data_produk_id" required>
                                @foreach ($produk as $k => $p)
                                <option value="{{ $p->id }}" {{ $data->data_produk_id === $p->id ? 'selected' : '' }}>{{ $p->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputAddress2">Qty</label>
                            <input type="number" class="form-control @error('lembar') is-invalid @enderror" name="lembar"
                                required value="{{ $data->lembar }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputAddress2">Tanggal</label>
                            <input type="date" class="form-control @error('tggl_transaksi') is-invalid @enderror" name="tggl_transaksi" value="{{ $data->tggl_transaksi }}"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress2">Keterangan</label>
                        <textarea name="ket" id="" cols="30" rows="10"
                            class="form-control @error('ket') is-invalid @enderror">{{ $data->ket }}</textarea>
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
