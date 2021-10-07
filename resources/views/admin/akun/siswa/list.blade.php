@extends('layouts.app')

@section('content')
@if (Session::has("users"))
<script>
    swal("Berhasil","Berhasil menambah user","success")
</script>
@elseif(Session::has("deleted"))
<script>
    swal("Berhasil","Berhasil memblock user","warning")
</script>
@elseif(Session::has("updated"))
<script>
    swal('Berhasil',"Berhasil mengupdate user","success")
</script>
@elseif(Session::has("unblocked"))
<script>
    swal('Berhasil',"Berhasil men-unblock user","success")
</script>
@endif
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
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="/akun/siswa/create" class="btn-sm btn btn-primary p-2"><i class="fa fa-plus-circle"
                                    aria-hidden="true"></i> Tambah User</a>
                            <a href="" class="btn-sm btn btn-info p-2" data-toggle="modal" data-target="#sort-data">Sort
                                Data</a>
                            @if (request()->kelas!=null || request()->tahun_ajaran!=null)
                            <a href="/akun/siswa" class="btn-sm btn btn-danger p-2">Clear</a>
                            @endif
                            <div class="modal fade" id="sort-data" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="" method="get">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="exampleModalLabel">Sort Data</h3>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <select name="tahun_ajaran" id="tahun_ajaran" class="form-control">
                                                        <option disabled selected>-- Pilih Tahun Ajaran</option>
                                                        @foreach ($tahun as $item)
                                                        @if (request()->tahun_ajaran==$item->id)
                                                        <option value="{{ $item->id }}" selected>
                                                            {{ $item->tahun_ajaran }}</option>
                                                        @else
                                                        <option value="{{ $item->id }}">{{ $item->tahun_ajaran }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <select name="kelas" id="kelas" class="form-control">
                                                        <option disabled selected>-- Pilih Kelas --</option>
                                                        @foreach ($kelas as $data)
                                                        @if (request()->kelas==$data->id)
                                                        <option value="{{ $data->id }}" selected>{{ $data->nama_kelas }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $data->id }}">{{ $data->nama_kelas }}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a class="btn-sm btn btn-success p-2 text-white" data-toggle="modal"
                                data-target="#exampleModal">
                                <i class="fas fa-file-excel"></i>
                                ExportExcel
                            </a>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="/eksport/siswa/excel" method="post">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="exampleModalLabel">Filter</h3>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="font-weight-bold">Pilih Kelas & Tahun Ajaran</p>
                                                <div class="form-group">
                                                    <select name="tahun_ajaran" id="tahun_ajaran" class="form-control">
                                                        <option disabled selected>-- Pilih Tahun Ajaran</option>
                                                        @foreach ($tahun as $item)
                                                        @if (request()->tahun_ajaran==$item->id)
                                                        <option value="{{ $item->id }}" selected>
                                                            {{ $item->tahun_ajaran }}</option>
                                                        @else
                                                        <option value="{{ $item->id }}">{{ $item->tahun_ajaran }}
                                                        </option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <select name="kelas" id="kelas" class="form-control">
                                                        <option disabled selected>-- Pilih Kelas --</option>
                                                        @foreach ($kelas as $data)
                                                        @if (request()->kelas==$data->id)
                                                        <option value="{{ $data->id }}" selected>{{ $data->nama_kelas }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $data->id }}">{{ $data->nama_kelas }}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Eskport</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger mt-2" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (Session::has("nothing"))
                    <div class="alert alert-danger mt-2" role="alert">
                        <strong>{{ Session::get("nothing") }}</strong>
                    </div>
                    @endif
                </div>
                <div class="card-body">
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td scope="row" align="center"><input type="checkbox" name="user_id[]" id="user_id">
                                    </td>
                                    <td>{{ $user->user->name }}<br> <strong
                                            class="text-danger">{{ $user->user->block==1 ? "( User Blocked )" : "" }}</strong>
                                    </td>
                                    <td>{{ $user->user->username }}</td>
                                    <td>{{ $user->kelas->nama_kelas }}</td>
                                    <td>{{ $user->tahunajaran->tahun_ajaran }}</td>
                                    <td>{{ $user->user->status }}</td>
                                    <td align="center">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                @if (isset($user->user->block))
                                                <form action="/siswa/unblock/{{ $user->user->id }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    @method("PUT")
                                                    <button type="submit"
                                                        class="btn-sm btn btn-danger p-2">Unblock</button>
                                                </form>
                                                @else
                                                <a href="#" class="btn-sm btn btn-danger p-2" data-toggle="modal"
                                                    data-target="#modaldelete{{ $user->user->id }}">Block</a>
                                                @endif
                                                <a href="/siswa/detail/{{ $user->user->id }}"
                                                    class="btn-primary btn btn-sm p-2">Detail</a>
                                                <a href="/siswa/edit/{{ $user->user->id }}"
                                                    class="btn-sm btn btn-success p-2">Edit</a>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modaldelete{{ $user->user->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="/siswa/block/{{ $user->user->id }}" method="post">
                                                    @csrf
                                                    @method("PUT")
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 class="modal-title" id="exampleModalLabel">Are u sure??
                                                            </h3>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Nama : {{ $user->user->name }}<br>
                                                            Username : {{ $user->user->username }}<br>
                                                            Nomor Induk : {{ $user->user->nomor_induk }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Block</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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

@endpush

@push('js')
<script>
    $("#all").click(function(event){
            if (this.checked) {
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
@endpush