@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card mt--8 mb-5 shadow-lg">
        <div class="card-header">
            <a href="/akun/siswa" class="btn-sm btn btn-dark p-2"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="/siswa/update/{{ $user->user_id }}" method="POST">
                @csrf
                @method("PUT")
                <input type="hidden" name="status" value="{{ request()->status }}">
                <div class="row">
                    <div class="col-12 col-sm-12 form-siswa">
                        <label for=" category">Nama User</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old("nama") ?? $user->user->name }}">
                        @error('nama')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="form-group form-siswa">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="{{ old("username") ?? $user->user->username }}">
                            @error('username')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-siswa">
                            <label for="nomor">Nomor Induk Siswa</label>
                            <input type="number" name="nis" id="nomor" class="form-control" value="{{ old("nis") ?? $user->user->nomor_induk }}">
                            @error('nis')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row form-siswa">
                    <div class="col-sm-12">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" onclick="animate_spinner()"><span>Update <span id="spinner" style="display:none;"><img src="{{ asset("img/svg/pulse.svg") }}" width="30px"></span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        function animate_spinner(){
            $("#spinner").css("display","inline-block")
        }
    </script>
@endpush