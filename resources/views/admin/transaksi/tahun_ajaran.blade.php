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
            <h3>
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i
                        class="fa fa-plus" aria-hidden="true"></i> Tambah</a>
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-dialog">
                            <form action="/insert/tahun/ajaran" method="post">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Tambah Tahun Ajaran</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name">Tahun Ajaran</label>
                                            <p>Isi tahun ajaran dengan contoh format cont. 2021/2020</p>
                                            <input type="text" name="tahun_ajaran" id="tahun_ajaran"
                                                class="form-control" placeholder="Tahun Ajaran yang akan ditambahan"
                                                required>
                                            @error('tahun_ajaran')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

            </h3>
        </div>
        <div class="card-body">
            @if (Session::has("updated"))
            <div class="alert alert-success mt-2">
                <strong>{{ Session::get("updated") }}</strong>
            </div>
            @elseif (Session::has("success"))
            <div class="alert alert-info mt-2">
                <strong>{{ Session::get("success") }}</strong>
            </div>
            @endif
            @error('tahun_ajaran')
            <div class="alert alert-danger mt-2">
                <strong>{{ $message }}</strong>
            </div>
            @enderror

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tahun as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->tahun_ajaran }}</td>
                            <td class="{{ $item->status=="active" ? "bg-success text-white" : "" }}">
                                {{ Str::ucfirst($item->status) ?? "--" }}</td>
                            <td>
                                @if ($item->status!="active")
                                <form action="/active/{{ $item->id }}" method="post" class="d-inline">
                                    @csrf
                                    @method("PUT")
                                    <button type="submit" class="btn-sm btn btn-success p-2">Activate</button>
                                </form>
                                @endif
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