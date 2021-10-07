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
                    @include('admin.transaksi.partials.transaksi_nav',["active" => "completed"])
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <form action="" method="get">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" name="range" id="range" autocomplete="off" value="{{ $date }}">
                                    <div class="input-group-append">
                                        @if ($date!=null)
                                            <button class="btn btn-danger" type="submit" id="button-addon2" value="clear" name="clear">Clear</button>
                                        @else
                                            <button class="btn btn-primary" type="submit" id="button-addon2" value="filter" name="filter">Filter</button>
                                        @endif
                                        <a href="/report/peminjaman?filter={{ $date }}" class="btn btn-success ml-2" target="_blank"><i
                                                class="fas fa-file-excel"></i> Eksport</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Nomor Induk</th>
                                    <th scope="col">Kelas / Jurusan</th>
                                    <th scope="col">Barang</th>
                                    <th scope="col">Waktu Request</th>
                                    <th scope="col">Waktu Pengembalian</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $req)
                                <tr>
                                    <td scope="row" align="center"><input type="checkbox" name="delete[]" id="delete"></td>
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
                                    <td>{{ $req->tanggal_request." ".$req->waktu_request }}</td>
                                    <td>{{ $req->waktu_pengembalian }}</td>
                                    <td>{{ Str::ucfirst($req->status->keterangan_status) }}</td>
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
      $('input[type="checkbox"]').click(function() {
        if ($(this).prop("checked") == true) {
          $("#btn-delete").fadeIn(100);
        } else if ($(this).prop("checked") == false) {
          $("#btn-delete").fadeOut(100);
        }
      });
    });
</script>
@endpush
@push('js')
<script>
    $('#range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('#range]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

</script>
@endpush