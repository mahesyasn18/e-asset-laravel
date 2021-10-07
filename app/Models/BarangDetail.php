<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "barang_detail";
    protected $guarded = ["id"];

    public function barang()
    {
        return $this->belongsTo(Barang::class,"barang_id");
    }
    public function category()
    {
        return $this->belongsTo(Category::class,"category_id");
    }

    public function request()
    {
        return $this->belongsToMany(Request::class,"detail_request","detail_id","request_id")->withPivot("status_scan");
    }
}
