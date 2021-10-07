@extends('users.user_layout')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid mb-3">
    <div class="row mt--7">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('request.barang')}}" class="btn btn-dark"><i class="fa fa-arrow-circle-left"
                            aria-hidden="true"></i>
                        Kembali</a>
                    @if (Session::has("changed"))
                    <div class="alert alert-success mt-2" role="alert">
                        <strong>{{ Session::get("changed") }}</strong>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <p class="text-primary"><i class="fa fa-info-circle" aria-hidden="true"></i>
                        {{ count($count)." Barang sedang dipinjam" }}</p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama dan Kode Barang</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $detail)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $detail->barang->nama_barang." 0".$detail->kode_barang }}</td>
                                    @if ($detail->status=="ready")
                                    <td class="bg-success text-white" align="center">{{ Str::ucfirst($detail->status) }}
                                    </td>
                                    @elseif ($detail->status=="dipinjam")
                                    <td class="bg-info text-white" align="center">{{ Str::ucfirst($detail->status) }}
                                    </td>
                                    @elseif($detail->status="rusak")
                                    <td class="bg-danger text-white" align="center">{{ Str::ucfirst($detail->status) }}
                                    </td>
                                    @endif
                                    <td>
                                        @if ($detail->status=="ready")
                                        <a href="/create/request?id={{$detail->id}}" class="btn-sm btn btn-primary">Buat
                                            Request</a>
                                        @elseif($detail->status=="dipinjam")

                                        @else
                                        --
                                        @endif
                                    </td>
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