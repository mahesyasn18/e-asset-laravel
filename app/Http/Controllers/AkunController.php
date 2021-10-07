<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\User;
use App\Models\Admin;
use App\Models\TahunAjaran;
use App\Models\TransaksiMasuk;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use App\Models\RequestModel;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AkunController extends Controller
{
    public function admin()
    {
        $admins = Admin::with("jurusan")->get();
        $data = [
            "title" => "Data Akun Admin",
            "admins" => $admins
        ];
        return view("admin.akun.admin.data_admin", $data);
    }

    public function createadmin()
    {
        $jurusan = Jurusan::all();
        $data = [
            "title" => "Create Akun Admin",
            "jurusan" => $jurusan
        ];
        return view('admin.akun.admin.create_admin', $data);
    }

    public function insert_admin(Request $request)
    {
        sleep(1.5);
        $request->validate(
            [
                "name" => "required",
                "username" => "required|unique:admins,username",
                "password" => "required",
                "jurusan" => "required",
                "status" => "required"
            ],
            [
                "nama.required" => "Kolom nama harus diisi",
                "username.required" => "Kolom username harus diisi",
                "username.unique" => "Username yang di masukkan sudah digunakan",
                "password.required" => "Password wajib diisi",
                "jurusan.required" => "Jurusan wajib diisi",
                "status.required" => "Status harus diisi"
            ]
        );

        Admin::create([
            "name" => $request->name,
            "username" => $request->username,
            "password" => Hash::make($request->password),
            "jurusan_id" => $request->jurusan,
            "status" => $request->status
        ]);

        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => $request->nama,
            "category_data" => "akun",
            "jumlah_data_masuk" => 1,
            "action" => "menambah admin"
        ]);

        return redirect()->to("/akun/admin")->with("admins", "Berhasil membuat akun admin");
    }

    public function admin_edit($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            abort(404);
        } else {
            $jurusan = Jurusan::all();
            $data = [
                "title" => "Edit Data Admin",
                "admin" => $admin,
                "jurusan" => $jurusan
            ];
            return view("admin.akun.admin.akun_admin", $data);
        }
    }

    public function update_admin(Request $request, $id)
    {
        sleep(1.5);
        $request->validate(
            [
                "nama" => "required",
                "username" => "required|unique:admins,username," . $id,
                "jurusan" => "required",
                "status" => "required"
            ],
            [
                "nama.required" => "Kolom nama harus diisi",
                "username.required" => "Kolom username harus diisi",
                "username.unique" => "Username yang di masukkan sudah digunakan",
                "password.required" => "Password wajib diisi",
                "jurusan.required" => "Jurusan wajib diisi",
                "status.required" => "Status harus diisi"
            ]
        );

        Admin::where('id', $id)->update([
            "name" => $request->nama,
            "username" => $request->username,
            "jurusan_id" => $request->jurusan,
            "status" => $request->status
        ]);

        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => $request->nama,
            "category_data" => "akun",
            "action" => "mengupdate data admin"
        ]);

        return redirect()->to("/akun/admin")->with("admin-update", "Berhasil mengupdate admin");
    }

    public function admin_block($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            abort(404);
        } else {
            TransaksiMasuk::create([
                "admin_id" => auth()->guard("admin")->id(),
                "data" => $admin->name,
                "category_data" => "akun",
                "action" => "mem-block data akun admin"
            ]);
            $admin->update([
                "status" => "block"
            ]);
        }

        session()->flash('deleted', "Berhasil menghapus data admin");
        return redirect()->back()->with("deleted", "Berhasil menghapus data admin");
    }

    public function admin_unblock($id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            abort(404);
        } else {
            TransaksiMasuk::create([
                "admin_id" => auth()->guard("admin")->id(),
                "data" => $admin->name,
                "category_data" => "akun",
                "action" => "unblock data akun admin"
            ]);
            $admin->update([
                "status" => "admin"
            ]);
        }

        return redirect()->back()->with("admin-unblock", "Berhasil men-unblock admin");
    }

    public function exportuser(Request $request, $id)
    {
        $spredsheet = new Spreadsheet();
        $sheet = $spredsheet->getActiveSheet();
        $protection = $spredsheet->getActiveSheet()->getProtection();
        $protection->setPassword('smkn1cmi');
        $protection->setSheet(true);
        $protection->setSort(true);
        $protection->setInsertRows(true);
        $protection->setFormatCells(true);
        $drawing = new Drawing();
        $drawing->setName('Paid');
        $drawing->setDescription('Paid');
        $drawing->setPath(public_path('img/logo/smk.png')); // put your path and image here
        $drawing->setCoordinates('B2');
        $drawing->setWorksheet($spredsheet->getActiveSheet());

        //styling center
        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $spredsheet->getActiveSheet()->getStyle('C2:J2')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C6:J6')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C7:J7')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C8:J8')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('A1:B8')->applyFromArray($styleArray);
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
        $spredsheet->getActiveSheet()->getStyle('C1:J1')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C3:J3')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C4:J4')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C5:J5')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('A9:J9')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('A10:J10')->applyFromArray($styleArray2);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        $sheet->mergeCells('A1:B8');
        $sheet->mergeCells("C1:J1");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'PEMERINTAH PROVINSI JAWA BARAT');
        $sheet->mergeCells("C2:J2");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 2, 'DINAS PENDIDIKAN');
        $sheet->mergeCells("C3:J3");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'CABANG DINAS PENDIDIKAN WILAYAH VII');
        $sheet->mergeCells("C4:J4");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'SEKOLAH MENENGAH KEJURUAN NEGERI 1 CIMAHI');
        $sheet->mergeCells("C5:J5");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 5, '1.Teknologi & Rekayasa  2.Teknologi Informasi & Komunikasi  3.Seni & Industri Kreatif');
        $sheet->mergeCells("C6:J6");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Jln. Mahar Martanegara No. 48 Telp./Fax (022) 6629683 Leuwigajah Kota Cimahi 40533');
        $sheet->mergeCells("C7:J7");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 7, ' Website : http://www.smkn1-cmi.sch.id - e-mail : info@smkn1-cmi.sch.id');
        $sheet->mergeCells("C8:J8");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 8, ' Kota Cimahi - 40533');
        $sheet->mergeCells("A9:J9");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 9, ' LAPORAN DATA PEMINJAM BARANG PER');


        $sheet->setCellValue("A10", "No");
        $sheet->setCellValue("B10", "Nama User");
        $sheet->setCellValue("C10", "Nomor Induk");
        $sheet->setCellValue("D10", "Kelas/Jurusan");
        $sheet->setCellValue("E10", "Nama Barang");
        $sheet->setCellValue("F10", "Tahun Ajaran");
        $sheet->setCellValue("G10", "Waktu Request");
        $sheet->setCellValue("H10", "Waktu Pengembalian");
        $sheet->setCellValue("I10", "Status");
        $sheet->setCellValue("J10", "Keterangan");

        if ($request->filter != null) {
            $idfilter = (int) $request->filter;
            $requests = RequestModel::where("user_id", $id)->with("status", "tahunajaran", "siswa", "guru")
                ->where(function ($query) {
                    $query->where("status_id", 4);
                    $query->orWhere("status_id", 5);
                })
                ->where(function ($query) use ($idfilter) {
                    $query->where("tahunajaran_id", $idfilter);
                })

                ->get();
        } else {
            $requests = RequestModel::where("user_id", $id)->where(function ($query) {
                $query->where("status_id", 4);
                $query->orWhere("status_id", 5);
            })->with("status", "tahunajaran", "siswa", "guru")->get();
        }
        // style3 
        $styleArray3 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $i = 11;
        $no = 1;
        foreach ($requests as $request) {
            foreach ($request->detail as $item) {
                if ($item->id == $item->pivot->detail_id) {
                    $spredsheet->getActiveSheet()->getStyle('A' . $i . ':' . 'J' . $i)->applyFromArray($styleArray3);
                    $sheet->setCellValue("A" . $i, $no++);
                    $sheet->setCellValue("B" . $i, $request->nama_user);
                    $sheet->setCellValue("C" . $i, $request->nomor_induk);
                    if ($request->siswa != null) {
                        $sheet->setCellValue("D" . $i, $request->siswa->kelas->nama_kelas);
                    } else {
                        $sheet->setCellValue("D" . $i, $request->guru->jurusan->nama_jurusan);
                    }
                    $sheet->setCellValue("E" . $i, $item->nama_barang . "0" . $item->kode_barang);
                    $sheet->setCellValue("F" . $i, $request->tahunajaran->tahun_ajaran);
                    $sheet->setCellValue("G" . $i, $request->tanggal_request . " " . $request->waktu_request);
                    $sheet->setCellValue("H" . $i, $request->waktu_pengembalian);
                    $sheet->setCellValue("I" . $i, $request->status->keterangan_status);
                    $sheet->setCellValue("J" . $i, $request->keterangan);
                    $i++;
                }
            }
        }
        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => "Laporan Peminjam Barang",
            "category_data" => "Laporan",
            "action" => "Export Data Peminjam Barang Per "
        ]);
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=laporan_peminjaman_barang.xlsx");
        $writer = IOFactory::createWriter($spredsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
