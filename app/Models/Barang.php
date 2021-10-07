<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "barang";
    protected $guarded = ["id"];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sumber()
    {
        return $this->belongsTo(Sumber::class, "sumber_id");
    }

    public function detail()
    {
        return $this->hasMany(BarangDetail::class);
    }
}
