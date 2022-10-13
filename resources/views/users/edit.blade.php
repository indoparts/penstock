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
                <h6 class="m-0 font-weight-bold text-primary">Form ubah data users</h6>
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="card-body">
                <form action="{{ url('users/' . $data->id) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputAddress2">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                required value="{{ $data->name }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputAddress2">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $data->email }}"
                                required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputAddress3">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ $data->password }}"
                                required>
                        </div>
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
