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
            <a href="/akun/guru" class="btn-sm btn btn-dark p-2"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="/guru/update/{{ $user->id }}" method="post">
                @csrf
                @method("PUT")
                <input type="hidden" name="status" value="{{ request()->status }}">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-guru" >
                            <label for="nama">Nama Guru</label>
                            <input type="text" name="nama_guru" id="nama" class="form-control" value="{{ old('nama_guru') ?? $user->name }}">
                            @error('nama_guru')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-guru" style=";">
                            <label for="username">Username Guru</label>
                            <input type="text" name="username_guru" id="username" class="form-control" value="{{ old("username_guru") ?? $user->username }}">
                            @error('username_guru')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-guru" style=";margin-top: -7px;">
                            <label for="nomor">Nomor Induk Pegawai</label>
                            <input type="number" name="nip" id="nomor" class="form-control" value="{{ old("nip") ?? $user->nomor_induk }}">
                            @error('nip')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-guru">
                            <label for="jurusan">Tempat Mengajar Jurusan</label>
                            <select name="jurusan_ajar" id="jurusan" class="form-control">
                                @foreach ($jurusan as $data)
                                    @if ($user->jurusan_id==$data->id)
                                        <option value="{{ $data->id }}" selected>{{ $data->nama_jurusan." (".$data->singkatan.")" }}</option>
                                    @else
                                        <option value="{{ $data->id }}">{{ $data->nama_jurusan." (".$data->singkatan.")" }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('jurusan_ajar')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row form-guru" >
                    <div class="col-sm-12">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" onclick="start_animate()"><span>Update <span id="spinner" style="display:none;"><img src="{{ asset("img/svg/pulse.svg") }}" width="30px"></span></button>
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
        function start_animate(){
            $("#spinner").css("display","inline-block")
        }
    </script>
@endpush