@extends('users.user_layout')

@section('content')
@if (Session::has("requested"))
    <script>
        swal("Berhasil","Berhasil memproses request","success")
    </script>
@endif
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
                    <h2>
                        Riwayat Transaksi
                    </h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peminjam</th>
                                    <th>Nomor Induk</th>
                                    <th>Nama Barang</th>
                                    <th>Waktu Request</th>
                                    <th>Status</th>
                                    <th>Keterangan Penolakan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->nama_user }}</td>
                                        <td>{{ $request->nomor_induk }}</td>
                                        <td>
                                        @foreach ($request->detail as $item)
                                            @if ($item->id==$item->pivot->detail_id)
                                                    <ul>
                                                        <li>{{ $item->nama_barang." 0".$item->kode_barang }}</li>
                                                    </ul>
                                            @endif
                                        @endforeach
                                        </td>
                                        <td>{{ $request->tanggal_request." ".$request->waktu_request }}</td>
                                        <td>{{ Str::ucfirst($request->status->keterangan_status) }}</td>
                                        <td>{{ $request->keterangan ?? "--" }}</td>
                                        <td>
                                            @if ($request->status_id==3)
                                                <a href="/invoice-print/{{ $request->id }}" class="btn btn-sm btn-primary p-2">Invoice</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-header" align="right">
                        <a href="/index" class="btn btn-success">
                            Kembali
                        </a>
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
    });
</script>

@endpush