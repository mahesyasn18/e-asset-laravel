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
                    @include('admin.transaksi.partials.transaksi_nav',["active" => "pending"])
                </div>
                <div class="card-body">
                    <div class="table-responsive">
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
                                            @if ($item->id == $item->pivot->detail_id)
                                            <ul>
                                                <li>{{ $item->nama_barang." 0".$item->kode_barang }}</li>
                                            </ul>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $request->tanggal_request." ".$request->waktu_request }}</td>
                                    <td>{{ Str::ucfirst($request->status->keterangan_status) }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <form action="/pending/changed?status=2&id={{ $request->id }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    @method("PUT")
                                                    <button type="submit" class="btn-sm btn btn-primary p-2"
                                                        onclick="start_animate({{ $request->id }})">Approve <span
                                                            style="display: none" id="pulse{{ $request->id }}"><img
                                                                src="{{ asset("img/svg/pulse.svg") }}" alt=""
                                                                width="20px" class="m-0"></span></button>
                                                </form>
                                                <a href="" data-target="#modalrefuse{{ $request->id }}"
                                                    data-toggle="modal" class="btn btn-sm btn-danger p-2">Refuse</a>
                                            </div>
                                        </div>
                                    </td>
                                    <div class="modal fade" id="modalrefuse{{ $request->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Masukkan keterangan
                                                        alasan penolakan?</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Nama Peminjam : {{ $request->nama_user }}<br>
                                                    Nomor Induk : {{ $request->nomor_induk }}
                                                    <form action="/pending-refuse/changed?id={{ $request->id }}"
                                                        method="post">
                                                        @csrf
                                                        @method("PUT")
                                                        <div class="form-group">
                                                            <label for="masukkanPesan">Keterangan</label>
                                                            <textarea class="form-control"
                                                                placeholder="Masukkan pesan Anda" name="keterangan"
                                                                style="resize: none" required></textarea>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger">Refuse</button>
                                                </div>
                                                </form>
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

@endpush
@push('js')
<script>
    function start_animate(id){
            $("#pulse"+id).css("display","inline-block")
        }
</script>
@endpush