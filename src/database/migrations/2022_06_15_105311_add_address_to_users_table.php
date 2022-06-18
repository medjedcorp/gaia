<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->char('postcode',8)->nullable();
            $table->unsignedInteger('prefecture_id')->default(1);
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('address',255)->nullable()->index();
            $table->boolean('secret_flag')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('postcode');
            $table->dropColumn('prefecture_id');
            $table->dropColumn('address');
            $table->dropColumn('secret_flag');
        });
    }
}
