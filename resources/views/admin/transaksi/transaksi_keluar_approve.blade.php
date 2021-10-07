@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid mt--8">
    <div class="row">
        <div class="col-sm-12">
            <div class="card rounded shadow-lg">
                <div class="card-header">
                    @include('admin.transaksi.partials.transaksi_nav',["active" => "approve"])
                </div>
                <div class="card-body">
                    @if (Session::has("approved"))
                    <div class="alert alert-success" role="alert">
                        <strong>{{ Session::get("approved") }}</strong>
                    </div>
                    @endif
                    <div class="table-responsive">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            <ul>
                                <li>{{ $error }}</li>
                            </ul>
                            @endforeach
                        </div>
                        @endif
                        @if (Session::has("invalid"))
                            <div class="alert alert-danger mt-2" role="alert">
                                <strong>{{ Session::get("invalid") }}</strong>
                            </div>
                        @elseif(Session::has("success"))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ Session::get("success") }}</strong>
                            </div>
                        @elseif(Session::has("not_scan"))
                            <div class="alert alert-danger" role="alert">
                                <strong>{{ Session::get("not_scan") }}</strong>
                            </div>
                        @elseif(Session::has('not_found'))
                            <div class="alert alert-danger" role="alert">
                                <strong>{{ Session::get("not_found") }}</strong>
                            </div>
                        @endif
                        <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Nomor Induk</th>
                                    <th scope="col">Kelas / Jurusan</th>
                                    <th scope="col">Barang</th>
                                    <th scope="col">Waktu Request</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $request->nama_user }}</td>
                                    <td>{{ $request->nomor_induk }}</td>
                                    <td>
                                        @if ($request->siswa!=null)
                                            {{ $request->siswa->kelas->nama_kelas }}
                                        @else
                                            {{ $request->guru->jurusan->nama_jurusan }}
                                        @endif
                                    </td>
                                    <td>
                                        @foreach ($request->detail as $item)
                                        @if ($item->id==$item->pivot->detail_id)
                                        <ul>
                                            <li>{{ $item->nama_barang." 0".$item->kode_barang }}
                                                @if ($item->pivot->status_scan=="scanned")
                                                <span class="text-success">
                                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                </span>
                                                @else
                                                <span>
                                                    <a href="" data-target="#scanbarang{{ $item->pivot->detail_id }}"
                                                        data-toggle="modal">Scan Barang</a>
                                                </span>
                                                @endif
                                            </li>
                                        </ul>
                                        @endif
                                        @endforeach
                                    </td>
                                    @foreach ($request->detail as $data)
                                    <div id="scanbarang{{ $data->pivot->detail_id }}" class="modal fade" role="dialog"
                                        style="display:none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Scan Barcode Barang</h3>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="/approve/scan_barang?id={{ $data->pivot->detail_id }}&request_id={{ $data->pivot->request_id }}"
                                                        method="post" role="form">
                                                        @csrf
                                                        @method("PUT")
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <p><b>Note : Silahkan scan barcode pada barang
                                                                            atau ketikan kode unik yang terdaftar
                                                                            pada sistem pada input di bawah ini
                                                                        </b></p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <input type="text" name="kode_unik"
                                                                        class="form-control" id="input_barang"
                                                                        required><br>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button id="nosave" type="button"
                                                                class="btn btn-danger pull-left"
                                                                data-dismiss="modal">Batal</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <td>{{ $request->tanggal_request." ".$request->waktu_request }}</td>
                                    <td>{{ Str::ucfirst($request->status->keterangan_status) }}</td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="box-tools pull-left">
                                                <a href="" data-target="#scan-modal{{ $request->id }}"
                                                    data-toggle="modal" class=" btn-sm btn btn-success"
                                                    data-backdrop="true">Scan</a>
                                            </div>
                                        </div>
                                    </td>
                                    <div class="modal fade" id="scan-modal{{ $request->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="/approve/changed?id={{ $request->id }}" method="post">
                                                @csrf
                                                @method("PUT")
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="exampleModalLabel">Scan Kartu
                                                            Pelajar</h3>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <p><b>Note : Silahkan scan kartu pelajar atau ketikan
                                                                        Nomor Induk yang terdaftar pada sistem pada
                                                                        input di bawah ini</b></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <input type="text" name="nomor_induk"
                                                                    class="form-control" id="input_barang" required>
                                                            </div>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@push('js')
<script>
    $(document).ready(function() {
$('#example').DataTable({
    "pagingType": "numbers"
});
} );
</script>

@endpush