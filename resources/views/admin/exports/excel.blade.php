<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="2" rowspan="8" style="text-align: center;">{{URL::asset('img/logo/smk.png')}}</td>
                <td colspan="6" style="border: 1px solid rgb(0, 0, 0); text-align: center;">PEMERINTAH PROVINSI JAWA
                    BARAT</td>
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid rgb(0, 0, 0); text-align: center;">DINAS PENDIDIKAN</td>
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid rgb(0, 0, 0); text-align: center;">CABANG DINAS PENDIDIKAN
                    WILAYAH VII</td>
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid rgb(0, 0, 0); text-align: center;">SEKOLAH MENENGAH KEJURUAN
                    NEGERI 1 CIMAHI</td>
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid rgb(0, 0, 0); text-align: center;">1.Teknologi dan Rekayasa
                    2.Teknologi Informasi dan Komunikasi 3.Seni dan Industri Kreatif</td>
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid rgb(0, 0, 0); text-align: center;">Jln. Mahar Martanegara No.
                    48 Telp./Fax (022) 6629683 Leuwigajah Kota Cimahi 40533</td>
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid rgb(0, 0, 0); text-align: center;"> Website :
                    http://www.smkn1-cmi.sch.id - e-mail : info@smkn1-cmi.sch.id</td>
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid rgb(0, 0, 0); text-align: center;"> Kota Cimahi - 40533</td>
            </tr>
            <tr></tr>
            <tr></tr>
        </thead>
        <tbody>
            <tr style="border: 1px solid rgb(0, 0, 0);">
                <th style="border: 1px solid rgb(0, 0, 0); text-align: center;">No</th>
                <th style="border: 1px solid rgb(0, 0, 0);">Nama</th>
                <th style="border: 1px solid rgb(0, 0, 0);">Nomor Induk</th>
                <th style="border: 1px solid rgb(0, 0, 0);">Kelas / Jurusan</th>
                <th style="border: 1px solid rgb(0, 0, 0);">Barang</th>
                <th style="border: 1px solid rgb(0, 0, 0);">Waktu Request</th>
                <th style="border: 1px solid rgb(0, 0, 0);">Waktu Pengembalian</th>
                <th style="border: 1px solid rgb(0, 0, 0);">Status</th>
            </tr>
            @foreach ($requests as $request)
            <tr style="border: 1px solid rgb(0, 0, 0);">
                <td style="border: 1px solid rgb(0, 0, 0);">{{ $loop->iteration }}</td>
                <td style="border: 1px solid rgb(0, 0, 0);">{{ $request->nama_user }}</td>
                <td style="border: 1px solid rgb(0, 0, 0);">{{ $request->nomor_induk }}</td>
                <td style="border: 1px solid rgb(0, 0, 0);">
                    @if ($request->user->status=="siswa")
                    {{ Str::ucfirst($request->user->tingkat)."-".$request->user->jurusan->singkatan."-".Str::ucfirst($request->user->kelas) }}
                    @else
                    {{ $request->user->jurusan->nama_jurusan }}
                    @endif
                </td>
                <td style="border: 1px solid rgb(0, 0, 0);">
                    @foreach ($request->detail as $item)
                    @if ($item->id==$item->pivot->detail_id)
                    {{ $item->nama_barang." 0 ".$item->kode_barang." " }}
                    @endif
                    @endforeach
                </td>
                <td style="border: 1px solid rgb(0, 0, 0);">{{ $request->tanggal_request." ".$request->waktu_request }}
                </td>
                <td style="border: 1px solid rgb(0, 0, 0);">{{ $request->waktu_pengembalian }}</td>
                <td style="border: 1px solid rgb(0, 0, 0);">{{ Str::ucfirst($request->status->keterangan_status) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>