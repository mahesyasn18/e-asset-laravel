<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurusanGuru extends Model
{
    use HasFactory;
    protected $table = "jurusan_guru";
    protected $fillable = [
        "jurusan_id",
        "user_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class,"jurusan_id");
    }

    public function request()
    {
        return $this->hasMany(RequestModel::class);
    }
}
