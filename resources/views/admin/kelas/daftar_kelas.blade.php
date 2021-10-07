@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid mt--8">
    <div class="card">
        <div class="card-header">
            <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalkelas"><i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Kelas</a>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Session::has("kelas"))
                <div class="alert alert-success">
                    <strong>{{ Session::get("kelas") }}</strong>
                </div>
            @endif
            <div class="modal fade" id="modalkelas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="/kelas/create" method="post">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Tambah Kelas</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Tingkat</label>
                                    <select name="tingkat" id="tingkat" class="form-control">
                                        <option disabled selected>-- Pilih Tingkat --</option>
                                        <option value="X">X</option>
                                        <option value="XI">XI</option>
                                        <option value="XII">XII</option>
                                        <option value="XIII">XIII</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Kelas</label>
                                    <select name="kelas" id="kelas" class="form-control">
                                        <option disabled selected>-- Pilih Kelas --</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Jurusan</label>
                                    <select name="jurusan" id="jurusan" class="form-control">
                                        <option disabled selected>-- Pilih Jurusan --</option>
                                        @foreach ($jurusan as $data)
                                            <option value="{{ $data->id }}">{{ $data->nama_jurusan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($jurusan as $item)
                <div class="col-sm-3">
                    <div class="card mt-3" style="width: 13rem;">
                        <img src="{{ asset('img/jurusan/'.$item->image) }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h4 class="card-title">{{$item->nama_jurusan}}</h4>
                            <a href="/class/{{$item->id}}" class="btn btn-primary">Lihat Kelas</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection