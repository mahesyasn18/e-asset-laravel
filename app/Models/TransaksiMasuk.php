<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    use HasFactory;

    protected $table = "transaksi_masuk";
    protected $fillable = ["admin_id","data","category_data","jumlah_data_masuk","jumlah_data_keluar","action","created_at","updated_at"];

    public function admin()
    {
        return $this->belongsTo(Admin::class,"admin_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class,"category_id");
    }
}
