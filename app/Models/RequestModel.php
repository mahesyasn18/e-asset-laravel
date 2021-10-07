<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;
    protected $table = "request";
    protected $guarded = ["id"];

    public function detail()
    {
        return $this->belongsToMany(BarangDetail::class, 'detail_request', 'request_id', 'detail_id')->withPivot("status_scan");
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function siswa()
    {
        return $this->belongsTo(KelasSiswa::class,"user_id","user_id");
    }

    public function guru()
    {
        return $this->belongsTo(JurusanGuru::class,"user_id","user_id");
    }
}
