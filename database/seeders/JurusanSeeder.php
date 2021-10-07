<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                "nama_jurusan" => "Teknik Pendingin dan Tata Udara",
                "singkatan" => "TPTU",
                "image" => "TPTU.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "nama_jurusan" => "Teknik Otomasi Industri",
                "singkatan" => "TOI",
                "image" => "TOI.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "nama_jurusan" => "Instrumentasi Otomatisasi Proses",
                "singkatan" => "IOP",
                "image" => "KP.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "nama_jurusan" => "Teknik Elektronika Daya dan Komunikasi",
                "singkatan" => "TEDK",
                "image" => "TEK.jpg",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama_jurusan" => "Teknik Elektronika Industri",
                "singkatan" => "TEI",
                "image" => "EIND.jpg",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama_jurusan" => "Sistem Informasi Jaringan dan Aplikasi",
                "singkatan" => "SIJA",
                "image" => "TKJ.jpg",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama_jurusan" => "Rekayasa Perangkat Lunak",
                "singkatan" => "RPL",
                "image" => "RPL.jpg",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama_jurusan" => "Produksi Film dan Program Televisi",
                "singkatan" => "PFPT",
                "image" => "PFPT.jpg",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama_jurusan" => "Teknik Mekatronika",
                "singkatan" => "MEKA",
                "image" => "KM.jpg",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];

        foreach ($datas as $data) {
            DB::table('jurusan')->insert($data);
        }
    }
}
