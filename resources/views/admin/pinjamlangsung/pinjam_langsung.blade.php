@extends('layouts.app')

@section('content')
@if (Session::has("added"))
<script>
    swal("Berhasil","Berhasil menambah barang ke keranjang","success");
</script>
@elseif(Session::has("deleted"))
<script>
    swal('Berhasil',"Berhasil menghapus barang dari keranjang","success");
</script>
@elseif(Session::has("get_user"))
<script>
    swal("Berhasil","Berhasil mendapat data user!","success")
</script>
@elseif(Session::has("failed"))
<script>
    swal("Gagal","Nomor induk user tidak terdaftar!","error")
</script>
@elseif(Session::has("notfound"))
<script>
    swal("Gagal","Error! , tidak bisa meminjam barang ini","error")
</script>
@elseif(Session::has("rusak"))
<script>
    swal("Gagal","Error! , tidak bisa meminjam barang ini","error")
</script>
@elseif(Session::has("diipinjam"))
<script>
    swal("Gagal","Error! , tidak bisa meminjam barang ini","error")
</script>
@endif
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card mt--8">
        <div class="card-header">
            <h3>Scan Barang</h3>
        </div>
        <div class="card-body">
            @if (Session::has("dipinjam"))
            <div class="alert alert-danger" role="alert">
                <strong>{{ Session::get("dipinjam") }}</strong>
            </div>
            @elseif (Session::has("rusak"))
            <div class="alert alert-danger" role="alert">
                <strong>{{ Session::get("rusak") }}</strong>
            </div>
            @endif
            <form action="/request/langsung" method="POST">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="formScan" name="kode_unik"
                                placeholder="Silahkan scan barang atau tulis kode unik barang" autofocus>
                        </div>
                    </div>
                    <button type="submit" class="btn-sm btn btn-primary mt-3">Submit</button>
                </div>
            </form>
            <div class="row">
                <div class="col-sm-12">
                    @if (count($cart)>0)
                    <table class="table table-bordered table-stripped" id="table-content" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama & Kode Barang</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $c)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $c->name." 0".$c->associatedModel->kode_barang }}</td>
                                <td>{{ $c->associatedModel->kode_unik }}</td>
                                <td>
                                    <form action="/cart/delete/{{ $c->id }}" method="post">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn-sm btn btn-danger p-2">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <h3>Scan Kartu Pelajar</h3>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <form action="/scan/kartu_pelajar" method="POST">
                                @csrf
                                <input type="text" class="form-control" id="formScan" name="scan_kartu"
                                    placeholder="Silahkan scan kartu pelajar atau nulis nomor induk siswa pada field ini">
                                @error('scan_kartu')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="btn-sm btn btn-success mt-3">Scan</button>
                            </form>
                        </div>
                    </div>
                    @if ($user)
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <div class="card shadow">
                                <div class="card-header p-3" style="background-color: #172b4d">
                                    <h3 class="text-primary">Data User</h3>
                                </div>
                                <div class="card-body">
                                    <p>Nama : {{ $user["nama"] }}</p>
                                    <p>Username : {{ $user["username"] }}</p>
                                    <p>Nomor Induk : {{ $user["nomor_induk"] }}</p>
                                </div>
                                <div class="card-footer">
                                    <form action="/request/create" method="post">
                                        @csrf
                                        <button type="submit" class="btn-sm btn btn-primary">Process</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection