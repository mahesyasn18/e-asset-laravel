<?php

namespace App\Http\Controllers;

use App\Models\BarangDetail;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Cart;

class PinjamLangsungController extends Controller
{
    public function index()
    {
        $cart = Cart::getContent();
        if ($cart->count() == 0) {
            session()->forget("user");
        }
        $user = session()->get("user");
        $data = [
            "title" => "Request Langsung",
            "cart"  =>   $cart,
            "user" => $user
        ];
        return view("admin.pinjamlangsung.pinjam_langsung", $data);
    }

    public function create_req(Request $request)
    {
        $kode_unik = $request->kode_unik;
        if (!$kode_unik) {
            session()->flash('notfound', "Error! , tidak bisa meminjam barang ini");
            return redirect()->to("/pinjam/langsung");
        } else {
            $detail = BarangDetail::where("kode_unik", $kode_unik)->first();
            if (!$detail) {
                session()->flash('notfounds', "Error! , tidak bisa meminjam barang ini");
                return redirect()->to("/pinjam/langsung");
            } elseif ($detail->status == "dipinjam") {
                session()->flash('dipinjam', "Error! , tidak bisa meminjam barang ini");
                return redirect()->back();
            } elseif ($detail->status == "rusak") {
                session()->flash('rusak', "Error! , tidak bisa meminjam barang ini");
                return redirect()->back();
            } else {
                Cart::add([
                    'id' => $detail->id,
                    'name' => $detail->nama_barang,
                    'price' => 0,
                    'quantity' => 1,
                    'associatedModel' => $detail
                ]);

                $detail->update([
                    "status" => "dipinjam"
                ]);
                session()->flash('added', "Berhasil menambah barang ke keranjang");
                return redirect()->to("/pinjam/langsung");
            }
        }
    }
    public function remove($id)
    {
        $detail = BarangDetail::find($id);
        if (!$detail) {
            abort(404);
        } else {
            $detail->update([
                "status" => "ready"
            ]);
            Cart::remove($id);

            session()->flash("deleted", "Berhasil menghapus barang dari keranjang");
            return redirect()->to("/pinjam/langsung");
        }
    }

    public function get_users(Request $request)
    {
        $request->validate([
            "scan_kartu" => "required"
        ]);
        $nomor_induk = $request->scan_kartu;

        $user = User::where("nomor_induk", $nomor_induk)->first();
        if ($user == null) {
            return redirect()->back()->with("failed", "nomor induk user tidak terdaftar");
        } else {
            $data = [
                "id" => $user->id,
                "nama" => $user->name,
                "username" => $user->username,
                "nomor_induk" => $user->nomor_induk
            ];

            session()->forget("user");

            session()->put("user", $data);
            return redirect()->back()->with("get_user", "Berhasil mendapat user");
        }
    }

    public function insert_transaksi()
    {
        $user = session()->get("user");
        $cart = Cart::getContent();
        $admin_id = auth()->guard("admin")->id();
        $active = TahunAjaran::where("status", "active")->first();

        $request = RequestModel::create([
            "user_id" => $user["id"],
            "admin_id" => $admin_id,
            "nomor_induk" => $user["nomor_induk"],
            "nama_user" => $user["nama"],
            "kode_invoice" => rand(10000000, 20000000),
            "status_id" => 3,
            "tahunajaran_id" => $active->id,
            "tanggal_request" => date("Y-m-d"),
            "waktu_request" => date("H:i:s")
        ]);

        if ($request) {
            foreach ($cart as $item) {
                $request->detail()->attach($item->id, [
                    "status_scan" => "scanned"
                ]);
                Cart::remove($item->id);
            }
            session()->forget("user");

            session()->flash("langsung", "Berhasil membuat request barang");
            return redirect()->to("/transaksi/keluar/ongoing");
        }
    }
}
