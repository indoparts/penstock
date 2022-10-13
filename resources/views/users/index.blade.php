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
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-error">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">Data Users</h6>
                    </div>
                    <div class="col">
                        <form action="{{ route('users.index') }}" method="get">
                            <div class="input-group mb-3">
                                <input type="text" name="cari" class="form-control"
                                    placeholder="Cari berdasarkan nama">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-outline-secondary">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (count($data) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" width="15%">Nama</th>
                                    <th scope="col" width="15%">Email</th>
                                    <th scope="col" width="10%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $k)
                                    <tr>
                                        <td>{{ $k->name }}</td>
                                        <td>{{ $k->email }}</td>
                                        <td>
                                            <a href="{{ url('users/' . $k->id . '/edit') }}"
                                                class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm"
                                                onclick="event.preventDefault();
                                                 document.getElementById('delete-form').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="delete-form" action="{{ url('users/' . $k->id) }}" method="POST"
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
