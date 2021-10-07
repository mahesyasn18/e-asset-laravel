<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\TransaksiMasuk;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahun = TahunAjaran::all();
        $data = [
            "title" => "Daftar Tahun Ajaran",
            "tahun" => $tahun
        ];
        return view("admin.transaksi.tahun_ajaran", $data);
    }

    public function update_data($id)
    {
        $cari = TahunAjaran::find($id);
        if (!$cari) {
            abort(404);
        } else {
            $active = TahunAjaran::where("status", "active")->first();
            $update = TahunAjaran::where("id", $active->id)->update([
                "status" => ""
            ]);

            if ($update) {
                $cari->update([
                    "status" => "active"
                ]);

                TransaksiMasuk::create([
                    "admin_id" => auth()->guard("admin")->user()->id,
                    "data" => "Activate Tahun Ajaran",
                    "category_data" => "tahun ajaran",
                    "action" => "Aktivasi Tahun Ajaran terbaru"
                ]);
            }

            session()->flash("updated", "Berhasil update data");
            return redirect()->back();
        }
    }
    public function insertTA(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => "required|unique:tahun_ajaran"
        ]);

        TahunAjaran::create([
            "tahun_ajaran" => $request->tahun_ajaran
        ]);
        session()->flash("success", "Berhasil menambah Tahun Ajaran");
        return redirect()->back();
    }
}
