<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Sumber;
use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\TransaksiMasuk;
use FFI;
use Illuminate\Support\Facades\DB;
use Mumpo\FpdfBarcode\FpdfBarcode;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AssetController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $sumber = Sumber::all();
        $data = [
            "title" => "Tambah Barang",
            "categories" => $categories,
            "sumber" => $sumber
        ];
        return view("admin.barang.create", $data);
    }

    public function category_insert(Request $request)
    {
        $request->validate([
            'nama_kategori' => "required|unique:categories"
        ]);

        Category::create([
            "nama_kategori" => $request->nama_kategori
        ]);
        session()->flash("success", "Berhasil menambah kategori");
        return redirect()->back();
    }

    public function category_destroy($id)
    {
        $categori = Category::findOrFail($id);
        if (!$categori) {
            abort(404);
        } else {
            $categori::where('id', $id)->delete();
        }

        session()->flash("deleted", "Berhasil menghapus kategori");
        return redirect()->back();
    }

    public function insert(Request $request)
    {
        sleep(1.5);
        $request->validate(
            [
                "nama_barang" => "required|unique:barang",
                "category" => "required",
                "stok" => "required|numeric",
                "sumber" => "required",
                "penyimpanan" => "required",
                "tanggal_masuk" => "required",
                "harga_satuan" => "required|numeric"
            ],
            [
                "nama_barang.required" => "Field nama barang tidak boleh kosong",
                "category.required" => "Category harus di pilih terlebih dahulu",
                "stok.required" => "Stok harus diisi",
                "stok.numeric" => "Stok harus berupa angka",
                "sumber.required" => "Sumber field harus di pilih terlebih dahulu",
                "penyimpanan.required" => "Penyimpanan harus diisi",
                "tanggal_masuk.required" => "Tanggal Masuk harus diisi",
                "harga_satuan.required" => "Field harga satuan harus  diisi",
                "harga_satuan.numeric" => "Field harga satuan harus berupa angka"
            ]
        );

        $total = $request->harga_satuan * $request->stok;
        $datetime = $request->tanggal_masuk . " " . date("H:i:s");
        $barang = Barang::create([
            "admin_id" => $request->id_admin,
            "nama_barang" => $request->nama_barang,
            "category_id" => $request->category,
            "stok" => $request->stok,
            "sumber_id" => $request->sumber,
            "penyimpanan" => $request->penyimpanan,
            "harga_satuan" => $request->harga_satuan,
            "total" => $total,
            "created_at" => $datetime
        ]);

        $stok = $request->stok;
        if ($barang) {
            $last_id = $barang->id;
            for ($i = 1; $i <= $stok; $i++) {
                BarangDetail::create([
                    "barang_id" => $last_id,
                    "category_id" => $request->category,
                    "kode_unik" => rand(100000, 700000),
                    "nama_barang" => $request->nama_barang,
                    "kode_barang" => $i,
                    "status" => "ready",
                    "created_at" => $datetime
                ]);
            }

            TransaksiMasuk::create([
                "admin_id" => auth()->guard("admin")->id(),
                "data" => $request->nama_barang,
                "category_data" => "barang",
                "jumlah_data_masuk" => $request->stok,
                "action" => "input data barang"
            ]);
        }


        session()->flash("success", "Berhasil tambah barang");
        return redirect()->to("/dashboard");
    }

    public function show($id)
    {
        $details = BarangDetail::with("category", "barang")->where("barang_id", $id)->get();
        if (count($details) == 0) {
            abort(404);
        }
        $data = [
            "title" => "Detail Barang",
            "details" => $details,
            "id" => $id
        ];
        return view("admin.barang.detail", $data);
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        $categories = Category::all();
        $sumber = Sumber::all();
        if (!$barang) {
            abort(404);
        }

        $data = [
            "title" => "Edit Barang " . $barang->nama_barang,
            "barang" => $barang,
            "categories" => $categories,
            "sumber" => $sumber
        ];
        return view("admin.barang.edit", $data);
    }

    public function update(Request $request, $id)
    {
        sleep(1.5);
        $request->validate(
            [
                "nama_barang" => "required|unique:barang",
                "category" => "required",
                "stok" => "required|numeric",
                "sumber" => "required",
                "penyimpanan" => "required",
                "tanggal_masuk" => "required",
                "harga_satuan" => "required|numeric"
            ],
            [
                "nama_barang.required" => "Field nama barang tidak boleh kosong",
                "category.required" => "Category harus di pilih terlebih dahulu",
                "stok.required" => "Stok harus diisi",
                "stok.numeric" => "Stok harus berupa angka",
                "sumber.required" => "Sumber field harus di pilih terlebih dahulu",
                "penyimpanan.required" => "Penyimpanan harus diisi",
                "tanggal_masuk.required" => "Tanggal Masuk harus diisi",
                "harga_satuan.required" => "Field harga satuan harus  diisi",
                "harga_satuan.numeric" => "Field harga satuan harus berupa angka"
            ]
        );
        $barang = Barang::find($id);
        if ($barang == null) {
            abort(404);
        } else {
            if ($request->stok >= $barang->stok) {
                $total = $request->harga_satuan * $request->stok;
                $datetime = $request->tanggal_masuk . " " . date("H:i:s");
                $last_category = $barang->category_id;
                $last_created = $barang->created_at;
                $last_stok = $barang->stok;
                $stok = (int)$request->stok;
                $update = $barang->update([
                    "admin_id" => $request->id_admin,
                    "nama_barang" => $request->nama_barang,
                    "category_id" => $request->category,
                    "stok" => $request->stok,
                    "sumber_id" => $request->sumber,
                    "penyimpanan" => $request->penyimpanan,
                    "harga_satuan" => $request->harga_satuan,
                    "total" => $total,
                    "created_at" => $datetime
                ]);
                if ($request->category != $last_category) {
                    BarangDetail::where("barang_id", $id)->update([
                        "category_id" => $request->category
                    ]);
                } elseif ($request->tanggal_masuk != $last_created) {
                    BarangDetail::where("barang_id", $id)->update([
                        "created_at" => $datetime
                    ]);
                }

                if ($stok != $last_stok) {
                    $last_code = BarangDetail::where('barang_id', $id)->latest("kode_barang")->first();
                    $code = $last_code->kode_barang;
                    $new_stok = $stok - $last_stok;
                    for ($i = 1; $i <= $new_stok; $i++) {
                        $new_code = $code + $i;
                        BarangDetail::create([
                            "barang_id" => $id,
                            "category_id" => $request->category,
                            "kode_unik" => rand(100000, 700000),
                            "nama_barang" => $request->nama_barang,
                            "kode_barang" => $new_code,
                            "status" => "ready",
                            "created_at" => $datetime
                        ]);
                    }

                    TransaksiMasuk::create([
                        "admin_id" => auth()->guard("admin")->id(),
                        "data" => $request->nama_barang,
                        "category_data" => "barang",
                        "jumlah_data_masuk" => $new_stok,
                        "action" => "mengupdate data barang"
                    ]);
                } else {
                    TransaksiMasuk::create([
                        "admin_id" => auth()->guard("admin")->id(),
                        "data" => $request->nama_barang,
                        "category_data" => "barang",
                        "action" => "mengupdate data barang"
                    ]);
                }



                session()->flash("updated", "Berhasil mengupdate barang");
                return redirect()->to("/dashboard");
            } else {
                session()->flash("failed", "Gagal mengupdate barang , stok tidak valid");
                return redirect()->back();
            }
        }
    }

    public function detail_status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $detail = BarangDetail::find($id);
        if (!$detail) {
            abort(404);
        } else {
            if ($status == "ready") {
                BarangDetail::where("id", $id)->update([
                    "status" => "rusak"
                ]);
            } else {
                BarangDetail::where("id", $id)->update([
                    "status" => "ready"
                ]);
            }
        }

        session()->flash("changed", "Status berhasil di rubah");
        return redirect()->back();
    }

    public function insert_excel(Request $request)
    {
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
                $nama_barang = $sheetData[$i]["1"];
                if (is_numeric($sheetData[$i]["2"])) {
                    $kategori_id = $sheetData[$i]["2"];
                    $kategori = Category::find($kategori_id);
                    if (!$kategori) {
                        return redirect()->back()->with("invalid_category", "Kategori yang anda masukkan tidak ada , silahkan tambah kategori terlebih dahulu");
                    }
                    $stok = $sheetData[$i]["3"];
                    $sumber = $sheetData[$i]["4"];
                    $data = Sumber::where("nama_sumber", $sumber)->first();
                    if ($data == null) {
                        $new_sumber = Sumber::create([
                            "nama_sumber" => $sumber
                        ]);
                        $sumber = $new_sumber->id;
                    } else {
                        $sumber = $data->id;
                    }
                    $penyimpanan = $sheetData[$i]["5"];
                    $tanggal = date("Y-m-d", strtotime($sheetData[$i]["6"]));
                    $tanggal_masuk = $tanggal . " " . date("H:i:s");
                    $harga_satuan = (int) $sheetData[$i]["7"];
                    $total = $stok * $harga_satuan;

                    if (empty($nama_barang) || empty($kategori_id) || empty($stok) || empty($sumber) || empty($penyimpanan) || empty($tanggal_masuk) || empty($harga_satuan)) {
                        return redirect()->back()->with("empty", "Semua kolom harus diisi!");
                    } else {
                        if (Barang::where("nama_barang", $nama_barang)->first()) {
                            return redirect()->back()->with("empty", "Nama barang sudah Ada");
                        } else {
                            $barang = Barang::create([
                                "admin_id" => auth()->guard("admin")->id(),
                                "nama_barang" => $nama_barang,
                                "category_id" => $kategori->id,
                                "stok" => $stok,
                                "sumber_id" => $sumber,
                                "penyimpanan" => $penyimpanan,
                                "harga_satuan" => $harga_satuan,
                                "total" => $total,
                                "created_at" => $tanggal_masuk
                            ]);
                        }

                        if ($barang) {
                            for ($x = 1; $x <= $stok; $x++) {
                                DB::table('barang_detail')->insert([
                                    "barang_id" => $barang->id,
                                    "category_id" => $kategori->id,
                                    "kode_unik" => rand(100000, 700000),
                                    "nama_barang" => $nama_barang,
                                    "kode_barang" => $x,
                                    "status" => "ready",
                                    "created_at" => $tanggal_masuk
                                ]);
                            }
                        }
                    }
                } else {
                    return redirect()->back()->with("invalid_kategori", "Kesalahan format pada salah satu kolom . Note : Kemungkinan kesalahan pada kolom kategori");
                }
            }
            return redirect()->to("/dashboard")->with("success", "Berhasil tambah barang");
        } else {
            return redirect()->back()->with('invalid_file', "Format file yang anda masukkan tidak cocok!");
        }
    }

    public function excel_eksport()
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
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 9, ' LAPORAN DATA BARANG PER ' . date("d-m-Y"));


        $barang = Barang::with("sumber", "admin", "category")->get();
        $sheet->setCellValue("A10", "No");
        $sheet->setCellValue("B10", "Nama Barang");
        $sheet->setCellValue("C10", "Kategori");
        $sheet->setCellValue("D10", "Stok");
        $sheet->setCellValue("E10", "Input By");
        $sheet->setCellValue("F10", "Sumber Barang");
        $sheet->setCellValue("G10", "Penyimpanan");
        $sheet->setCellValue("H10", "Waktu Input");
        $sheet->setCellValue("I10", "Harga Barang Satuan");
        $sheet->setCellValue("J10", "Jumlah Harga");
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



        // style3 
        $styleArray3 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ],
        ];
        $i = 11;
        $no = 1;
        foreach ($barang as $item) {

            $spredsheet->getActiveSheet()->getStyle('A' . $i . ':' . 'J' . $i)->applyFromArray($styleArray3);
            $sheet->setCellValue("A" . $i, $no++);
            $sheet->setCellValue("B" . $i, $item->nama_barang);
            $sheet->setCellValue("C" . $i, $item->category->nama_kategori);
            $sheet->setCellValue("D" . $i, $item->stok);
            $sheet->setCellValue("E" . $i, $item->admin->name);
            $sheet->setCellValue("F" . $i, $item->sumber->nama_sumber);
            $sheet->setCellValue("G" . $i, $item->penyimpanan);
            $sheet->setCellValue("H" . $i, $item->created_at);
            $spredsheet->getActiveSheet()->getStyle("I" . $i)->getNumberFormat()->setFormatCode('Rp' . '#,##0');
            $sheet->setCellValue("I" . $i, $item->harga_satuan);
            $spredsheet->getActiveSheet()->getStyle("J" . $i)->getNumberFormat()->setFormatCode('Rp' . '#,##0');
            $sheet->setCellValue("J" . $i, $item->total);
            $i++;
        }
        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => "Laporan Data Barang",
            "category_data" => "Laporan",
            "action" => "Export Data Barang Per " . date("d-m-Y")
        ]);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=laporan_stok_barang.xlsx");
        $writer = IOFactory::createWriter($spredsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function pdf_eksport()
    {
        $barang = Barang::with("sumber", "admin", "category")->get();
        $fpdf = new FpdfBarcode('L', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetFont('Arial', 'B', 16);
        $no = 1;
        $fpdf->Image(public_path('img/logo/smk.png'), 10, 10, 33, 33);
        $fpdf->Cell(25);
        $fpdf->SetFont('Times', 'B', '12');
        $fpdf->Cell(0, 5, "PEMERINTAH PROVINSI JAWA BARAT", 0, 1, 'C');
        $fpdf->Cell(25);
        $fpdf->Cell(0, 5, "DINAS PENDIDIKAN", 0, 1, 'C');
        $fpdf->Cell(25);
        $fpdf->SetFont('Times', 'B', '12');
        $fpdf->Cell(0, 5, "CABANG DINAS PENDIDIKAN WILAYAH VII", 0, 1, 'C');
        $fpdf->Cell(25);
        $fpdf->SetFont('Times', 'B', '15');
        $fpdf->Cell(0, 5, "SEKOLAH MENENGAH KEJURUAN NEGERI 1 CIMAHI", 0, 1, 'C');
        $fpdf->Cell(25);
        $fpdf->SetFont('Times', 'B', '8');
        $fpdf->Cell(0, 5, "1.Teknologi & Rekayasa  2.Teknologi Informasi & Komunikasi  3.Seni & Industri Kreatif", 0, 1, 'C');
        $fpdf->Cell(25);
        $fpdf->SetFont('Times', 'I', '8');
        $fpdf->Cell(0, 5, "Jln. Mahar Martanegara No. 48 Telp./Fax (022) 6629683 ", 0, 1, 'C');
        $fpdf->Cell(25);
        $fpdf->Cell(0, 2,  "Website : http://www.smkn1-cmi.sch.id - e-mail : info@smkn1-cmi.sch.id", 0, 1, 'C');
        $fpdf->SetLineWidth(1);
        $fpdf->Line(10, 45, 285, 45);
        $fpdf->SetLineWidth(0);
        $fpdf->Line(10, 46, 285, 46);
        $fpdf->ln();
        $fpdf->ln();
        $fpdf->ln();
        $fpdf->ln();
        $fpdf->ln();
        $fpdf->ln();
        $fpdf->SetFont('Times', 'B', '15');
        $fpdf->Cell(0, 5, "Laporan Data Barang", 0, 1, 'C');
        $fpdf->SetLineWidth(0);
        $fpdf->Line(120, 61, 178, 61);
        $fpdf->ln();
        $fpdf->ln();
        $fpdf->ln();
        $fpdf->setFont("Arial", "B", "9");
        $fpdf->CellFitSpace(10, 6, 'No', 1, 0, "C");
        $fpdf->CellFitSpace(40, 6, 'Nama Barang', 1, 0, "C");
        $fpdf->CellFitSpace(30, 6, 'Kategori', 1, 0, "C");
        $fpdf->CellFitSpace(15, 6, 'stok', 1, 0, "C");
        $fpdf->CellFitSpace(30, 6, 'Input By', 1, 0, "C");
        $fpdf->CellFitSpace(30, 6, 'Sumber Barang', 1, 0, "C");
        $fpdf->CellFitSpace(30, 6, 'Penyimpanan', 1, 0, "C");
        $fpdf->CellFitSpace(30, 6, 'Waktu Input', 1, 0, "C");
        $fpdf->CellFitSpace(30, 6, 'Harga Satuan', 1, 0, "C");
        $fpdf->CellFitSpace(30, 6, 'Harga Total', 1, 1, "C");

        foreach ($barang as $b) {
            $fpdf->CellFitSpace(10, 8, "$no", 1, 0, "C");
            $fpdf->CellFitSpace(40, 8, "$b->nama_barang", 1, 0, "C");
            $fpdf->CellFitSpace(30, 8, $b->category->nama_kategori, 1, 0, "C");
            $fpdf->CellFitSpace(15, 8, "$b->stok", 1, 0, "C");
            $fpdf->CellFitSpace(30, 8, $b->admin->name, 1, 0, "C");
            $fpdf->CellFitSpace(30, 8, $b->sumber->nama_sumber, 1, 0, "C");
            $fpdf->CellFitSpace(30, 8, $b->penyimpanan, 1, 0, "C");
            $fpdf->CellFitSpace(30, 8, $b->created_at, 1, 0, "C");
            $fpdf->CellFitSpace(30, 8, 'Rp. ' . number_format($b->harga_satuan, 0, ",", ".") . ',-', 1, 0, "C");
            $fpdf->CellFitSpace(30, 8, 'Rp. ' . number_format($b->total, 0, ",", ".") . ',-', 1, 1, "C");
            $no++;
        }

        return $fpdf->Output('Export Data Barang Per ' . date("d-m-Y") . ".pdf", 'D');
    }
    public function barcode(Request $request, $id)
    {
        $id = $request->barcode;
        $detail = BarangDetail::where("barang_id", $id)->get();
        $fpdf = new FpdfBarcode();
        $fpdf->AddPage();
        $a = 11;
        $b = 20;
        $c = 40;
        $d = 20;
        $e = 69;
        $f = 20;
        $g = 98;
        $h = 20;
        $i = 127;
        $j = 20;
        $k = 156;
        $l = 20;
        $m = 185;
        $n = 20;
        $o = 214;
        $p = 20;

        for ($i = 0; $i < count($id); $i++) {
            $detail = BarangDetail::find($id[$i]);
            if ($a == 11 && $b <= 280) {
                $fpdf->i25($a, $b, "$detail->kode_unik");
                $fpdf->Ln();
                $a += 0;
                $b += 18;
            } elseif ($c == 40 && $d <= 280) {
                $fpdf->i25($c, $d, "$detail->kode_unik");
                $fpdf->Ln();
                $c += 0;
                $d += 18;
            } elseif ($e == 69 && $f <= 280) {
                $fpdf->i25($e, $f, "$detail->kode_unik");
                $fpdf->Ln();
                $e += 0;
                $f += 18;
            } elseif ($g == 98 && $h <= 280) {
                $fpdf->i25($g, $h, "$detail->kode_unik");
                $fpdf->Ln();
                $g += 0;
                $h += 18;
            } elseif ($i == 127 && $j <= 280) {
                $fpdf->i25($i, $j, "$detail->kode_unik");
                $fpdf->Ln();
                $i += 0;
                $j += 18;
            } elseif ($k == 156 && $l <= 280) {
                $fpdf->i25($k, $l, "$detail->kode_unik");
                $fpdf->Ln();
                $k += 0;
                $l += 18;
            } elseif ($m == 185 && $n <= 280) {
                $fpdf->i25($m, $n, "$detail->kode_unik");
                $fpdf->Ln();
                $m += 0;
                $n += 18;
            } elseif ($o == 214 && $p <= 280) {
                $fpdf->i25($o, $p, "$detail->kode_unik");
                $fpdf->Ln();
                $o += 0;
                $p += 18;
            }
        }

        return $fpdf->Output();
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        if ($barang == null) {
            abort(404);
        } else {
            TransaksiMasuk::create([
                "admin_id" => auth()->guard("admin")->id(),
                "data" => $barang->nama_barang,
                "category_data" => "barang",
                "jumlah_data_keluar" => $barang->stok,
                "action" => "menghapus data barang"
            ]);
            $barang->delete();
        }

        session()->flash("deleted", "Berhasil menghapus barang");
        return redirect()->back();
    }
}
