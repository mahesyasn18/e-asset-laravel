<?php

namespace App\Http\Controllers;

use App\Models\JurusanGuru;
use App\Models\KelasSiswa;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sort_users($kelas,$tahunajaran)
    {
        $kelas = (integer) $kelas;
        $tahunajaran = (integer) $tahunajaran;
        if ($kelas!=null && $tahunajaran==null) {
            return KelasSiswa::where("kelas_id",$kelas)->get();
        }
        elseif($tahunajaran!=null && $kelas==null){
            return KelasSiswa::where("tahunajaran_id",$tahunajaran)->get();
        }
        elseif($kelas!=null && $tahunajaran!=null){
            return KelasSiswa::where("kelas_id",$kelas)->where("tahunajaran_id",$tahunajaran)->get();
        }

        return null;
    }

    protected function sort_guru($jurusan){
        if ($jurusan!=null) {
            $id_jurusan = (integer) $jurusan;
            $status = "guru";
            return JurusanGuru::whereHas("user" , function($q) use ($status){
                $q->where("status",$status);
            })->where("jurusan_id",$id_jurusan)->get();
        }
        return null;
    }
}
