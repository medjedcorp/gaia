<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('station_cd',8)->unique()->index();
            $table->string('station_g_cd',8)->index();
            $table->string('station_name',100)->index();
            $table->string('line_cd',6)->index();
            $table->foreign('line_cd')->references('line_cd')->on('lines')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unsignedInteger('pref_cd');
            $table->foreign('pref_cd')->references('id')->on('prefectures')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->char('post',8);
            $table->string('address',255);
            $table->geometry('location')->nullable()->comment('緯度経度');
            $table->boolean('display_flag')->default(0)->index();
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
        Schema::dropIfExists('stations');
    }
}
