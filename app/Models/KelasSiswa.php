<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasSiswa extends Model
{
    use HasFactory;
    protected $table="kelas_siswa";
    protected $fillable = ['user_id','kelas_id','tahunajaran_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class,"tahunajaran_id");
    }

    // public function request()
    // {
    //     return $this->belongsTo(RequestModel::class);
    // }
}
