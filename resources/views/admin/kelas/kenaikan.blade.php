@extends('layouts.app')

@section('content')
@if (Session::has("succed"))
<script>
    swal("Berhasil","Berhasil menambah tahun ajaran","success")
</script>
@endif
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">

        </div>
    </div>
</div>
<div class="container-fluid mt--8">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <a href="/daftar-kelas" class="btn btn-success"><svg width="22px" height="22px" viewBox="0 0 16 16"
                        class="bi bi-arrow-left-short" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
                    </svg>Kembali</a>
                <a href="" class="btn btn-primary" id="btn-naik" style="display: none" onclick="sendData()">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    Naik
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (Session::has("error"))
            <div class="alert alert-danger">
                <strong>{{ Session::get('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            @endif
            <form action="/naik-kelas" method="post">
                @csrf
                <div class="form-group forms-naik" style="display: none">
                    <select name="tahunajaran" id="tahunajaran" class="form-control">
                        <option disabled selected>-- Pilih Tahun Ajaran</option>
                    </select>
                    @error('tahunajaran')
                    <strong class="text-danger">{{ $message }}</strong>
                    @enderror
                </div>
                <div class="form-group forms-naik" style="display: none">
                    <select name="kelas" id="kelas" class="form-control">
                        <option disabled selected>-- Pilih Kelas</option>
                    </select>
                    @error('kelas')
                    <strong class="text-danger">{{ $message }}</strong>
                    @enderror
                </div>
                <div class="form-group forms-naik" style="display: none">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
                <div class="table-responsive">
                    <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" name="all" id="all"></th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Kelas</th>
                                <th>Tahun Ajaran</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td scope="row" class="text-center">
                                    <input type="checkbox" name="id[]" id="user_id" value="{{ $user->user_id }}">
                                </td>
                                <td>{{ $user->user->name }}<br> <strong
                                        class="text-danger">{{ $user->user->block==1 ? "( User Blocked )" : "" }}</strong>
                                </td>
                                <td>{{ $user->user->username }}</td>
                                <td>{{ $user->kelas->nama_kelas }}</td>
                                <td>{{ $user->tahunajaran->tahun_ajaran }}</td>
                                <td>{{ $user->user->status }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </form>
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
    $("#all").click(function(event){
            if(this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function() {
                    this.checked = true;                        
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;                       
                });
            }
        })
</script>

<script>
    $("input[type='checkbox']").click(function(){
            if (this.checked) {
                $("#btn-naik").css("display","block")
            }
            else{
                const count = $('input:checkbox:checked').length;
                if (count==0) {
                    $("#btn-naik").css("display","none")
                }
            }
        })
</script>

<script>
    function sendData(){
            event.preventDefault();
            $.ajax({
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/get-data-kelas",
                data: "",
                dataType: "json",
                success: function (response) {
                    let kelas = response.kelas;
                    let tahunajaran = response.tahun;
                    $(".forms-naik").css("display","block");
                    console.log(tahunajaran);
                    $.each(tahunajaran, function (i, value) { 
                         $("#tahunajaran").append(`
                            <option value=`+ value.id +`>`+value.tahun_ajaran+`</option>
                         `)
                    });

                    $.each(kelas, function (i, data) { 
                        $("#kelas").append(`
                            <option value=`+ data.id +`>`+ data.nama_kelas +`</option>
                        `)
                    });
                    $("#btn-naik").css("display","none");
                }
            });
        }
</script>
@endpush