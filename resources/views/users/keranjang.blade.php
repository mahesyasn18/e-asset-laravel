@extends('users.user_layout')

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
                    <h2>
                        Keranjang
                    </h2>
                </div>
                <div class="card-body">
                    @if (count($cart)==0)
                    <p class="text-muted text-center">
                        Belum ada data di keranjang
                    </p>
                    @else
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama dan Kode Barang</th>
                                    <th>Kode Unik</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name." 0".$item->associatedModel->kode_barang }}</td>
                                    <td>{{ $item->associatedModel->kode_unik }}</td>
                                    <td>
                                        <form action="/cart/remove/{{ $item->id }}" method="post">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn-sm btn btn-danger p-2">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div class="card-footer" align="right">
                        @if (count($cart)!=0)
                        <form action="/process" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary" onclick="button_animate()"><span>Process<span
                                        id="spinner" style="display:none;"><img src="{{ asset("img/svg/pulse.svg") }}"
                                            width="30px"></span></button>
                        </form>
                        @endif
                        <a href="/request/barang" class="btn btn-success">
                            Kembali
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection
@push('js')
<script>
    function button_animate(){
                $("#spinner").css("display","inline-block");
            }
</script>
@endpush