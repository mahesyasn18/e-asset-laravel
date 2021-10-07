<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\RequestModel;
use App\Models\TahunAjaran;
use App\Models\KelasDetail;
use App\Models\KelasSiswa;
use App\Models\TransaksiMasuk;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $active = TahunAjaran::where("status", "active")->first();
        $sort = $this->sort_users($request->kelas, $request->tahun_ajaran);
        if ($sort != null) {
            $users = $sort;
        } else {
            $users = KelasSiswa::with("user", "kelas", "tahunajaran")->where("tahunajaran_id", $active->id)->get();
        }
        $jurusan = Jurusan::all();
        $kelas = Kelas::orderBy("nama_kelas")->get();
        $tahun = TahunAjaran::all();
        $data = [
            "title" => "Data Akun Siswa",
            "users" => $users,
            "jurusan" => $jurusan,
            "kelas" => $kelas,
            "tahun" => $tahun
        ];
        return view('admin.akun.siswa.list', $data);
    }

    public function create(Request $request)
    {
        $tahun = TahunAjaran::all();
        $kelas = Kelas::all();
        $data = [
            "title" => "Create Akun User",
            "kelas" => $kelas,
            "tahun" => $tahun
        ];
        return view('admin.akun.siswa.create', $data);
    }

    public function insert(Request $request)
    {
        sleep(1.5);
        $request->validate(
            [
                "nama" => "required|unique:users,name",
                "username" => "required|unique:users,username",
                "password" => "required",
                "nis" => "required|unique:users,nomor_induk",
                "tahun_masuk" => "required",
                "kelas" => "required"
            ],
            [
                "nama.required" => "Kolom nama wajib diisi",
                "nama.unique" => "Nama yang di masukkan sudah ada",
                "username.required" => "Username harus diisi",
                "username.unique" => "Username sudah digunakan",
                "password.required" => "Kolom password harus sudah diisi",
                "nis.required" => "Kolom NIS harus diisi",
                "tahun_masuk.required" => "Tahun Masuk harus sudah diisi",
                "kelas.required" => "Kelas harus diisi"
            ]
        );

        $user = User::create([
            "name" => $request->nama,
            "username" => $request->username,
            "password" => Hash::make($request->password),
            "nomor_induk" => $request->nis,
            "status" => "siswa"
        ]);
        if ($user) {
            $kelas_id = $request->kelas;
            $tahunajaran_id = $request->tahun_masuk;
            $user_id = $user->id;

            KelasSiswa::create([
                "user_id" => $user_id,
                "kelas_id" => $kelas_id,
                "tahunajaran_id" => $tahunajaran_id
            ]);
        }

        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => $request->nama,
            "category_data" => "akun",
            "jumlah_data_masuk" => 1,
            "action" => "menambah akun user siswa"
        ]);

        session()->flash("users", "Berhasil menambah user");
        return redirect()->to('/akun/siswa');
    }

    public function show(Request $request, $id)
    {
        $active = TahunAjaran::where("status", "active")->first();
        $user = KelasSiswa::with("user", "kelas", "tahunajaran")->where("user_id", $id)->where("tahunajaran_id", $active->id)->first();
        $id_tahun = "";
        if (!$user) {
            abort(404);
        } else {
            $tahun = TahunAjaran::all();

            if ($request->tahunajaran != null) {
                $id_tahun = (int) $request->tahunajaran;
                $request = RequestModel::with("tahunajaran", "status")->where("user_id", $id)->where("tahunajaran_id", $id_tahun)->get();
                $user = KelasSiswa::with("user", "kelas", "tahunajaran")->where("user_id", $id)->where("tahunajaran_id", $id_tahun)->first();
                if ($user == null) {
                    $user = KelasSiswa::with("user", "kelas", "tahunajaran")->where("user_id", $id)->where("tahunajaran_id", $active->id)->first();
                }
                $active->id = $id_tahun;
            } else {
                $request = RequestModel::with("tahunajaran", "status")->where("user_id", $id)->get();
            }
            $data = [
                "title" => "Detail User",
                "user" => $user,
                "tahun" => $tahun,
                "request" => $request,
                "filter" => $id_tahun,
                "active" => $active
            ];
            return view("admin.akun.siswa.show", $data);
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
        $user = KelasSiswa::where("user_id", $id)->first();
        $jurusan = Jurusan::get();
        $data = [
            "title" => "Edit Data Siswa",
            "user" => $user,
            "jurusan" => $jurusan
        ];
        return view("admin.akun.siswa.edit", $data);
    }

    public function update(Request $request, $id)
    {
        sleep(1.5);
        $request->validate([
            "nama" => "required|string|unique:users,name," . $id,
            "username" => "required|string|unique:users,username," . $id,
            "nis" => "required|unique:users,nomor_induk," . $id,
        ]);

        User::where('id', $id)->update([
            "name" => $request->nama,
            "username" => $request->username,
            "nomor_induk" => $request->nis,
            "status" => "siswa"
        ]);

        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => $request->nama,
            "category_data" => "akun",
            "action" => "mengupdate data akun siswa"
        ]);

        session()->flash("updated", "Berhasil mengupdate user");
        return redirect()->to("/akun/siswa");
    }

    public function export(Request $request)
    {
        $request->validate([
            "kelas" => "required",
            "tahun_ajaran" => "required",
        ]);

        $id = $request->kelas;
        $tahunajaran = $request->tahun_ajaran;
        $users = KelasSiswa::where("kelas_id", $id)->get();
        if (count($users) == 0) {
            session()->flash("nothing", "Tidak ada data user yang bisa di eksport");
            return redirect()->back();
        } else {
            $user_valid = KelasSiswa::where('kelas_id', $id)
                ->where("tahunajaran_id", $tahunajaran)
                ->with("user", "kelas", "tahunajaran")
                ->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $styleArray3 = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);


            $users = User::with("jurusan")->get();
            $spreadsheet->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray3);
            $sheet->setCellValue("A1", "No");
            $sheet->setCellValue("B1", "Nama");
            $sheet->setCellValue("C1", "Username");
            $sheet->setCellValue("D1", "Kelas");
            $sheet->setCellValue("E1", "Jurusan");

            $i = 2;
            $no = 1;
            foreach ($user_valid as $item) {
                $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':' . 'E' . $i)->applyFromArray($styleArray3);
                $sheet->setCellValue("A" . $i, $no++);
                $sheet->setCellValue("B" . $i, $item->user->name);
                $sheet->setCellValue("C" . $i, $item->user->username);
                $sheet->setCellValue("D" . $i, $item->kelas->nama_kelas);
                $sheet->setCellValue("E" . $i, $item->kelas->jurusan->nama_jurusan);
                $i++;
            }
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=laporan_data_user.xlsx");
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            "excel" => "required"
        ]);
        $extensions = ["xlsx", "csv"];
        $result = array($request->file('excel')->getClientOriginalExtension());
        if (in_array($result[0], $extensions)) {
            $arr_file = explode(".", $request->file("excel")->getClientOriginalName());
            $ext = end($arr_file);
            if ($ext === "csv") {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv;
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
            }
            $spreadsheet = $reader->load($request->file("excel")->getPathname());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            for ($i = 1; $i < count($sheetData); $i++) {
                $name = $sheetData[$i]["0"];
                $username = $sheetData[$i]["1"];
                $password = Hash::make($sheetData[$i]["2"]);
                $nomor_induk = (string) $sheetData[$i]["3"];

                if (is_numeric($sheetData[$i]["4"])) {
                    $tahunajaran = $sheetData[$i]["4"];
                    $tahun = TahunAjaran::find($tahunajaran);
                    if ($tahun == null) {
                        return redirect()->back()->with("invalid tahunajaran_id", "Gagal mengupload silahkan cek kembali file excel anda . Note : error kemungkinan terletak pada kolom tahun ajaran");
                    }
                }

                if (is_numeric($sheetData[$i]["5"])) {
                    $kelas = $sheetData[$i]["5"];
                    $valid = Kelas::find($kelas);
                    if ($valid == null) {
                        return redirect()->back()->with("invalid_idkelas", "Gagal mengupload silahkan cek kembali file excel anda . Note : error kemungkinan terletak pada kolom kelas");
                    }

                    if (empty($name) || empty($username) || empty($password) || empty($nomor_induk) || empty($tahunajaran) || empty($kelas)) {
                        return redirect()->back()->with("empty", "Kolom pada excel tidak boleh kosong!");
                    } else {
                        if (User::where("username", "==", $username)->where("nomor_induk", "==", $nomor_induk) == True) {
                            return redirect()->back()->with("username", "Username sudah ada!");
                        } else {
                            $user = User::create([
                                "name" => $name,
                                "username" => $username,
                                "password" => $password,
                                "nomor_induk" => $nomor_induk,
                                "status" => "siswa"
                            ]);
                            KelasSiswa::create([
                                "user_id" => $user->id,
                                "kelas_id" => $kelas,
                                "tahunajaran_id" => $tahunajaran
                            ]);
                        }
                    }
                } else {
                    return redirect()->back()->with("invalid_idjurusan", "Gagal mengupload silahkan cek kembali file excel anda . Note : error kemungkinan terletak pada kolom jurusan");
                }
            }
        } else {
            return redirect()->back()->with("error", "Format excel tidak bener , mohon coba kembali !");
        }


        session()->flash("users", "Berhasil menambah user");
        return redirect()->to('/akun/siswa');
    }
}
