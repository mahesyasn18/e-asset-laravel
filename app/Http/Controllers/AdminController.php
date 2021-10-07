<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\Jurusan;
use App\Models\User;
use App\Models\Admin;
use App\Models\Sumber;

class AdminController extends Controller
{
    function __construct()
    {
        $this->middleware("auth:admin");
    }

    public function index()
    {
        $barang = Barang::with("category", "sumber", "admin")->get();
        $data = [
            "title" => "Dashboard",
            "barang" => $barang
        ];
        return view("dashboard", $data);
    }

    public function adminLogout()
    {
        auth()->guard('admin')->logout();
        session()->flush();

        return redirect()->route('logout_admin');
    }

    public function jurusan()
    {
        $jurusan = Jurusan::all();
        $data = [
            "title" => "Daftar Jurusan",
            "jurusan" => $jurusan
        ];
        return view("admin.Jurusan.daftar_jurusan", $data);
    }

    public function datas()
    {

        return response()->json([
            "detail" => count(BarangDetail::all()),
            "users" => count(User::all()),
            "admin" => count(Admin::where("status", "admin")->get())
        ]);
    }

    public function sumber()
    {
        $data = [
            "title" => "Sumber Barang"
        ];
        return view("admin.barang.sumber", $data);
    }

    public function sumber_insert(Request $request)
    {
        $request->validate([
            "nama_sumber" => "required|unique:sumber"
        ]);

        Sumber::create([
            "nama_sumber" => $request->nama_sumber
        ]);
        return redirect()->back();
    }

    public function sumber_data()
    {
        $sumber = Sumber::all();
        return response()->json(["sumber" => $sumber]);
    }

    public function notif()
    {
        $request = RequestModel::where("status_id", 1)->get();
        $count = count($request);
        $output = "";
        foreach ($request as $req) {
            $output .= "<a href='/transaksi/keluar/pending' class='dropdown-item'><span>New request from $req->nama_user</span></a>";
        };
        return response()->json([
            "count" => $count,
            "output" => $output
        ], 200);
    }
}
