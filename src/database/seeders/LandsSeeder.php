<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Land;

class LandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lands')->insert([
            'bukken_num' => '333333333333',
            'touroku_date' => '令和 4年 1月1日',
            'change_date' => '令和 4年 3月10日',
            'update_date' => '令和 4年 3月17日',
            'bukken_shumoku' => '売地',
            'torihiki_taiyou' => '専任',
            'torihiki_jyoukyou' => '公開中',
            'torihiki_hosoku' => '購入申込み書面受領日：２０２２年３月６日',
            'price' => '1780',
            'mae_price' => '2000',
            'pic_email' => 'matsuda@medjed.jp',
            'pic_name' => '松田智哉',
            'pic_tel' => '090-4285-3303',
            'company' => 'メジェド合同会社 テスト店',
            'company_tel' => '0120-123-123',
            'prefecture_id' => '29',
            'address1' => '生駒市',
            'address2' => '俵口町',
            'heibei_tanka' => '6.4',
            'tsubo_tanka' => '21.1',
            'land_menseki' => '279.01',
            'keisoku_siki' => '公簿',
            'tsubo_tanka' => '21.1',
            'genkyou' => '更地',
            'hikiwatashi_jiki' => '即時',
            'houshu_keitai' => '分かれ',
            'city_planning' => '市街',
            'toukibo_chimoku' => '宅地',
            'genkyou_chimoku' => '宅地',
            'youto_chiki' => '一低',
            'saiteki_youto' => '住宅用地',
            'chiikichiku' => '風致',
            'kenpei_rate' => '40％',
            'youseki_rate' => '60％',
            'other_seigen' => '宝幢寺門前遺跡区域内、１５ｍ斜線高度地区',
            'saikenchiku_fuka' => '不可',
            'chisei' => '平坦',
            'kenchiku_jyouken' => '無',
            'setudou_jyoukyou' => '三方',
            'setudou_hosou' => '有',
            'setudou_shubetu1' => '公道',
            'setudou_setumen1' => '16.4ｍ',
            'setudou_ichi1' => '無',
            'setudou_houkou1' => '北',
            'setudou_fukuin1' => '6ｍ',
            'setudou_shubetu2' => '公道',
            'setudou_setumen2' => '17.1ｍ',
            'setudou_ichi2' => '無',
            'setudou_houkou2' => '東',
            'setudou_fukuin2' => '6ｍ',
            'shuhenkankyou1' => '万代生駒店',
            'kyori1' => '700ｍ',
            'jikan1' => '徒歩　9分',
            'shuhenkankyou2' => 'ならコープ生駒店',
            'kyori2' => '750ｍ',
            'jikan2' => '徒歩　10分',
            'shuhenkankyou3' => '生駒メディカルビル',
            'kyori3' => '800ｍ',
            'jikan3' => '徒歩　10分',
            'shuhenkankyou4' => '郵便局生駒本局',
            'kyori4' => '620ｍ',
            'jikan4' => '徒歩　8分',
            'shuhenkankyou5' => '稲葉台公園',
            'kyori5' => '120ｍ',
            'jikan5' => '徒歩　2分',
            'setubi_jyouken' => '都市ガス,電気,上水道,下水道,２沿線以上利用可',
            'setubi' => '大型倉庫付き！トイレ付！',
            'jyouken' => '上物あり',
            'bikou1' => '資料はレインズよりお願いします',
            'bikou2' => '解体更地渡し',
            'bikou3' => '本物件は市街化調整区域内にある為、原則建物を建築することは出来ません。',
            'bikou4' => '旧住宅地造成事業に関する法律による許可に基づき造成された開発区域内にある為、建築可能です。対象不動産の区画形質の変更を行う場合は開発行為となる為、知事の許可が必要です。',
            'jyouken' => '上物あり',
            'display_flag' => 1,
            'created_at' => now(),
            'updated_at' => now()
            // 'remember_token'    => Str::random(10),
          ]);
    }

}
