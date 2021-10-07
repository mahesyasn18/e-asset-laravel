<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId("admin_id");
            $table->foreign("admin_id")->references("id")->on("admins");
            $table->string("data");
            $table->string("category_data");
            $table->integer("jumlah_data_masuk")->nullable();
            $table->integer("jumlah_data_keluar")->nullable();
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
        Schema::dropIfExists('transaksi_masuk');
    }
}
