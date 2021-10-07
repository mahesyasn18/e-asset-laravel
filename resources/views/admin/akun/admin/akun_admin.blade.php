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
            <div class="d-flex justify-content-between align-items-center">
                <h3>Data Admin</h3>
            </div>
        </div>
        <form action="/admin/update/{{ $admin->id }}" method="post">
            @csrf
            @method("PUT")
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old("nama") ?? $admin->name }}">
                            @error('nama')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group mb-0">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control"
                                value="{{ old("username") ?? $admin->username }}">
                            @error('username')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="form-group form-guru">
                            <label for="jurusan">Tempat Mengajar Jurusan</label>
                            <select name="jurusan" id="jurusan" class="form-control">
                                @foreach ($jurusan as $data)
                                @if ($admin->jurusan_id==$data->id)
                                <option value="{{ $data->id }}" selected>
                                    {{ $data->nama_jurusan." (".Str::upper($data->singkatan).")" }}</option>
                                @else
                                <option value="{{ $data->id }}">
                                    {{ $data->nama_jurusan." (".Str::upper($data->singkatan).")" }}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('jurusan')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" readonly>
                                <option value="admin">Admin</option>
                            </select>
                            @error('status')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>


            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" onclick="button_animate()"><span>Save <span
                                        id="spinner" style="display:none;"><img src="{{ asset("img/svg/pulse.svg") }}"
                                            width="30px"></span></button>
                            <a href="/akun/admin" class="btn btn-success float-right">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-short"
                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection