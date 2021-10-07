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
                    <div class="container-fluid">
                        <div id="text" class="h2 mb-4 text-gray-800" align="center" style="padding-top: 10px;">
                            <h1 class="h1 mb-4 text-gray-800" align="center" style="padding-top: 30px;">E-ASSET</h1>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="row" style="width: 450px">
                                <div class="col-12 nav-item dropdown no-arrow" align="center">
                                    <a href="/request/barang">
                                        <input type="submit" name="submit" value="Request Barang Sekarang"
                                            class="btn btn-primary">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script type="text/javascript">
        var i = 0,
        text;
        text = "E-ASSET merupakan aplikasi yang digunakan untuk mengelola data inventaris yang meliputi penerimaan barang, pendistribusian barang, permintaan barang, dan pengembalian barang. E-ASSET memungkinkan kita untuk melacak produk atau item berdasarkan transaksi barang atau jenis transaksi. E-ASSET juga dapat digunakan untuk mencetak setiap transaksi dan membantu dalam mengganti stok barang yang rusak."

        function typing() {
            if (i < text.length) {
            document.getElementById("text").innerHTML += text.charAt(i);
            i++;
            setTimeout(typing, 50);
            }
        }
        typing();
    </script>
@endpush