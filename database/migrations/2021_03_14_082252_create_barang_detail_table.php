<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("barang_id")->unsigned();
            $table->foreign("barang_id")->references("id")->on("barang")->onDelete("cascade")->onUpdate("cascade");
            $table->bigInteger("category_id")->unsigned();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
            $table->string("kode_unik");
            $table->string("nama_barang");
            $table->integer("kode_barang");
            $table->string("status");
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
        Schema::dropIfExists('barang_detail');
    }
}
