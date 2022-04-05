<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('land_line', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('land_id');
            $table->unsignedBigInteger('line_id');
            $table->string('bukken_num');
            $table->string('line_cd',6)->nullable()->index();
            $table->string('station_cd',10)->nullable()->index();
            $table->string('eki_toho',100)->nullable();
            $table->string('eki_car',100)->nullable();
            $table->string('eki_bus',100)->nullable();
            $table->string('bus_toho',100)->nullable();
            $table->string('bus_route',100)->nullable();
            $table->string('bus_stop',100)->nullable();
            $table->tinyInteger('level')->default(1)->index();
            $table->foreign('station_cd')->references('station_cd')->on('stations')->nullable();
            $table->foreign('land_id')->references('id')->on('lands')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('land_line');
    }
}
