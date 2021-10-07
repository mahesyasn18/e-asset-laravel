@extends('layouts.app')

@section('content')
@if (Session::has('admins'))
<script>
    swal("Berhasil","Berhasil menambah akun admin","success");
</script>
@elseif(Session::has("deleted"))
<script>
    swal("Berhasil" ,"Berhasil mem-block akun admin","warning");
</script>
@elseif(Session::has('admin-update'))
<script>
    swal("Berhasil" ,"Berhasil mengupdate akun admin","success");
</script>
@elseif(Session::has("admin-unblock"))
<script>
    swal("Berhasil","Berhasil unblock admin","success");
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
                    <a href="/akun/admin/create" class="btn-sm btn btn-primary p-2"><i class="fa fa-plus-circle"
                            aria-hidden="true"></i> Tambah Admin</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Jurusan</th>
                                    <th align="center">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->username }}</td>
                                    <td>{{ $admin->jurusan->nama_jurusan }}</td>
                                    <td align="center">{{ Str::ucfirst($admin->status) }}</td>
                                    <td align="center">
                                        <a href="/admin/edit/{{ $admin->id }}" class="btn-sm btn btn-success p-2"><i
                                                class="fa fa-pen" aria-hidden="true"></i></a>
                                        @if (Auth::guard("admin")->user()->id!=$admin->id)
                                        @if ($admin->status=="admin")
                                        <a href="" class="btn-sm btn btn-danger p-2" data-toggle="modal"
                                            data-target="#modaldelete{{ $admin->id }}">Block</a>
                                        @else
                                        <form action="/admin/unblock/{{ $admin->id }}" method="post" class="d-inline">
                                            @csrf
                                            @method("PUT")
                                            <button type="submit" class="btn-sm btn btn-primary p-2">Unblock</button>
                                        </form>
                                        @endif
                                        @endif
                                        <a href="/admin/log-activity/{{ $admin->id }}"
                                            class="btn-sm btn btn-info p-2">Detail</a>
                                        <div class="modal fade" id="modaldelete{{ $admin->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="/admin/block/{{ $admin->id }}" method="post">
                                                    @csrf
                                                    @method("PUT")
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 class="modal-title" id="exampleModalLabel">Yakin mau
                                                                mem-block?</h3>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Nama : {{ $admin->name }}<br>
                                                            Username : {{ $admin->username }}
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