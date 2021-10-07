<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'nomor_induk',
        'tahun_masuk',
        'jurusan_id',
        'tingkat',
        'kelas',
        'siswa',
        'status',
        'block'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, "jurusan_id");
    }

    public function request()
    {
        return $this->hasMany(RequestModel::class);
    }

    public function kelas_siswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function jurusan_guru()
    {
        return $this->hasMany(JurusanGuru::class);
    }
}
