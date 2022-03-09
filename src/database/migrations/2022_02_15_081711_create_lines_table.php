<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->integer('company_cd')->index();
            $table->foreign('company_cd')->references('company_cd')->on('trains');
            $table->string('line_cd',10)->index();
            $table->string('line_name',40)->index();
            $table->string('line_color',10)->index();
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
        Schema::dropIfExists('lines');
    }
}
