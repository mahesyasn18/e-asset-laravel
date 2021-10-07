@extends('layouts.app')

@section('content')
@if (Session::has("succed"))
<script>
    swal("Berhasil","Berhasil menambah tahun ajaran","success")
</script>
@endif
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid mt--8">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <a href="/daftar-kelas" class="btn btn-success"><svg width="22px" height="22px" viewBox="0 0 16 16"
                        class="bi bi-arrow-left-short" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
                    </svg>Kembali</a> </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Id Kelas</th>
                            <th>Nama Kelas</th>
                            <th>Jurusan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas as $item)
                        <tr>
                            <td align="center">{{ $item->id }}</td>
                            <td>{{ $item->nama_kelas }}</td>
                            <td>{{ $item->jurusan->nama_jurusan}}</td>
                            <td><a href="/class/student/{{ $item->id }}" class="btn btn-primary">Daftar Siswa</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection