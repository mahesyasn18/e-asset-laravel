@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card mt--8">
        <div class="card-header">
            <a href="" class="btn btn-success mt-2" data-toggle="modal" data-target="#modalupload">
                <span><i class="far fa-file-excel"></i></span>
                Upload With Ecxel
            </a>
            <div class="modal fade" id="modalupload" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="/guru/create/excel" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Upload File Excel</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label for="excel">Upload here !</label><br>
                                <input type="file" name="excel_guru" id="excel">
                                <h3 class="mt-2 mb-0">Note : </h3>
                                <h3>Petunjuk input data dengan excel</h3>
                                <ol>
                                    <li>Kolom data harus sesuai yang tertera pada file excel , <br>silahkan download
                                        template excel terlebih dahulu</li>
                                    <li>Kolom jurusan harus berupa id pada jurusan , <a href="/daftar/jurusan">Klik <br>
                                            disini untuk melihat daftar jurusan</a></li>
                                    <li>Semua kolom wajib diisi!</li>
                                </ol>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="btn-group dropright mt-2">
                <a href="{{ asset("download/Template For User Account (Teacher).xlsx") }}"
                    class="btn btn-success"><span><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download
                        template Excel</span></a>
            </div>
            @error('excel_guru')
            <div class="alert alert-danger mt-2">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            @if (Session::has('error'))
            <div class="alert alert-danger mt-2" role="alert">
                <strong>{{ Session::get("error") }}</strong>
            </div>
            @elseif(Session::has('invalid_idjurusan'))
            <div class="alert alert-danger mt-2" role="alert">
                <strong>{{ Session::get("invalid_idjurusan") }}</strong>
            </div>
            @elseif(Session::has('empty'))
            <div class="alert alert-danger mt-2" role="alert">
                <strong>{{ Session::get('empty') }}</strong>
            </div>
            @elseif(Session::has('username'))
            <div class="alert alert-danger mt-2" role="alert">
                <strong>{{ Session::get('username') }}</strong>
            </div>
            @endif
        </div>
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Data User</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="/guru/insert" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-guru">
                            <label for="nama">Nama Guru</label>
                            <input type="text" name="nama_guru" id="nama" class="form-control">
                            @error('nama_guru')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group form-guru">
                            <label for="username">Username Guru</label>
                            <input type="text" name="username_guru" id="username" class="form-control">
                            @error('username_guru')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group mb-0 form-guru">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                            @error('password')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                        <div class="form-group mb-0 form-guru" style="margin-top:7px;display: none;">
                            <input type="checkbox" name="show-password" id="show-password">
                            <label for="show-password" id="label">Show Password</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group form-guru" style="margin-top: -7px;">
                            <label for="nomor">Nomor Induk Pegawai</label>
                            <input type="number" name="nip" id="nomor" class="form-control">
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
                                <option disabled selected>--Pilih Salah Satu--</option>
                                @foreach ($jurusan as $data)
                                <option value="{{ $data->id }}">{{ $data->nama_jurusan." (".$data->singkatan.")" }}
                                </option>
                                @endforeach
                            </select>
                            @error('jurusan_ajar')
                            <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row form-guru">
                    <div class="col-sm-12">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" onclick="button_animate()"><span>Save <span
                                        id="spinner" style="display:none;"><img src="{{ asset("img/svg/pulse.svg") }}"
                                            width="30px"></span></button>
                            <a href="/akun/guru" class="btn btn-success float-right d-flex align-items-center">
                                <svg width="25px" height="25px" viewBox="0 0 16 16" class="bi bi-arrow-left-short"
                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
                                </svg>
                                Kembali
                            </a>
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
    function button_animate(){
            $("#spinner").css("display","inline-block");
        }
</script>
@endpush