<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->string('property_num',20)->index();
            $table->string('registration_date',20)->index();
            $table->string('update_date',20)->nullable();
            $table->string('change_date',20)->nullable();
            $table->string('property_event',40)->index();
            $table->string('txn_mode',20)->index();
            $table->string('txn_conditions',20)->nullable();
            $table->string('txn_supplement',255)->nullable();
            $table->string('company',85)->index();
            $table->string('company_tel',20)->index();
            $table->string('contact_tel',20)->nullable();
            $table->string('pic_name',85)->nullable();
            $table->string('pic_tel',20)->nullable();
            $table->string('pic_email',255)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('before_price', 10, 2)->nullable();
            $table->decimal('per_tanka', 10, 2)->nullable();
            $table->decimal('tsubo_tanka', 10, 2)->nullable();
            $table->decimal('land_area', 10, 3)->index();
            $table->string('planimetry',20)->nullable();
            $table->string('setback',20)->nullable();
            $table->string('shidou_futan',20)->nullable();
            $table->string('shidou_menseki',20)->nullable();
            $table->unsignedInteger('pref_id');
            $table->foreign('pref_id')->references('id')->on('prefectures');
            $table->string('address1',30)->index();
            $table->string('address2',100)->index();
            $table->string('address3',100)->index();
            $table->string('other_address',100)->nullable();
            $table->string('line_cd1',10)->index();
            $table->foreign('line_cd1')->references('line_cd')->on('lines')->nullable();
            $table->string('station_cd1',10)->index();
            $table->foreign('station_cd1')->references('station_cd')->on('stations')->nullable();
            $table->string('eki_toho1',30)->nullable();
            $table->string('eki_car1',30)->nullable();
            $table->string('eki_bus1',30)->nullable();
            $table->string('bus_toho1',30)->nullable();
            $table->string('bus_route1',30)->nullable();
            $table->string('bus_stop1',30)->nullable();
            $table->string('line_cd2',10)->index();
            $table->foreign('line_cd2')->references('line_cd')->on('lines')->nullable();
            $table->string('station_cd2',10)->index();
            $table->foreign('station_cd2')->references('station_cd')->on('stations')->nullable();
            $table->string('eki_toho2',30)->nullable();
            $table->string('eki_car2',30)->nullable();
            $table->string('eki_bus2',30)->nullable();
            $table->string('bus_toho2',30)->nullable();
            $table->string('bus_route2',30)->nullable();
            $table->string('bus_stop2',30)->nullable();
            $table->string('line_cd3',10)->index();
            $table->foreign('line_cd3')->references('line_cd')->on('lines')->nullable();
            $table->string('station_cd3',10)->index();
            $table->foreign('station_cd3')->references('station_cd')->on('stations')->nullable();
            $table->string('eki_toho3',30)->nullable();
            $table->string('eki_car3',30)->nullable();
            $table->string('eki_bus3',30)->nullable();
            $table->string('bus_toho3',30)->nullable();
            $table->string('bus_route3',30)->nullable();
            $table->string('bus_stop3',30)->nullable();
            $table->string('other_transportation',100)->nullable();
            $table->string('traffic',100)->nullable();
            $table->string('ichijikin',100)->nullable();
            $table->string('ichijikin_name1',100)->nullable();
            $table->string('ichijikin_price1',100)->nullable();
            $table->string('ichijikin_name2',100)->nullable();
            $table->string('ichijikin_price2',100)->nullable();
            $table->string('genkyou',10)->nullable();
            $table->string('hikiwatashi_jiki',10)->nullable();
            $table->string('hikiwatashi_nengetu',20)->nullable();
            $table->string('houshu_keitai',10)->nullable();
            $table->decimal('fee_rate',5,2)->nullable();
            $table->decimal('transaction_fee',5,2)->nullable();
            $table->string('city_planning',10)->nullable();
            $table->string('toukibo_chimoku',10)->nullable();
            $table->string('genkyou_chimoku',10)->nullable();
            $table->string('youto_chiki',10)->nullable();
            $table->string('saiteki_youto',10)->nullable();
            $table->string('chiikichiku',10)->nullable();
            $table->decimal('kenpei_rate',5,2)->nullable();
            $table->decimal('youseki_rate',5,2)->nullable();
            $table->string('youseki_seigen',100)->nullable();
            $table->string('other_seigen',100)->nullable();
            $table->string('saikenchiku_fuka',10)->nullable();
            $table->string('kokudohou_todokede',10)->nullable();
            $table->string('shakuchiken_shurui',10)->nullable();
            $table->decimal('shakuchi_ryou',10,2)->nullable();
            $table->string('shakuchi_kigen',20)->nullable();
            $table->string('chisei',10)->nullable();
            $table->string('kenchiku_jyouken',3)->nullable();
            $table->string('setudou_jyoukyou',5)->nullable();
            $table->string('setudou_hosou',3)->nullable();
            $table->string('setudou_shubetu1',5)->nullable();
            $table->string('setudou_setumen1',10)->nullable();
            $table->string('setudou_ichi1',5)->nullable();
            $table->string('setudou_houkou1',5)->nullable();
            $table->string('setudou_fukuin1',10)->nullable();
            $table->string('setudou_shubetu2',5)->nullable();
            $table->string('setudou_setumen2',10)->nullable();
            $table->string('setudou_ichi2',5)->nullable();
            $table->string('setudou_houkou2',5)->nullable();
            $table->string('setudou_fukuin2',10)->nullable();
            $table->string('shuhenkankyou1',40)->nullable();
            $table->string('kyori1',10)->nullable();
            $table->string('jikan1',10)->nullable();
            $table->string('shuhenkankyou2',40)->nullable();
            $table->string('kyori2',10)->nullable();
            $table->string('jikan2',10)->nullable();
            $table->string('shuhenkankyou3',40)->nullable();
            $table->string('kyori3',10)->nullable();
            $table->string('jikan3',10)->nullable();
            $table->string('shuhenkankyou4',40)->nullable();
            $table->string('kyori4',10)->nullable();
            $table->string('jikan4',10)->nullable();
            $table->string('shuhenkankyou5',40)->nullable();
            $table->string('kyori5',10)->nullable();
            $table->string('jikan5',10)->nullable();
            $table->string('setubi_jyouken',200)->nullable();
            $table->string('setubi',200)->nullable();
            $table->string('jyouken',200)->nullable();
            $table->string('bikou1',30)->nullable();
            $table->string('bikou2',60)->nullable();
            $table->string('bikou3',200)->nullable();
            $table->string('bikou4',200)->nullable();
            $table->string('photo1',100)->nullable();
            $table->string('photo2',100)->nullable();
            $table->string('photo3',100)->nullable();
            $table->string('photo4',100)->nullable();
            $table->string('photo5',100)->nullable();
            $table->string('photo6',100)->nullable();
            $table->string('photo7',100)->nullable();
            $table->string('photo8',100)->nullable();
            $table->string('photo9',100)->nullable();
            $table->string('photo10',100)->nullable();
            $table->string('zumen',100)->nullable();
            $table->boolean('display_flag')->default(1)->index();
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
        Schema::dropIfExists('lands');
    }
}
