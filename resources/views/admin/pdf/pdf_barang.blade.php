<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF - Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style type="text/css">
		table tr td,
		table tr th{
			font-size: 12pt;
		},
        center{
            margin-bottom: 10px;
        }
	</style>
</head>
<body>
    <center class="mb-0">
        <h3>Laporan Data Barang E-ASSET</h3>
    </center>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Input By</th>
                <th>Sumber Barang</th>
                <th>Penyimpanan</th>
                <th>Waktu Input</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assets as $asset)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $asset->nama_barang }}</td>
                    <td>{{ $asset->category->nama_kategori }}</td>
                    <td>{{ $asset->stok }}</td>
                    <td>{{ $asset->admin->name }}</td>
                    <td>{{ $asset->sumber->nama_sumber }}</td>
                    <td>{{ $asset->penyimpanan }}</td>
                    <td>{{ $asset->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>