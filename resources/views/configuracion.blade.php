@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('configuracion.grabar') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $configuracion->id }}">
        <div class="row justify-content-md-center">
            @if (session()->has('message'))
            <div class="col-12 col-md-8">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            <div class="col-md-8">
                <div class="form-group row">
                    <label for="login_interface" class="col-sm-4 col-form-label">Login interface</label>
                    <div class="col-sm-8">
                        <input type="url" class="form-control" name="login_interface" id="login_interface" value="{{ $configuracion->login_interface }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="usr" class="col-sm-4 col-form-label">ID, user name from Kirisun</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="usr" id="usr" value="{{ $configuracion->usr }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pwd" class="col-sm-4 col-form-label">Password</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="pwd" id="pwd" value="{{ $configuracion->pwd }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="url" class="col-sm-4 col-form-label">HTTPS URL customer use to</label>
                    <div class="col-sm-8">
                        <input type="url" class="form-control" name="url" id="url" value="{{ $configuracion->url }}" required>
                    </div>
                </div>
                <hr>
                <div class="form-group row bg-danger py-3 text-white">
                    <label for="password" class="col-sm-4 col-form-label">User Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password" id="password" required min="5">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12 text-right">
                        <button type="submit" class="btn btn-danger">Grabar</button>
                        &nbsp;
                        <a href="{{route('index')}}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
