@extends('layouts.app')
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
        @if ($message = Session::get('success'))
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
                                    <th scope="col" width="1%">#</th>
                                    <th scope="col" width="15%">Tgl. Transaksi</th>
                                    <th scope="col">Lembar</th>
                                    <th scope="col" width="10%">Ket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $k)
                                    <tr>
                                        <th scope="row">{{ $k->id }}</th>
                                        <td>{{ $k->tgl_transaksi }}</td>
                                        <td>{{ $k->lembar }}</td>
                                        <td>{{ $k->ket }}</td>
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
    <!-- /.container-fluid -->
@endsection

@section('js')
    @parent
@endsection
