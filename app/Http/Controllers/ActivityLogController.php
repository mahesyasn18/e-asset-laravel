<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use App\Models\Admin;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ActivityLogController extends Controller
{
    public function index()
    {
        $masuk = TransaksiMasuk::with("category", "admin")->get();
        $data = [
            "title" => "Activity Log",
            "masuk" => $masuk
        ];
        return view("admin.log.activity", $data);
    }

    public function admin_activity($id)
    {
        $masuk = TransaksiMasuk::where("admin_id", $id)->with("category", "admin")->get();
        $admin = Admin::with("jurusan")->find($id);
        $data = [
            "title" => "",
            "masuk" => $masuk,
            "admin" => $admin
        ];
        return view("admin.log.detail_activity", $data);
    }
    public function exportexcel()
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
        $spredsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 9, ' LAPORAN DATA BARANG PER ' . date("d-m-Y"));


        $activities = TransaksiMasuk::with("admin")->get();
        $sheet->setCellValue("A10", "No");
        $sheet->setCellValue("B10", "Data");
        $sheet->setCellValue("C10", "Kategori");
        $sheet->setCellValue("D10", "Admin");
        $sheet->setCellValue("E10", "Jumlah Data Masuk");
        $sheet->setCellValue("F10", "Jumlah Data Keluar");
        $sheet->setCellValue("G10", "Action");
        $sheet->setCellValue("H10", "Waktu");
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

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
        foreach ($activities as $activity) {

            $spredsheet->getActiveSheet()->getStyle('A' . $i . ':' . 'H' . $i)->applyFromArray($styleArray3);
            $sheet->setCellValue("A" . $i, $no++);
            $sheet->setCellValue("B" . $i, $activity->data);
            $sheet->setCellValue("C" . $i, $activity->category_data);
            $sheet->setCellValue("D" . $i, $activity->admin->name);
            $sheet->setCellValue("E" . $i, $activity->jumlah_data_masuk);
            $sheet->setCellValue("F" . $i, $activity->jumlah_data_keluar);
            $sheet->setCellValue("G" . $i, $activity->action);
            $sheet->setCellValue("H" . $i, $activity->created_at);
            $i++;
        }
        TransaksiMasuk::create([
            "admin_id" => auth()->guard("admin")->id(),
            "data" => "Laporan Activity Log",
            "category_data" => "Laporan",
            "action" => "Export Data Action Log Per " . date("d-m-Y")
        ]);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=laporan_Action_Log_Per" . date("d-m-Y") . ".xlsx");
        $writer = IOFactory::createWriter($spredsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
