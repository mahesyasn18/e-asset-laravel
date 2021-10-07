<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("admin_id")->unsigned();
            $table->foreign("admin_id")->references("id")->on("admins")->onDelete("cascade");
            $table->string("nama_barang");
            $table->bigInteger("category_id")->unsigned();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
            $table->integer("stok");
            $table->bigInteger("sumber_id")->unsigned();
            $table->foreign("sumber_id")->references("id")->on("sumber")->onDelete("cascade")->onUpdate("cascade");
            $table->string("penyimpanan");
            $table->bigInteger("harga_satuan");
            $table->bigInteger("total");
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
        Schema::dropIfExists('barang');
    }
}
