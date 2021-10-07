<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sumber extends Model
{
    use HasFactory;

    protected $table="sumber";
    protected $fillable = ["nama_sumber"];

    public function barang()
    {
        return $this->hasOne(Barang::class);
    }
}
