<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use App\Models\RequestModel;
use App\Models\BarangDetail;
use Illuminate\Support\Facades\DB;
use Mumpo\FpdfBarcode\FpdfBarcode;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Exports\RequestExport;
use App\Exports\RequestExportFilter;
use App\Models\TahunAjaran;
use Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TransaksiController extends Controller
{
    public function transaksipending()
    {
        $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 1)->get();
        $data = [
            "title" => "Transaction Pending",
            "requests" => $requests
        ];
        return view('admin.transaksi.transaksi_keluar_pending', $data);
    }

    public function transaksiapprove()
    {
        $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 2)->orderBy("waktu_request")->get();
        $data = [
            "title" => "Transaction Approve",
            "requests" => $requests
        ];
        return view('admin.transaksi.transaksi_keluar_approve', $data);
    }

    public function transaksiongoing()
    {
        $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 3)->get();
        $data = [
            "title" => "Transaction Ongoing",
            "requests" => $requests
        ];
        return view('admin.transaksi.transaksi_keluar_ongoing', $data);
    }

    public function transaksicompleted(Request $request)
    {
        $tanggal_asal = $request->range;
        if ($request->range != null) {
            if ($request->filter == "filter") {
                $date = explode(" - ", $request->range);
                $first_date = date("Y-m-d", strtotime($date[0]));
                $second_date = date("Y-m-d", strtotime($date[1]));
                $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 4)->whereBetween("tanggal_request", [$first_date, $second_date])->get();
            } else {
                $tanggal_asal = null;
                return redirect()->to("/transaksi/keluar/completed");
            }
        } else {
            $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 4)->orderBy("waktu_request")->get();
        }
        $data = [
            "title" => "Transaction Completed",
            "requests" => $requests,
            "date" => $tanggal_asal,
        ];
        return view('admin.transaksi.transaksi_keluar_completed', $data);
    }

    public function transaksicancel(Request $request)
    {
        $tanggal_asal = $request->range;
        if ($request->range != null) {
            if ($request->filter == "filter") {
                $date = explode(" - ", $request->range);
                $first_date = date("Y-m-d", strtotime($date[0]));
                $second_date = date("Y-m-d", strtotime($date[1]));
                $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 5)->whereBetween("tanggal_request", [$first_date, $second_date])->get();
            } else {
                $tanggal_asal = null;
                return redirect()->to("/transaksi/keluar/cancel");
            }
        } else {
            $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 5)->orderBy("waktu_request")->get();
        }
        $data = [
            "requests" => $requests,
            "title" => "Transaction Cancel",
            "date" => $tanggal_asal,
        ];
        return view('admin.transaksi.transaksi_keluar_cancel', $data);
    }

    public function ubah_status_pending(Request $request)
    {
        sleep(1.5);
        $id = $request->id;
        $transaksi = RequestModel::find($id);
        if ($id == null) {
            abort(404);
        } else {
            if (!$transaksi) {
                abort(404);
            } else {
                $status = (int)$request->status;
                $admin_id = auth()->guard("admin")->id();
                if ($request == null) {
                    abort(404);
                } else {
                    $transaksi->update([
                        "admin_id" => $admin_id,
                        "status_id" => $status
                    ]);
                    TransaksiMasuk::create([
                        "admin_id" => $admin_id,
                        "data" => "Menerima request barang",
                        "category_data" => "request",
                        "jumlah_data_masuk" => 1,
                        "action" => "Meng-approve request barang"
                    ]);

                    session()->flash("approved", "Transaksi berhasil di approve");
                    return redirect()->to("/transaksi/keluar/approve");
                }
            }
        }
    }

    public function transaksi_refuse(Request $request)
    {
        $id = $request->id;
        $admin_id = auth()->guard("admin")->id();
        $req = RequestModel::findOrFail($id);
        $assets = $req->detail()->get();

        $updated = $req->update([
            "admin_id" => $admin_id,
            "keterangan" => $request->keterangan,
            "status_id" => 5
        ]);

        if ($updated) {
            foreach ($assets as $asset) {
                BarangDetail::find($asset->id)->update([
                    "status" => "ready"
                ]);
            }
        }

        TransaksiMasuk::create([
            "admin_id" => $admin_id,
            "data" => "Menerima request barang",
            "category_data" => "request",
            "jumlah_data_masuk" => 1,
            "action" => "Meng-refuse request barang"
        ]);

        return redirect()->to("/transaksi/keluar/cancel")->with("refused", "Berhasil me-refuse request barang");
    }

    public function scan_barang_approve(Request $request)
    {
        $request->validate([
            "kode_unik" => "required"
        ]);
        $id_request = $request->request_id;
        $id = $request->id;
        $kode_unik = $request->kode_unik;
        $detail = BarangDetail::where("id", $id)
            ->where("kode_unik", $kode_unik)->first();
        if ($detail == null) {
            return redirect()->back()->with("invalid", "Kode barang yang anda masukkan salah , silahkan cek kembali");
        } else {
            $transaksi = RequestModel::find($id_request);
            $transaksi->detail()->updateExistingPivot($id, [
                "status_scan" => "scanned"
            ]);

            return redirect()->back()->with("success", "Berhasil melakukan scan barang");
        }
    }

    public function ubah_status_approve(Request $request)
    {
        $id = $request->id;
        $transaksi = RequestModel::find($id);
        $scanned = $transaksi->detail()->wherePivot("status_scan", null)->get();
        if (count($scanned) != 0) {
            return redirect()->back()->with("not_scan", "Silahkan scan barang terlebih dahulu");
        } else {
            $nomor = $request->nomor_induk;
            $data = RequestModel::where("id", $id)
                ->where("nomor_induk", $nomor)->get();

            if (count($data) == 0) {
                return redirect()->back()->with("not_found", "Scan gagal data tidak ditemukkan , silahkan cek kembali nomor induk yang dimasukan dan coba lagi!");
            } else {
                RequestModel::where("id", $id)->update([
                    "kode_invoice" => rand(10000000, 20000000),
                    "status_id" => 3
                ]);
                TransaksiMasuk::create([
                    "admin_id" => auth()->guard("admin")->id(),
                    "data" => "Merubah status request barang",
                    "category_data" => "request",
                    "jumlah_data_masuk" => 1,
                    "action" => "Merubah status request barang menjadi ongoing"
                ]);

                return redirect()->to("/transaksi/keluar/ongoing")->with("scan_completed", "Transaksi berhasil di proses!");
            }
        }
    }

    public function ubah_status_ongoing(Request $request)
    {
        $request->validate([
            "kode_invoice" => "required"
        ]);
        $kode_invoice = $request->kode_invoice;

        $data = RequestModel::where("kode_invoice", $kode_invoice)->first();
        if ($data == null) {
            return redirect()->back()->with("wrong", "Kode Invoice yang anda masukkan tidak dapat ditemukkan di sistem");
        } else {
            $check = $data->detail()->wherePivot("status_scan", "scanned")->get();
            if (count($check) != 0) {
                return redirect()->back()->with("not_scanned", "Silahkan scan barang terlebih dahulu untuk melanjutkan proses ini");
            } else {
                $data->update([
                    "status_id" => 4,
                    "waktu_pengembalian" => date("Y-m-d H:i:s")
                ]);

                return redirect()->to("/transaksi/keluar/completed")->with('success', "Berhasil melakukan scan");
            }
            TransaksiMasuk::create([
                "admin_id" => auth()->guard("admin")->id(),
                "data" => "Merubah status request barang",
                "category_data" => "request",
                "jumlah_data_masuk" => 1,
                "action" => "Merubah status request barang menjadi completed"
            ]);
            return redirect()->back()->with("barang_scanned", "Berhasil melakukan scan barang");
        }
    }

    public function scan_barang_ongoing(Request $request)
    {
        $request->validate([
            "kode_unik" => "required"
        ]);

        $id = $request->id;
        $kode_unik = $request->kode_unik;
        $detail = BarangDetail::where("id", $id)
            ->where("kode_unik", $kode_unik)->first();
        if ($detail == null) {
            return redirect()->back()->with("invalid", "Kode barang yang anda masukkan salah , silahkan cek kembali");
        } else {
            $detail->update([
                "status" => "ready"
            ]);
            $request_id = $request->request_id;
            $req = RequestModel::find($request_id);

            $req->detail()->updateExistingPivot($id, [
                "status_scan" => "scan_kembali"
            ]);

            return redirect()->back()->with("barang_scanned", "Berhasil melakukan scan barang");
        }
    }

    public function invoice_print($id)
    {

        $req = RequestModel::with('admin')->find($id);
        $admin = $req->admin;
        $barang = $req->detail()->get();
        $pdf = new FpdfBarcode();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $no = 1;
        $pdf->Cell(70, 10, "E-ASSET");
        $pdf->Cell(120, 10, "                                        Invoice #$req->kode_invoice");
        $pdf->Ln();
        $code = 'CODE 128';
        $pdf->Code128(133, 20, "$req->kode_invoice", 70, 15);
        $pdf->SetXY(133, 38);
        $pdf->Write(5, "             $req->kode_invoice");
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->setFont("Arial", "B", "12");
        $pdf->Cell(48, 8, "Nama");
        $pdf->Cell(50, 8, ":  $req->nama_user");
        $pdf->Ln();
        $pdf->Cell(48, 8, "Nomor Induk");
        $pdf->Cell(50, 8, ": $req->nomor_induk");
        $pdf->Ln();
        $pdf->Cell(48, 8, "Jumlah Barang");
        $pdf->Cell(50, 8, ": " . count($barang));
        $pdf->Ln();
        $pdf->Cell(48, 8, "Handle By");
        $pdf->Cell(50, 8, ":  $admin->name");
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 10, "Data Peminjaman Barang");
        $pdf->Ln();
        $pdf->setFont("Arial", "B", "12");
        $pdf->Cell(30, 6, '#', 1, 0, "C");
        $pdf->Cell(115, 6, 'Nama Barang', 1, 0, "C");
        $pdf->Cell(45, 6, 'Kode Barang', 1, 1, "C");
        foreach ($barang as $b) {
            $pdf->Cell(30, 10, "$no", 1, 0, "C");
            $pdf->Cell(115, 10, "$b->nama_barang 0$b->kode_barang", 1, 0, "C");
            $pdf->Cell(45, 10, "$b->kode_unik", 1, 1, "C");
            $no++;
        }
        $req->update([
            "status_invoice" => "printed"
        ]);

        return $pdf->Output("Invoice#" . $req->kode_invoice . "-" . "$req->nama_user" . "-" .  date("Y-m-d") . ".pdf", "D");
    }
    public function export_to_Excel(Request $request)
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

        $spredsheet->getActiveSheet()->getStyle('C2:G2')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C6:G6')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C7:G7')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C8:G8')->applyFromArray($styleArray);
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
        $spredsheet->getActiveSheet()->getStyle('C1:G1')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C3:G3')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C4:G4')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C5:G5')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('A9:G9')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('A10:G10')->applyFromArray($styleArray2);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        $sheet->mergeCells('A1:B8');
        $sheet->mergeCells("C1:G1");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'PEMERINTAH PROVINSI JAWA BARAT');
        $sheet->mergeCells("C2:G2");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 2, 'DINAS PENDIDIKAN');
        $sheet->mergeCells("C3:G3");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'CABANG DINAS PENDIDIKAN WILAYAH VII');
        $sheet->mergeCells("C4:G4");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'SEKOLAH MENENGAH KEJURUAN NEGERI 1 CIMAHI');
        $sheet->mergeCells("C5:G5");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 5, '1.Teknologi & Rekayasa  2.Teknologi Informasi & Komunikasi  3.Seni & Industri Kreatif');
        $sheet->mergeCells("C6:G6");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Jln. Mahar Martanegara No. 48 Telp./Fax (022) 6629683 Leuwigajah Kota Cimahi 40533');
        $sheet->mergeCells("C7:G7");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 7, ' Website : http://www.smkn1-cmi.sch.id - e-mail : info@smkn1-cmi.sch.id');
        $sheet->mergeCells("C8:G8");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 8, ' Kota Cimahi - 40533');
        $sheet->mergeCells("A9:G9");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 9, ' LAPORAN DATA PEMINJAM BARANG PER ' . date("d-m-Y"));
        if ($request->filter != null) {
            $date = explode(" - ", $request->filter);
            $first_date = date("Y-m-d", strtotime($date[0]));
            $second_date = date("Y-m-d", strtotime($date[1]));
            $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 4)->whereBetween("tanggal_request", [$first_date, $second_date])->get();
        } else {
            $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 4)->orderBy("waktu_request")->get();
        }

        $sheet->setCellValue("A10", "No");
        $sheet->setCellValue("B10", "Nama User");
        $sheet->setCellValue("C10", "Nomor Induk");
        $sheet->setCellValue("D10", "Kelas/Jurusan");
        $sheet->setCellValue("E10", "Nama Barang");
        $sheet->setCellValue("F10", "Waktu Request");
        $sheet->setCellValue("G10", "Waktu Pengembalian");


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
                    $spredsheet->getActiveSheet()->getStyle('A' . $i . ':' . 'G' . $i)->applyFromArray($styleArray3);
                    $sheet->setCellValue("A" . $i, $no++);
                    $sheet->setCellValue("B" . $i, $request->nama_user);
                    $sheet->setCellValue("C" . $i, $request->nomor_induk);
                    if ($request->siswa != null) {
                        $sheet->setCellValue("D" . $i, $request->siswa->kelas->nama_kelas);
                    } else {
                        $sheet->setCellValue("D" . $i, $request->guru->jurusan->nama_jurusan);
                    }
                    $sheet->setCellValue("E" . $i, $item->nama_barang . "0" . $item->kode_barang);
                    $sheet->setCellValue("F" . $i, $request->tanggal_request . " " . $request->waktu_request);
                    $sheet->setCellValue("G" . $i, $request->waktu_pengembalian);
                    $i++;
                }
            }
        }

        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => "Laporan Peminjam Barang",
            "category_data" => "Laporan",
            "action" => "Export Data Peminjam Barang Per " . date("d-m-Y")
        ]);
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=laporan_peminjaman_barang.xlsx");
        $writer = IOFactory::createWriter($spredsheet, 'Xlsx');
        $writer->save('php://output');
        //return Excel::download(new RequestExport, "Laporan Peminjaman Barang.xlsx");
    }
    public function export_Excel(Request $request)
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

        $spredsheet->getActiveSheet()->getStyle('C2:H2')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C6:H6')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C7:H7')->applyFromArray($styleArray);
        $spredsheet->getActiveSheet()->getStyle('C8:H8')->applyFromArray($styleArray);
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
        $spredsheet->getActiveSheet()->getStyle('C1:H1')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C3:H3')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C4:H4')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('C5:H5')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('A9:H9')->applyFromArray($styleArray2);
        $spredsheet->getActiveSheet()->getStyle('A10:H10')->applyFromArray($styleArray2);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        $sheet->mergeCells('A1:B8');
        $sheet->mergeCells("C1:H1");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'PEMERINTAH PROVINSI JAWA BARAT');
        $sheet->mergeCells("C2:H2");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 2, 'DINAS PENDIDIKAN');
        $sheet->mergeCells("C3:H3");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 3, 'CABANG DINAS PENDIDIKAN WILAYAH VII');
        $sheet->mergeCells("C4:H4");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'SEKOLAH MENENGAH KEJURUAN NEGERI 1 CIMAHI');
        $sheet->mergeCells("C5:H5");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 5, '1.Teknologi & Rekayasa  2.Teknologi Informasi & Komunikasi  3.Seni & Industri Kreatif');
        $sheet->mergeCells("C6:H6");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Jln. Mahar Martanegara No. 48 Telp./Fax (022) 6629683 Leuwigajah Kota Cimahi 40533');
        $sheet->mergeCells("C7:H7");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 7, ' Website : http://www.smkn1-cmi.sch.id - e-mail : info@smkn1-cmi.sch.id');
        $sheet->mergeCells("C8:H8");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(3, 8, ' Kota Cimahi - 40533');
        $sheet->mergeCells("A9:H9");
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 9, ' LAPORAN DATA PENOLAKAN PEMINJAM BARANG PER ' . date("d-m-Y"));
        if ($request->filter != null) {
            $date = explode(" - ", $request->filter);
            $first_date = date("Y-m-d", strtotime($date[0]));
            $second_date = date("Y-m-d", strtotime($date[1]));
            $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 5)->whereBetween("tanggal_request", [$first_date, $second_date])->get();
        } else {
            $requests = RequestModel::with("status", "siswa", "guru")->where("status_id", 5)->orderBy("waktu_request")->get();
        }

        $sheet->setCellValue("A10", "No");
        $sheet->setCellValue("B10", "Nama User");
        $sheet->setCellValue("C10", "Nomor Induk");
        $sheet->setCellValue("D10", "Kelas/Jurusan");
        $sheet->setCellValue("E10", "Nama Barang");
        $sheet->setCellValue("F10", "Waktu Request");
        $sheet->setCellValue("G10", "Status");
        $sheet->setCellValue("H10", "Keterangan");

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
                    $spredsheet->getActiveSheet()->getStyle('A' . $i . ':' . 'H' . $i)->applyFromArray($styleArray3);
                    $sheet->setCellValue("A" . $i, $no++);
                    $sheet->setCellValue("B" . $i, $request->nama_user);
                    $sheet->setCellValue("C" . $i, $request->nomor_induk);
                    if ($request->siswa != null) {
                        $sheet->setCellValue("D" . $i, $request->siswa->kelas->nama_kelas);
                    } else {
                        $sheet->setCellValue("D" . $i, $request->guru->jurusan->nama_jurusan);
                    }
                    $sheet->setCellValue("E" . $i, $item->nama_barang . "0" . $item->kode_barang);
                    $sheet->setCellValue("F" . $i, $request->tanggal_request . " " . $request->waktu_request);
                    $sheet->setCellValue("G" . $i, $request->status->keterangan_status);
                    $sheet->setCellValue("H" . $i, $request->keterangan);
                    $i++;
                }
            }
        }

        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => "Laporan Peminjam Barang",
            "category_data" => "Laporan",
            "action" => "Export Data Peminjam Barang Per " . date("d-m-Y")
        ]);
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=laporan_penolakan_peminjaman_barang" . date("d-m-Y") . ".xlsx");
        $writer = IOFactory::createWriter($spredsheet, 'Xlsx');
        $writer->save('php://output');
        //return Excel::download(new RequestExport, "Laporan Peminjaman Barang.xlsx");
    }
}
