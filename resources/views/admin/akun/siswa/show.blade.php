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
                <h1 class="display-2 text-white">{{ $user->user->name }}</h1>
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
                                    {{ $user->user->name }}
                                </h3>
                                <div class="h4 font-weight-300">
                                    {{ $user->user->username }}
                                </div>
                                <div class="h4 mt-4">
                                    {{ Str::ucfirst($user->user->status) }} -
                                    {{ $user->kelas->nama_kelas }}
                                </div>
                                <div>
                                    <i
                                        class="ni education_hat mr-2"></i>{{ __('Sekolah Menengah Kejuruan Negeri 1 Cimahi') }}
                                </div>
                                <hr class="my-4" />
                                <p>List transaction of spesific users and admins can make a transaction report for a
                                    spesific user</p>
                                @if ($user->user->status=="siswa")
                                    <a href="/akun/siswa"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
                                @else
                                    <a href="/akun/guru"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">Daftar Transaksi {{ $user->name }}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle btn-sm" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Tahun Ajaran
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if (request()->tahunajaran!=null)
                                            <a href="/siswa/detail/{{ request()->id }}" class="dropdown-item">
                                                <span>
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    Clear Filter
                                                </span>
                                            </a>
                                            @endif
                                            @foreach ($tahun as $data)
                                            <a href="?tahunajaran={{ $data->id }}"
                                                class="dropdown-item {{ request()->tahunajaran==$data->id || $active->id==$data->id ? "bg-info text-white" : "" }}">{{ $data->tahun_ajaran}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <a href="/users/export/{{request()->id}}{{ request()->tahunajaran!=null ? "?filter=$filter" : "" }}"
                                        class="btn btn-info btn-sm d-inline float-right">Download Report
                                        User</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Tahun Ajaran</th>
                                        <th scope="col">Barang</th>
                                        <th scope="col">Waktu Request</th>
                                        <th scope="col">Waktu Pengembalian</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($request as $req)
                                    <tr>
                                        <td scope="row">{{ $loop->iteration }}</td>
                                        <td>{{ $req->nama_user }}</td>
                                        <td>{{ $req->tahunajaran->tahun_ajaran }}</td>
                                        <td>
                                            @foreach ($req->detail as $item)
                                            @if ($item->id==$item->pivot->detail_id)
                                            <ul>
                                                <li>{{ $item->nama_barang." 0".$item->kode_barang }}</li>
                                            </ul>
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $req->tanggal_request." ".$req->waktu_request }}</td>
                                        <td>{{ $req->waktu_pengembalian }}</td>
                                        <td>{{ $req->status->keterangan_status }}</td>
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