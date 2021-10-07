<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreignId("admin_id")->nullable();
            $table->foreign("admin_id")->references("id")->on("admins");
            $table->string("nomor_induk");
            $table->string("nama_user");
            $table->string("kode_invoice")->nullable();
            $table->foreignId("status_id");
            $table->foreign("status_id")->references("id")->on("status");
            $table->string("status_invoice")->nullable();
            $table->string("keterangan")->nullable();
            $table->foreignId("tahunajaran_id");
            $table->foreign("tahunajaran_id")->references("id")->on("tahun_ajaran");
            $table->date("tanggal_request");
            $table->time("waktu_request");
            $table->dateTime("waktu_pengembalian")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request');
    }
}
