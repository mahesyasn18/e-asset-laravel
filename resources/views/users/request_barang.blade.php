@extends('users.user_layout')

@section('content')
@if (Session::has("added"))
    <script>
        swal("Berhasil","Berhasil menambah barang ke keranjang","success");
    </script>
@elseif(Session::has("deleted"))
    <script>
        swal('Berhasil',"Berhasil menghapus barang dari keranjang","success");
    </script>
@endif
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid mt--8">
    <div class="row mb-3">
        <div class="col-sm-12">
            <a href="/keranjang">
                <span class="bg-info p-2 text-white rounded">
                    <i class="fa fa-cart-plus" aria-hidden="true"></i>
                    <span class="badge" style="background-color: red">{{ $count }}</span>
                </span>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                @foreach ($Barang as $a)
                <div class="col-sm-6 col-6">
                    <div class="card-deck">
                        <div class="card w-100 mb-2 shadow-lg border border-info">
                            <div class="card-body">
                                <h3 class="card-title">{{ $a->nama_barang }}</h3>
                                <p class="card-text">Stok : {{ $a->stok }}</p>
                                <p class="card-text">Kategori : {{ $a->category->nama_kategori }}</p>
                                <a href="/detail/barang/{{$a->id}}" class="card-link">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-double-right"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z" />
                                        <path fill-rule="evenodd"
                                            d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                    Pilih
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
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