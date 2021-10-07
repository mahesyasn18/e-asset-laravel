@extends('layouts.app')

@section('content')
@if (Session::has("success"))
<script>
    swal("Berhasil", "Berhasil menambah barang", "success");
</script>
@elseif(Session::has("deleted"))
<script>
    swal("Berhasil","Berhasil menghapus barang","warning");
</script>
@elseif(Session::has("updated"))
<script>
    swal("Berhasil","Berhasil mengupdate barang","success")
</script>
@endif
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0 p-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Assets</h5>
                                    <span class="h2 font-weight-bold mb-0" id="asset">350,897</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0 p-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Users</h5>
                                    <span class="h2 font-weight-bold mb-0" id="user">2,356</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0 p-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Admin</h5>
                                    <span class="h2 font-weight-bold mb-0" id="admin">924</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0 p-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Jurusan</h5>
                                    <span class="h2 font-weight-bold mb-0">9</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="ni ni-building" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="container-fluid mb-3">
    <div class="card mt--7">
        <div class="card-header">
            <a href="/eksport/excel" class="btn btn-success">
                <span><i class="far fa-file-excel"></i></span>
                Excel
            </a>
            <a href="/eksport/pdf" class="btn btn-danger">
                <span><i class="fas fa-file-pdf"></i></span>
                PDF
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Category</th>
                            <th>Input By</th>
                            <th>Stok</th>
                            <th>Input Date</th>
                            <th>Action</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barang as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->category->nama_kategori }}</td>
                            <td>{{ $item->admin->name }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td class="text-center">
                                <a href="/asset/edit/{{ $item->id }}" class="btn-sm btn btn-primary"><i
                                        class="fas fa-pen"></i></a>
                                <a href="/asset/show/{{ $item->id }}" class="btn-sm btn btn-warning"><i
                                        class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="" class="btn-sm btn btn-danger" data-toggle="modal"
                                    data-target="#modalhapus{{ $item->id }}"><i class="fa fa-trash"
                                        aria-hidden="true"></i></a>
                                <div class="modal fade" id="modalhapus{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="/asset/delete/{{ $item->id }}" method="post">
                                            @csrf
                                            @method("DELETE")
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="exampleModalLabel">Yakin mau menghapus?
                                                    </h3>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-0">Nama Barang : {{ $item->nama_barang }}</p>
                                                    <p>Kategori : {{ $item->category->nama_kategori }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td><a href="" class="btn-sm btn btn-info" data-toggle="modal"
                                    data-target="#modallainnya{{ $item->id }}">Lainnya</a></td>
                        </tr>
                        <div class="modal fade" id="modallainnya{{ $item->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Keterangan Lainnya</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">Sumber : {{ $item->sumber->nama_sumber }}</p>
                                        <p class="mb-0">Penyimpanan : {{ $item->penyimpanan }}</p>
                                        <p class="mb-0">Sub Total : {{ "Rp . ".number_format($item->harga_satuan) }}</p>
                                        <p class="mb-0">Total : {{ "Rp . ".number_format($item->total) }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>

@endpush

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
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "/data/dashboard",
            data: "",
            dataType: "json",
            success: function (response) {
                $("#asset").html(response.detail);
                $("#user").html(response.users);
                $("#admin").html(response.admin);
            }
        });
</script>
@endpush