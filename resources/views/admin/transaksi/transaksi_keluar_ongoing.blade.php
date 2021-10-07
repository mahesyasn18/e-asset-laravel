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
                    @include('admin.transaksi.partials.transaksi_nav',["active" => "ongoing"])
                </div>
                <div class="card-body">
                    @if (Session::has('scan_completed'))
                        <div class="alert alert-success" role="alert">
                            <strong>{{ Session::get("scan_completed") }}</strong>
                        </div>
                    @elseif(Session::has("langsung"))
                        <div class="alert alert-success" role="alert">
                            <strong>{{ Session::get("langsung") }}</strong>
                        </div>
                    @elseif(Session::has("wrong"))
                        <div class="alert alert-danger" role="alert">
                            <strong>{{ Session::get("wrong") }}</strong>
                        </div>
                    @elseif(Session::has("not_scanned"))
                        <div class="alert alert-danger" role="alert">
                            <strong>{{ Session::get("not_scanned") }}</strong>
                        </div>
                    @elseif(Session::has("barang_scanned"))
                        <div class="alert alert-success" role="alert">
                            <strong>{{ Session::get("barang_scanned") }}</strong>
                        </div>
                    @elseif(Session::has("invalid"))
                        <div class="alert alert-danger" role="alert">
                            <strong>{{ Session::get("invalid") }}</strong>
                        </div>
                    @endif
                    <div class="alert alert-success" id="alert-refresh" style="display: none">
                        Invoice berhasil di buat , silahkan refresh halaman ini . <strong><a href="" class="text-white">Click Here</a></strong>
                    </div>
                    <div class="table-responsive">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $error)
                                    <strong>{{ $error }}</strong>
                                @endforeach
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
                                                    @if ($item->pivot->status_scan=="scan_kembali")
                                                    <span class="text-success">
                                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                    </span>
                                                    @else
                                                        @if ($request->status_invoice=="printed")
                                                            <span>
                                                                <a href="" data-target="#scanbarang{{ $item->pivot->detail_id }}"
                                                                    data-toggle="modal">Scan Barang</a>
                                                            </span>
                                                        @endif
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
                                                        action="/ongoing/scan_barang?id={{ $data->pivot->detail_id }}&request_id={{ $data->pivot->request_id }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <p><b>Note : Silahkan scan barcode pada barang
                                                                    atau ketikan kode unik yang terdaftar
                                                                    pada sistem pada input di bawah ini
                                                                </b></p>
                                                            <div class="row">
                                                                <div class="col-lg-11">
                                                                    <input type="text" name="kode_unik"
                                                                        class="form-control" id="input_barang"><br>
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
                                    <td>{{ $request->status->keterangan_status }}</td>
                                    <td>
                                        @if ($request->status_invoice=="printed")
                                            <a href="" data-target="#scan-modal{{ $request->id }}" data-toggle="modal"
                                            class=" btn-sm btn btn-success" data-backdrop="true">Scan Kembalikan</a>
                                            @endif
                                        <a href="/invoice-print/{{ $request->id }}" class="btn-sm btn btn-primary"
                                        onclick="show_alert({{ $request->id }})">Print Invoice</a>

                                    </td>
                                    <div id="scan-modal{{ $request->id }}" class="modal fade" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Scan Kartu Pelajar</h3>
                                                </div>
                                                <div class="modal-body" style="word-wrap: break-word;">
                                                    <p><b>Note : Silahkan scan kartu pelajar atau ketikan
                                                            Nomor Induk yang terdaftar pada sistem pada
                                                            input di bawah ini</b></p>
                                                    <form
                                                        action="/ongoing/changed"
                                                        method="post" role="form">
                                                        @csrf
                                                        @method("PUT")
                                                        <div class="form-group">

                                                            <div class="row">
                                                                <div class="col-lg-11">
                                                                    <input type="text" name="kode_invoice"
                                                                        class="form-control" id="input_barang"><br>
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
<script>
    $(document).ready(function() {
      $(".modal").on("shown.bs.modal", function() {
        $("#input_barang").focus();
        $("#nomor").focus();
      });
    });
</script>

@endpush

@push('js')
    <script>
        function show_alert(){
            $("#alert-refresh").css("display","block")
        }
    </script>
@endpush