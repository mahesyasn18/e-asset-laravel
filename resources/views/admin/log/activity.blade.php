@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card mt--8">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="d-flex justify-content-between">
                                <a href="/dashboard" class="btn btn-dark"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i> Kembali</a>
                                <a href="/activity/excel" class="btn btn-success"><i class="fas fa-file-excel"></i>
                                    Excel</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Data</th>
                                    <th>Category Data</th>
                                    <th>Admin</th>
                                    <th>Jumlah Data Masuk</th>
                                    <th>Jumlah Data Keluar</th>
                                    <th>Action</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($masuk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->data }}</td>
                                    <td>{{ $item->category_data }}</td>
                                    <td>{{ $item->admin->name }}</td>
                                    <td>{{ $item->jumlah_data_masuk ?? "--" }}</td>
                                    <td>{{ $item->jumlah_data_keluar ?? "--" }}</td>
                                    <td>{{ Str::ucfirst($item->action) }}</td>
                                    <td>{{ $item->created_at }}</td>
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