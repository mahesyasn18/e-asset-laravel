@extends('layouts.app')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid mt--8">
    <div class="card">
        <div class="card-header">
            <form action="" method="get">
                @csrf
                <div class="input-group">
                    <select name="tahun_ajaran" id="tahun_ajaran" class="form-control">
                        <option selected disabled>-- Pilih Tahun Ajaran</option>
                        @foreach ($tahunajaran as $data)
                            @if (request()->tahun_ajaran==$data->id)
                                <option value="{{ $data->id }}" selected>{{ $data->tahun_ajaran }}</option>
                                @else
                                <option value="{{ $data->id }}">{{ $data->tahun_ajaran }}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        @if (request()->tahun_ajaran!="")
                            <a href="/request-log" class="btn btn-danger">Clear</a>
                        @else
                            <button class="btn btn-primary" type="submit" id="button-addon2">Filter</button>
                        @endif
                            <a href="/request-log/excel?filter={{ $filter }}" class="btn btn-success ml-2"
                                target="_blank"><i class="fas fa-file-excel"></i> Eksport</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <dit class="table-responsive">
                <table class="table table-bordered" id="table-log">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Nomor Induk</th>
                            <th scope="col">Kelas / Jurusan</th>
                            <th scope="col">Barang</th>
                            <th scope="col">Tahun Ajaran</th>
                            <th scope="col">Waktu Request</th>
                            <th scope="col">Waktu Pengembalian</th>
                            <th scope="col">Status</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $req)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $req->nama_user }}</td>
                            <td>{{ $req->nomor_induk }}</td>
                            <td>
                                @if ($req->siswa!=null)
                                    {{ $req->siswa->kelas->nama_kelas }}
                                @else
                                    {{ $req->guru->jurusan->nama_jurusan }}
                                @endif
                            </td>
                            <td>
                                @foreach ($req->detail as $item)
                                    @if ($item->id==$item->pivot->detail_id)
                                    <ul>
                                        <li>{{ $item->nama_barang." 0".$item->kode_barang }}</li>
                                    </ul>
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $req->tahunajaran->tahun_ajaran }}</td>
                            <td>{{ $req->tanggal_request." ".$req->waktu_request }}</td>
                            <td>{{ $req->waktu_pengembalian }}</td>
                            <td>{{ Str::ucfirst($req->status->keterangan_status) }}</td>
                            <td>{{ $req->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </dit>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function(){
            $('#table-log').DataTable({
                "pagingType": "numbers"
            });
         })
</script>
@endpush