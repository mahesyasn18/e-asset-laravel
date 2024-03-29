<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = "kelas";
    protected $fillable = ["nama_kelas", "jurusan_id"];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function kelas_siswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }
}
