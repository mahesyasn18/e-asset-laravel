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
                <div class="btn-group dropleft">
                    <button class="btn-sm btn btn-info dropdown-toggle" style="font-size: 17px" type="button"
                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Kategori
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalkategori">Daftar
                            Kategori</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modaltambah">Tambah
                            Kategori</a>
                    </div>
                </div>
            </div>
            @error('nama_kategori')
            <div class="alert alert-danger mt-2" role="alert">
                <strong>Gagal menambah kategori , silahkan ulangi lagi</strong>
            </div>
            @enderror
            @if (Session::has("success"))
            <div class="alert alert-success mt-2" role="alert">
                <strong>{{ Session::get("success") }}</strong>
            </div>
            @endif
            @if (Session::has("deleted"))
            <div class="alert alert-warning mt-2" role="alert">
                <strong>{{ Session::get("deleted") }}</strong>
            </div>
            @endif
        </div>
        <form action="/asset/insert" method="post">
            @csrf
            <div class="card-body">
                @if (Session::has("invalid_file"))
                <div class="alert alert-danger" role="alert">
                    <strong>{{ Session::get('invalid_file') }}</strong>
                </div>
                @elseif(Session::has("invalid_kategori"))
                <div class="alert alert-danger" role="alert">
                    <strong>{{ Session::get("invalid_kategori") }}</strong>
                </div>
                @elseif(Session::has("empty"))
                <div class="alert alert-danger" role="alert">
                    <strong>{{ Session::get("empty") }}</strong>
                </div>
                @elseif(Session::has("invalid_category"))
                <div class="alert alert-danger" role="alert">
                    <strong>{{ Session::get("invalid_category") }}</strong>
                </div>
                @endif
                <input type="hidden" name="id_admin" value="{{ Auth::guard('admin')->user()->id }}">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                                value="{{ old("nama_barang") ?? "" }}">
                            @error('nama_barang')
                            <p class="text-danger mb-0">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="category">Kategori</label>
                        <select name="category" id="category" class="form-control">
                            <option disabled selected>--Pilih--</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
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
                        <input type="number" name="stok" id="stok" class="form-control" value="{{ old("stok") ?? "" }}">
                        @error('stok')
                        <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 col-sm-6">
                        <label for="sumber">Sumber Barang</label>
                        <select name="sumber" id="sumber" class="form-control">
                            <option disabled selected>--Pilih--</option>
                            @foreach ($sumber as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_sumber }}</option>
                            @endforeach
                        </select>
                        @error('sumber')
                        <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="penyimpanan">Penyimpanan Barang</label>
                        <input type="text" name="penyimpanan" id="penyimpanan" class="form-control"
                            value="{{ old("penyimpanan") ?? "" }}">
                        @error('penyimpanan')
                        <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control"
                            value="{{ old("tanggal_masuk") ?? "" }}">
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
                            <input type="number" class="form-control" name="harga_satuan"
                                aria-describedby="basic-addon1" value="{{ old("harga_satuan") }}">
                        </div>
                        @error('harga_satuan')
                        <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div>
                    <button type="submit" class="btn btn-primary" onclick="start_amimate()">Save
                        <span id="spinner" style="display:none;">
                            <img src="{{ asset("img/svg/pulse.svg") }}" width="30px">
                        </span>
                    </button>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span><i class="far fa-file-excel"></i></span>
                            Excel
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalupload">Upload
                                Massal (Excel)</a>
                            <a class="dropdown-item"
                                href="{{ asset("download/Template For Create Item(Barang).xlsx") }}">Download Template
                                (Excel)</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modaltambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/category/insert" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Tambah Kategori</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="name" class="form-control"
                            placeholder="Nama kategori yang akan di tambahkan" required>
                        @error('nama_kategori')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalupload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/asset/create/excel" method="post" enctype="multipart/form-data">
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
                    <input type="file" name="excel" id="excel" required>
                    <h3 class="mt-2 mb-0">Note : </h3>
                    <h3>Petunjuk input data dengan excel</h3>
                    <ol>
                        <li>Kolom data harus sesuai yang tertera pada file excel , <br>silahkan download template excel
                            terlebih dahulu</li>
                        <li>Kolom kategori harus berupa id pada kategori , <a href="#" data-toggle="modal"
                                data-target="#modalkategori">Klik <br> disini untuk melihat daftar kategori</a></li>
                        <li>Format tanggal pada kolom tanggal masuk<br>(dd/mm/yyy)</li>
                        <li>Kolom harga satuan harus berupa angka tanpa koma dan titik , contoh : 20000</li>
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
<div class="modal fade" id="modalkategori" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="" id="exampleModalLabel">Kategori Barang</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Kategori</th>
                            <th>Kategori</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td scope="row">{{ $category->id }}</td>
                            <td>{{ $category->nama_kategori }}</td>
                            <td>
                                <form action="/category/delete/{{ $category->id }}" method="post">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="btn-sm btn btn-danger"
                                        onclick="return confirm('Yakin mau menghapus kategori?')"><i class="fa fa-trash"
                                            aria-hidden="true"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
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