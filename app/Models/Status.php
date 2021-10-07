<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table="status";
    protected $fillable = ["keterangan_status","created_at","updated_at"];

    public function request()
    {
        return $this->hasOne(RequestModel::class);
    }
}
