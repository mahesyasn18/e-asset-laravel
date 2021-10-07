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
            <h3>DAFTAR JURUSAN</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Id Jurusan</th>
                        <th>Nama Jurusan</th>
                        <th>Singkatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jurusan as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nama_jurusan }}</td>
                        <td>{{ $item->singkatan}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection