<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\JurusanGuru;
use App\Models\RequestModel;
use App\Models\TahunAjaran;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $sort = $this->sort_guru($request->jurusan);
        if ($sort != null) {
            $users = $sort;
        } else {
            $users = JurusanGuru::with("user", "jurusan")->get();
        }
        $jurusan = Jurusan::all();
        $data = [
            "title" => "Data Akun Guru",
            "users" => $users,
            "jurusan" => $jurusan
        ];
        return view('admin.akun.guru.list', $data);
    }

    public function create()
    {
        $jurusan = Jurusan::all();
        $data = [
            "title" => "Create Akun User",
            "jurusan" => $jurusan
        ];
        return view('admin.akun.guru.create', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            "nama_guru" => "required|string",
            "username_guru" => "required",
            "password" => "required",
            "nip" => "required",
            "jurusan_ajar" => "required",
        ]);
        $user = User::create([
            "name" => $request->nama_guru,
            "username" => $request->username_guru,
            "password" => Hash::make($request->password),
            "nomor_induk" => $request->nip,
            "status" => "guru"
        ]);

        JurusanGuru::create([
            "user_id" => $user->id,
            "jurusan_id" => $request->jurusan_ajar
        ]);

        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => $request->nama_guru,
            "category_data" => "akun",
            "jumlah_data_masuk" => 1,
            "action" => "menambah data akun guru"
        ]);

        session()->flash("users", "Berhasil menambah user");
        return redirect()->to('/akun/guru');
    }

    public function show(Request $request, $id)
    {
        $user = JurusanGuru::whereHas("jurusan", function ($q) use ($id) {
            $q->where("user_id", $id);
        })->first();
        $id_tahun = "";
        if (!$user) {
            abort(404);
        } else {
            $tahun = TahunAjaran::all();

            if ($request->tahunajaran != null) {
                $id_tahun = (int) $request->tahunajaran;
                $request = RequestModel::with("tahunajaran", "status")->where("user_id", $id)->where("tahunajaran_id", $id_tahun)->get();
            } else {
                $request = RequestModel::with("tahunajaran", "status")->where("user_id", $id)->get();
            }
            $data = [
                "title" => "Detail User",
                "user" => $user,
                "tahun" => $tahun,
                "request" => $request,
                "filter" => $id_tahun
            ];
            return view("admin.akun.guru.show", $data);
        }
    }

    public function block($id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        } else {
            TransaksiMasuk::create([
                "admin_id" => auth()->guard("admin")->id(),
                "data" => $user->name,
                "category_data" => "akun",
                "action" => "mem-block data akun " . $user->status
            ]);
            $user->update([
                "block" => true
            ]);
        }

        session()->flash("deleted", "User berhasil di hapus");
        return redirect()->back();
    }

    public function unblock($id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        } else {
            TransaksiMasuk::create([
                "admin_id" => auth()->guard("admin")->id(),
                "data" => $user->name,
                "category_data" => "akun",
                "action" => "men-unblock data akun " . $user->status
            ]);
            $user->update([
                "block" => null
            ]);
        }

        session()->flash("unblocked", "User berhasil di unblock");
        return redirect()->back();
    }

    public function edit($id)
    {
        $user = User::where('status', 'guru')->find($id);
        $jurusan = Jurusan::get();
        $data = [
            "title" => "Edit Data Guru",
            "user" => $user,
            "jurusan" => $jurusan
        ];
        return view("admin.akun.guru.edit", $data);
    }

    public function update(Request $request, $id)
    {
        sleep(1.5);
        $request->validate([
            "nama_guru" => "required|string",
            "username_guru" => "required",
            "nip" => "required",
            "jurusan_ajar" => "required",
        ]);

        User::where('id', $id)->update([
            "name" => $request->nama_guru,
            "username" => $request->username_guru,
            "nomor_induk" => $request->nip,
            "status" => "guru"
        ]);
        JurusanGuru::where("user_id", $id)->update([
            "jurusan_id" => $request->jurusan_ajar
        ]);

        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => $request->nama_guru,
            "category_data" => "akun",
            "action" => "mengupdate data akun guru"
        ]);

        session()->flash("updated", "Berhasil mengupdate user");
        return redirect()->to("/akun/guru");
    }

    public function export(Request $request)
    {
        $request->validate([
            "jurusan" => "required",
        ]);

        $id = $request->jurusan;
        $users = JurusanGuru::with("user", "jurusan")->get();

        if (count($users) == 0) {
            session()->flash("nothing", "Tidak ada data user yang bisa di eksport");
            return redirect()->back();
        } else {
            $user_valid = JurusanGuru::where('jurusan_id', $id)->get();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $styleArray2 = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray2);
            $users = User::with("jurusan")->get();
            $sheet->setCellValue("A1", "No");
            $sheet->setCellValue("B1", "Nama");
            $sheet->setCellValue("C1", "Username");
            $sheet->setCellValue("D1", "Jurusan");
            $styleArray3 = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $i = 2;
            $no = 1;
            foreach ($user_valid as $item) {
                $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':' . 'D' . $i)->applyFromArray($styleArray3);
                $sheet->setCellValue("A" . $i, $no++);
                $sheet->setCellValue("B" . $i, $item->user->name);
                $sheet->setCellValue("C" . $i, $item->user->username);
                $sheet->setCellValue("D" . $i, $item->jurusan->nama_jurusan);
                $i++;
            }
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=laporan_data_user_guru.xlsx");
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            "excel_guru" => "required"
        ]);
        $extensions = ["xlsx", "csv"];
        $result = array($request->file('excel_guru')->getClientOriginalExtension());

        if (in_array($result[0], $extensions)) {
            $arr_file = explode(".", $request->file("excel_guru")->getClientOriginalName());
            $ext = end($arr_file);
            if ($ext === "csv") {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv;
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
            }
            $spreadsheet = $reader->load($request->file("excel_guru")->getPathname());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            for ($i = 1; $i < count($sheetData); $i++) {
                $name = $sheetData[$i]["1"];
                $username = $sheetData[$i]["2"];
                $password = Hash::make($sheetData[$i]["3"]);
                $nomor_induk = (int) $sheetData[$i]["4"];
                $nip = (string) $nomor_induk;

                if (is_numeric($sheetData[$i]["5"])) {
                    $jurusan_id = $sheetData[$i]["5"];
                    $jurusan = Jurusan::find($jurusan_id);
                    if ($jurusan == null) {
                        return redirect()->back()->with("invalid_idjurusan", "Gagal mengupload silahkan cek kembali file excel anda . Note : error kemungkinan terletak pada kolom jurusan");
                    }
                }

                if (empty($name) || empty($username) || empty($password) || empty($nip) || empty($jurusan_id)) {
                    return redirect()->back()->with("empty", "Kolom pada excel tidak boleh kosong!");
                } else {
                    if (User::where("username", "=", $username)->where("nomor_induk", "=", $nomor_induk)->first()) {
                        return redirect()->back()->with("username", "Username sudah ada!");
                    } else {
                        $user = User::create([
                            "name" => $name,
                            "username" => $username,
                            "password" => $password,
                            "nomor_induk" => $nip,
                            "status" => "guru"
                        ]);
                        JurusanGuru::create([
                            "user_id" => $user->id,
                            "jurusan_id" => $jurusan_id,
                        ]);

                        TransaksiMasuk::create([
                            "admin_id" => auth()->guard("admin")->id(),
                            "data" => $name,
                            "category_data" => "akun",
                            "jumlah_data_masuk" => 1,
                            "action" => "menambah data akun guru (Excel)"
                        ]);
                    }
                }
            }
        } else {
            return redirect()->back()->with("error", "Format excel tidak bener , mohon coba kembali !");
        }

        session()->flash("users", "Berhasil menambah user");
        return redirect()->to('/akun/siswa');
    }
}
