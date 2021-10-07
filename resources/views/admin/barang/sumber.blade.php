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
            <a href="" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i
                    class="fa fa-plus-circle" aria-hidden="true"></i> Tambah</a>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Tambah Sumber</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="/sumber/insert" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="nama">Nama Sumber</label>
                                    <input type="text" name="nama_sumber" id="nama" class="form-control">
                                    @error('nama_sumber')
                                    <div class="alert alert-danger mt-2">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @error('nama_sumber')
            <div class="alert alert-danger mt-2">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Sumber</th>
                            <th>Nama Sumber</th>
                        </tr>
                    </thead>
                    <tbody id="body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $.ajax({
            type: "GET",
            url: "/sumber-data",
            data: "json",
            dataType: "",
            success: function (response) {
                $.each(response.sumber, function (i,item) { 
                    $("#body").append(`
                        <tr>
                            <td>`+item.id+`</td>
                            <td>`+item.nama_sumber+`</td>
                        </tr>
                    `)
                });
            }
        });
</script>
@endpush