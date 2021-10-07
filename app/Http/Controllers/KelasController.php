<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\KelasDetail;
use App\Models\KelasSiswa;

class KelasController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        $tahun = TahunAjaran::all();
        $data = [
            "title" => "Daftar Kelas",
            "jurusan" => $jurusan,
        ];
        return view("admin.kelas.daftar_kelas", $data);
    }
    public function show($id)
    {
        $kelas = Kelas::with("jurusan")->where("jurusan_id", $id)->get();
        $jurusan = Jurusan::all();
        $tahunajaran = TahunAjaran::all();
        $data = [
            "title" => "Daftar Kelas",
            "jurusan" => $jurusan,
            "kelas" => $kelas,
            "tahunajaran" => $tahunajaran

        ];
        return view("admin.kelas.kelas", $data);
    }

    public function detail($id)
    {
        $active = TahunAjaran::where("status", "active")->first();
        $users = KelasSiswa::with("user", "kelas", "tahunajaran")->where("kelas_id", $id)->where("tahunajaran_id", $active->id)->get();
        $data = [
            "title" => "Data Akun Siswa",
            "users" => $users,
        ];
        return view('admin.kelas.kenaikan', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            "tingkat" => "required",
            "kelas" => "required",
            "jurusan" => "exists:jurusan,id"
        ]);

        $jurusan = Jurusan::find($request->jurusan);
        $singkat = $jurusan->singkatan;
        Kelas::create([
            "nama_kelas" => $request->tingkat . " " . $singkat . " " . $request->kelas,
            "jurusan_id" => $request->jurusan
        ]);

        return redirect()->back()->with("kelas", "Kelas berhasil dibuat");
    }

    public function data()
    {
        $tahun = TahunAjaran::all();
        $kelas = Kelas::all();

        return response()->json([
            "kelas" => $kelas,
            "tahun" => $tahun
        ]);
    }

    public function naik_kelas(Request $req)
    {
        $user_id = $req->id;
        $kelas = $req->kelas;
        $tahunajaran = $req->tahunajaran;

        $req->validate([
            "kelas" => "required",
            "tahunajaran" => "required"
        ]);
        for ($i = 0; $i < count($user_id); $i++) {
            KelasSiswa::create([
                "user_id" => $user_id[$i],
                "kelas_id" => $kelas,
                "tahunajaran_id" => $tahunajaran
            ]);
        }

        return redirect()->to("/daftar-kelas")->with("success", "Berhasil menaikan kelas");
    }
}
