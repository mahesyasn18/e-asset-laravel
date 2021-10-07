<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = "jurusan";
    protected $fillable = ["nama_jurusan", "singkatan"];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
    
    public function jurusan_guru()
    {
        return $this->hasMany(JurusanGuru::class);
    }
}
