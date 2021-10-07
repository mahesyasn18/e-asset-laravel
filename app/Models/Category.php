<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ["nama_kategori"];

    public function barang()
    {
        return $this->hasOne(Barang::class);
    }
    public function barang_detail()
    {
        return $this->hasOne(BarangDetail::class);
    }

    public function transaksi_masuk()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }
}
