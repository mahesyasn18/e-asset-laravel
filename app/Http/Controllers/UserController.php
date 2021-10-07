<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\User;
use App\Models\RequestModel;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cart;
use Pusher\Pusher;
use App\Events\RequestEvent;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:web');
    }
    public function index()
    {
        $data = [
            "title" => "Welcome User"
        ];
        return view("users.index", $data);
    }
    public function request()
    {
        $user_id = auth()->guard("web")->id();
        $cart = Cart::session($user_id)->getContent();
        $count = $cart->count();
        $barang = Barang::with("category", "detail")->get();
        $data = [
            "title" => "Request Barang",
            "Barang" => $barang,
            "count" => $count
        ];
        return view("users.request_barang", $data);
    }
    public function keranjang()
    {
        $user_id = auth()->guard("web")->id();
        $cart = Cart::session($user_id)->getContent();
        $data = [
            "title" => "Request Barang",
            "cart" => $cart
        ];
        return view("users.keranjang", $data);
    }
    public function riwayat()
    {
        $user_id = auth()->guard("web")->id();
        $requests = RequestModel::with("status")->where("user_id", $user_id)->get();
        $data = [
            "title" => "Riwayat Transaksi",
            "requests" => $requests
        ];
        return view("users.riwayat_transaksi", $data);
    }

    public function detail($id)
    {
        $details = BarangDetail::with("category", "barang")->where("barang_id", $id)->get();
        $count = BarangDetail::where("status", "dipinjam")
            ->where("barang_id", $id)
            ->get();
        if (count($details) == 0) {
            abort(404);
        }
        $data = [
            "title" => "Detail Barang",
            "details" => $details,
            "count" => $count
        ];
        return view("users.detail_barang", $data);
    }

    public function create_request(Request $request)
    {
        $id = $request->id;
        if (!$id) {
            abort(404);
        } else {
            $detail = BarangDetail::find($id);
            if (!$detail) {
                abort(404);
            } else {
                $user_id = auth()->guard("web")->id();
                Cart::session($user_id)->add([
                    'id' => $id,
                    'name' => $detail->nama_barang,
                    'price' => 0,
                    'quantity' => 1,
                    'associatedModel' => $detail
                ]);

                $detail->update([
                    "status" => "dipinjam"
                ]);

                session()->flash('added', "Berhasil menambah barang ke keranjang");
                return redirect()->to("/request/barang");
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

            $user_id = auth()->guard("web")->id();
            Cart::session($user_id)->remove($id);

            session()->flash("deleted", "Berhasil menghapus barang dari keranjang");
            return redirect()->to("/request/barang");
        }
    }

    public function process_request()
    {
        $user_id = auth()->guard("web")->id();
        $user = User::find($user_id);
        $active = TahunAjaran::where(["status" => "active"])->first();

        $request = RequestModel::create([
            "user_id" => $user_id,
            "nomor_induk" => $user->nomor_induk,
            "nama_user" => $user->name,
            "status_id" => 1,
            "tahunajaran_id" => $active->id,
            "tanggal_request" => date("Y-m-d"),
            "waktu_request" => date("H:i:s")
        ]);

        if ($request) {
            $cart = Cart::session($user_id)->getContent();
            foreach ($cart as $item) {
                $request->detail()->attach($item->id);
                Cart::session($user_id)->remove($item->id);
            }


            $option = [
                'cluster' => 'ap1',
                'encrypted' => true
            ];
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $option
            );
            $data['message'] = "New request from";
            $data['name'] = $user->name;
            $pusher->trigger('request-channel', 'request-event', $data);
            session()->flash("requested", "Berhasil membuat request");
            return redirect()->to("/riwayat/transaksi");
        }
    }
}
