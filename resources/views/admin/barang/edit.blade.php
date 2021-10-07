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
                <h3>Data Barang</h3>
            </div>
            @if (Session::has("success"))
                <div class="alert alert-success mt-2" role="alert">
                    <strong>{{ Session::get("success") }}</strong>
                </div>
            @endif
            @if (Session::has("deleted"))
                <div class="alert alert-warning mt-2" role="alert">
                    <strong>{{ Session::get("deleted") }}</strong>
                </div>
            @elseif(Session::has("failed"))
                <div class="alert alert-warning mt-2" role="alert">
                    <strong>{{ Session::get("failed") }}</strong>
                </div>
            @endif
        </div>
        <form action="/asset/update/{{ $barang->id }}" method="post">
            @csrf
            @method("PUT")
            <div class="card-body">
                <input type="hidden" name="id_admin" value="{{ Auth::guard('admin')->user()->id }}">
                <div class="row">
                    <div class="col-6 col-sm-6">
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="{{ $barang->nama_barang }}">
                            @error('nama_barang')
                                <p class="text-danger mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6 col-sm-6">
                        <label for="category">Kategori</label>
                        <select name="category" id="category" class="form-control">
                            @foreach ($categories as $category)
                                @if ($barang->category_id==$category->id)
                                    <option value="{{ $category->id }}" selected>{{ $category->nama_kategori }}</option>
                                @else
                                <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" id="stok" class="form-control" value="{{ $barang->stok }}">
                        @error('stok')
                            <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6 col-sm-6">
                        <label for="sumber">Sumber Barang</label>
                        <select name="sumber" id="sumber" class="form-control">
                            @foreach ($sumber as $item)
                                @if ($barang->sumber_id==$item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->nama_sumber }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->nama_sumber }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('sumber')
                            <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-6 col-sm-6">
                        <label for="penyimpanan">Penyimapanan Barang</label>
                        <input type="text" name="penyimpanan" id="penyimpanan" class="form-control" value="{{ $barang->penyimpanan }}">
                        @error('penyimpanan')
                            <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="{{ date("Y-m-d",strtotime($barang->created_at)) }}">
                        @error('tanggal_masuk')
                            <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <label for="harga_satuan">Harga Satuan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Rp . </span>
                            </div>
                            <input type="number" class="form-control" name="harga_satuan" aria-describedby="basic-addon1" value="{{ $barang->harga_satuan }}">
                        </div>
                        @error('harga_satuan')
                            <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" onclick="start_amimate()">Update <span id="spinner" style="display:none;"><img src="{{ asset("img/svg/pulse.svg") }}" width="30px"></span></button>
                    <a href="/dashboard" class="btn btn-success"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    <script>
        function start_amimate(){
            $("#spinner").css("display","inline-block");
        }
    </script>
@endpush