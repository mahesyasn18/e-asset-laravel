@extends('layouts.app')

@section('content')
<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center"
    style="background-image: url(../argon/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
                <h1 class="display-2 text-white">{{ $admin->name }}</h1>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
            <div class="card card-profile shadow">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a href="#">
                                <img src="{{ asset('img/logo/profile.jpg    ') }}" class="rounded-circle" width="400px">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0" style="margin-top:85px">
                    <div class="row">
                    </div>
                    <div class="row mt-5">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <h3>
                                    {{ $admin->name }}
                                </h3>
                                <div class="h4 font-weight-300">
                                    {{ $admin->username }}
                                </div>
                                <div class="h4 mt-4">
                                    {{ $admin->jurusan->nama_jurusan }}
                                </div>
                                <div>
                                    <i
                                        class="ni education_hat mr-2"></i>{{ __('Sekolah Menengah Kejuruan Negeri 1 Cimahi') }}
                                </div>
                                <hr class="my-4" />
                                <p>List log activity of spesific admin and for tracking if there is a suspicious activity</p>
                                <a href="/akun/admin"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="card shadow">
                <div class="card-header">
                    <div class="container">
                        <div class="row align-items-center">
                            <h3 class="mb-0">Log Activity {{ $admin->name }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="log-activity">
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
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
    $('#log-activity').DataTable({
        "pagingType": "numbers"
    });
} );
</script>

@endpush