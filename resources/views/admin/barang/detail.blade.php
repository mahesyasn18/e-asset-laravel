@extends('layouts.app')

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
                <form action="/barcode/print/{{ $id }}" method="post">
                    @csrf
                    <div class="card-header">
                        <a href="/dashboard" class="btn btn-dark"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                            Kembali</a>
                        <button type="submit" class="btn btn-primary" id="btn-barcode" style="display: none"><i class="fa fa-print" aria-hidden="true"></i> Print Barcode</button>
                        @if (Session::has("changed"))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{ Session::get("changed") }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h4 id="count-check">0 Checked</h4>
                        <h4 class="text-danger" id="text-max"></h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama & Kode Barang</th>
                                        <th>Kategori</th>
                                        <th>Kode Unik</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $detail)
                                    <tr>
                                        <td scope="row" align="center"><input type="checkbox" name="barcode[]" id="barcode" value="{{ $detail->id }}"></td>
                </form>
                                    <td>{{ $detail->barang->nama_barang." 0".$detail->kode_barang }}</td>
                                    <td>{{ $detail->category->nama_kategori }}</td>
                                    <td>{{ $detail->kode_unik }}</td>
                                    @if ($detail->status=="ready")
                                    <td class="bg-success text-white" align="center">{{ Str::ucfirst($detail->status) }}
                                    </td>
                                    @elseif($detail->status=="rusak")
                                    <td class="bg-danger text-white" align="center">{{ Str::ucfirst($detail->status) }}
                                    </td>
                                    @else
                                    <td class="bg-info text-white" align="center">{{ Str::ucfirst($detail->status) }}
                                    </td>
                                    @endif
                                    <td>
                                        @if ($detail->status!="dipinjam")
                                        <form action="/asset/status?status={{ $detail->status }}&id={{ $detail->id }}"
                                            method="post">
                                            @csrf
                                            @method("PUT")
                                            <button type="submit" class="btn-sm btn btn-success">Ubah Status</button>
                                        </form>
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

@push('js')
    <script>
        $("input[type='checkbox']").change(function(){
            if ( $('input:checkbox:checked').length > 50) {
                toastr.options = {
                "closeButton": true,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
                }
                toastr.error("Tidak bisa lebih dari 50 barcode dalam 1 kali print!");
                this.checked = false;
            }

            if (this.checked) {
                var count = $('input:checkbox:checked').length;
                $("#btn-barcode").css("display","inline-block")
                $("#count-check").html(count+" Checked")
            }
            else{
                var count = $('input:checkbox:checked').length;
                if (count==0) {
                    $("#btn-barcode").css("display","none")
                }
                $("#count-check").html(count+" Checked")
            }
        })
    </script>
@endpush