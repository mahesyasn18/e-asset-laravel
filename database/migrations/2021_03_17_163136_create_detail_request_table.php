<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_request', function (Blueprint $table) {
            $table->foreignId("request_id");
            $table->foreign("request_id")->references("id")->on("request")->onDelete("cascade");
            $table->foreignId("detail_id");
            $table->foreign("detail_id")->references("id")->on("barang_detail")->onUpdate("cascade")->onDelete("restrict");
            $table->string("status_scan")->nullable();
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
        Schema::dropIfExists('detail_request');
    }
}
