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
                    @include('admin.transaksi.partials.transaksi_nav',["active" => "cancel"])
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <form action="" method="get">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" name="range" id="range" autocomplete="off"
                                        value="{{ $date }}">
                                    <div class="input-group-append">
                                        @if ($date!=null)
                                        <button class="btn btn-danger" type="submit" id="button-addon2" value="clear"
                                            name="clear">Clear</button>
                                        @else
                                        <button class="btn btn-primary" type="submit" id="button-addon2" value="filter"
                                            name="filter">Filter</button>
                                        @endif
                                        <a href="/report/peminjaman/cancel?filter={{ $date }}"
                                            class="btn btn-success ml-2" target="_blank"><i
                                                class="fas fa-file-excel"></i> Eksport</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                    <div class="table-responsive">
                        @if (Session::has("refused"))
                        <div class="alert alert-success" role="alert">
                            <strong>{{ Session::get("refused") }}</strong>
                        </div>
                        @endif
                        <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Nomor Induk</th>
                                    <th scope="col">Kelas / Jurusan</th>
                                    <th scope="col">Barang</th>
                                    <th scope="col">Waktu Request</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                <tr>
                                    <td scope="row" align="center"><input type="checkbox" name="delete[]" id="delete">
                                    </td>
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
                                            <li>{{ $item->nama_barang." 0".$item->kode_barang }}</li>
                                        </ul>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $request->tanggaL_request." ".$request->waktu_request }}</td>
                                    <td>{{ $request->status->keterangan_status }}</td>
                                    <td>{{ $request->keterangan }}</td>
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