#!/usr/bin/env python
# coding: utf-8

# In[ ]:

from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.chrome.service import Service as ChromeService
# from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome import service as fs
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.select import Select
import time
import csv
import datetime
import shutil
import os
import re
import config
import shutil
import sys
import glob

#ログイン画面のURL
LOGIN_URL = "https://system.reins.jp/login/main/KG/GKG001200"
#ログイン情報：ユーザー名
USER_ID = config.REINS_ID
#ログイン情報：パスワード
PASS = config.REINS_PASS
# 物件種別１ 必須項目
PROPERTY_TYPE1 = "売土地"
# 所在地・沿線
# 所在地範囲選択１
# 都道府県名
# PREF1_FORM = config.REINS_PREF1
# PREF1_END = config.REINS_PREF1
# PREF2_START = config.REINS_PREF2
# PREF2_END = config.REINS_PREF2
# LOCATION1_START = config.REINS_LOCATION1
# LOCATION1_END = config.REINS_LOCATION1
# LOCATION2_START = config.REINS_LOCATION2
# LOCATION2_END = config.REINS_LOCATION2
PREF1_FORM1 = config.REINS_PREF1
ADD1_FORM1 = config.ADD1_FORM1
ADD2_FORM1 = config.ADD2_FORM1
PREF1_FORM2 = config.REINS_PREF2
ADD1_FORM2 = config.ADD1_FORM2
ADD2_FORM2 = config.ADD2_FORM2
PREF1_FORM3 = config.REINS_PREF3
ADD1_FORM3 = config.ADD1_FORM3
ADD2_FORM3 = config.ADD2_FORM3

# 各ページ待機秒数
SEC = 4
SEC2 = 2

# 基本の保存先を同じディレクトリに
DOWNDIR = os.chdir('/var/www/html/storage/app/tmp')
TMPDIR = '/var/www/html/storage/app/tmp'
CSVDIR = '/var/www/html/storage/app/csv/land'
PUBDIR = '/var/www/html/storage/app/public'
PDFDIR = '/var/www/html/storage/app/pdfs'
# DownDir = os.getcwd()
# 一時保存フォルダを空にする
shutil.rmtree(TMPDIR)
os.mkdir(TMPDIR)

# ダウンロード先を指定 os.getcwd()はこのスクリプトが保存されている場所。windowsはバックスラッシュになるから/に置き換える
# NOWDIR = os.getcwd().replace(os.sep,'/')
# NOWDIR = os.getcwd()

# CSVの準備
csv_date = datetime.datetime.now().strftime("%Y%m%d")
csv_file_name = CSVDIR + '/estate' + csv_date + '.csv'
#  mode = 'w 書き込み 、encoding='cp932' shift-jis
f = open(csv_file_name, mode = 'w', encoding='utf-8', errors='ignore')
writer = csv.writer(f, lineterminator='\n')
csv_header = ["bukken_num","touroku_date","change_date","update_date","bukken_shumoku","ad_kubun", "torihiki_taiyou","torihiki_jyoukyou","torihiki_hosoku","company","company_tel","contact_tel","pic_name","pic_tel","pic_email","price","mae_price","heibei_tanka","tsubo_tanka","land_menseki","keisoku_siki","setback","shidou_futan","shidou_menseki","prefecture_id","address1","address2","address3","other_address","line_cd1","station_cd1","eki_toho1","eki_car1","eki_bus1","bus_toho1","bus_route1","bus_stop1","line_cd2","station_cd2","eki_toho2","eki_car2","eki_bus2","bus_toho2","bus_route2","bus_stop2","line_cd3","station_cd3","eki_toho3","eki_car3","eki_bus3","bus_toho3","bus_route3","bus_stop3","other_transportation","traffic","ichijikin","ichijikin_name1","ichijikin_price1","ichijikin_name2","ichijikin_price2","genkyou","hikiwatashi_jiki","hikiwatashi_nengetu","houshu_keitai","fee_rate","transaction_fee","city_planning","toukibo_chimoku","genkyou_chimoku","youto_chiki","saiteki_youto","chiikichiku","kenpei_rate","youseki_rate","youseki_seigen","other_seigen","saikenchiku_fuka","kokudohou_todokede","shakuchiken_shurui","shakuchi_ryou","shakuchi_kigen","chisei","kenchiku_jyouken","setudou_jyoukyou","setudou_hosou","setudou_shubetu1","setudou_setumen1","setudou_ichi1","setudou_houkou1","setudou_fukuin1","setudou_shubetu2","setudou_setumen2","setudou_ichi2","setudou_houkou2","setudou_fukuin2","shuhenkankyou1","kyori1","jikan1","shuhenkankyou2","kyori2","jikan2","shuhenkankyou3","kyori3","jikan3","shuhenkankyou4","kyori4","jikan4","shuhenkankyou5","kyori5","jikan5","setubi_jyouken","setubi","jyouken","bikou1","bikou2","bikou3","bikou4","photo1","photo2","photo3","photo4","photo5","photo6","photo7","photo8","photo9","photo10","zumen"]

writer.writerow(csv_header)

chromedriver = "/usr/bin/chromedriver"
# options = webdriver.ChromeOptions()

chrome_options = webdriver.ChromeOptions()
chrome_options.add_argument('--no-sandbox')
chrome_options.add_argument('--headless')
chrome_options.add_argument('--disable-gpu')
chrome_options.add_argument('--disable-dev-shm-usage')
chrome_options.add_argument("--window-size=1920,1080")
chrome_options.add_argument('--lang=ja-JP')

# headlessモード追記
download_option = {'download.default_directory': DOWNDIR,'download.directory_upgrade': 'true','download.prompt_for_download': False,'safebrowsing.enabled': True}
chrome_options.add_experimental_option('prefs', download_option)
# driverの読み込み
driver = webdriver.Chrome(options=chrome_options)

#指定したurlへ遷移
driver.get(LOGIN_URL)
time.sleep(SEC) # 秒


#「ユーザーID」テキストボックスへログイン情報を設定
# txt_user = driver.find_element(By.ID, "__BVID__13")
txt_user = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div/div[3]/div/div[2]/div[1]/div[1]/div/div[2]/input")
txt_user.send_keys(USER_ID)

#「パスワード」テキストボックスへログイン情報を設定
# txt_pass = driver.find_element(By.ID, "__BVID__16")
txt_pass = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div/div[3]/div/div[2]/div[1]/div[2]/div/div[2]/input")
txt_pass.send_keys(PASS)

#「ログイン」ボタンのdisabledを削除
login_dis = "document.getElementsByClassName('btn')[0].removeAttribute('disabled');"
driver.execute_script(login_dis)
time.sleep(SEC) # 秒

#「ログイン」ボタンクリック
try:
    btn_login = driver.find_element(By.CLASS_NAME, "btn")
    btn_login.click()
except Exception:
    print('ログインボタン押下時にエラーが発生しました。')

time.sleep(7) # 秒

# メインメニューでの操作
# 売買物件検索をクリック※売買物件検索のテキスト部分を変更すれば、検索方法が変わります
btn_main_menu = driver.find_element(by=By.XPATH, value="//button[contains(@class, 'btn p-button btn-primary btn-block px-0') and contains(., '売買 物件検索')]")
btn_main_menu.click()
time.sleep(SEC) # 秒

# 売買検索条件入力での操作
# 売土地、売一戸建、売マンション、売外全(住宅以外建物全部)、売外一(住宅以外建物一部)
# property1 = driver.find_element(By.ID, "__BVID__123")
property1 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[4]/div/div[2]/div[1]/div[2]/select")
select_property1 = Select(property1)
select_property1.select_by_visible_text(PROPERTY_TYPE1)

# 都道府県の入力。envファイルより取得
# 所在地１
pref1_form1 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[10]/div[2]/div[1]/div/input")
pref1_form1.send_keys(PREF1_FORM1)

address1_form1 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[10]/div[3]/div/div[2]/div/div/input")
address1_form1.send_keys(ADD1_FORM1)

address2_form1 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[10]/div[4]/div/div[2]/div[1]/div/input")
address2_form1.send_keys(ADD2_FORM1)

# 所在地2
pref1_form2 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[12]/div[2]/div[1]/div/input")
pref1_form2.send_keys(PREF1_FORM2)

address1_form2 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[12]/div[3]/div/div[2]/div/div/input")
address1_form2.send_keys(ADD1_FORM2)

address2_form2 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[12]/div[4]/div/div[2]/div[1]/div/input")
address2_form2.send_keys(ADD2_FORM2)

# 所在地3
pref1_form3 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[14]/div[2]/div[1]/div/input")
pref1_form3.send_keys(PREF1_FORM3)

address1_form3 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[14]/div[3]/div/div[2]/div/div/input")
address1_form3.send_keys(ADD1_FORM3)

address2_form3 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[14]/div[4]/div/div[2]/div[1]/div/input")
address2_form3.send_keys(ADD2_FORM3)


# 検索をクリック
trade_search = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[2]/div/div/div/div/div[4]/button")
trade_search.click()
time.sleep(SEC) # 秒
# 何ページあるか取得
pages = driver.find_elements(By.CLASS_NAME, "page-item")
# 例 売土地(100件)
page_count = len(pages)
page_num = page_count / 2 - 2
page = 0

# next_page = 1
print(str(page_num) + 'ページ取り込みます')
# print(page_num)

while True:
    page = page + 1
    # 検索結果一覧画面へ
    # 詳細ボタンの数を取得
    # elementsにすれば複数取得可能
    detail_elems = driver.find_elements(by=By.XPATH, value="//button[contains(@class, 'btn p-button m-0 py-0 btn-outline btn-block px-0') and contains(., '詳細')]")
    detail_count = len(detail_elems)
    print(str(page) + '頁目 / ' + str(detail_count) + '件あります')
    # カウント要素を別で作らないとループがうまくまわらない…
    i = 0

    for i in range(detail_count):
        details = driver.find_elements(by=By.XPATH, value="//button[contains(@class, 'btn p-button m-0 py-0 btn-outline btn-block px-0') and contains(., '詳細')]")
        # details[i].click()
        # この書き方でないと画面外ボタンがエラーになる
        driver.execute_script("arguments[0].click();", details[i])

        # データ取得
        csvlist = []
        
        # 物件番号が見つかるまで待機時間を5回繰り返す。ページ遷移時に時間がかかった場合の対応
        for _ in range(5):
            time.sleep(SEC) # 秒
            if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[1]/div/div[2]/div")) > 0 :
                property_num = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[1]/div/div[2]/div")
                break
                
        csvlist.append(property_num.text)
        
        registration_date = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[2]/div/div[2]/div")
        
        csvlist.append(registration_date.text)

        # 変更か更新が存在する場合
        update_date = None
        change_date = None
        if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[3]/div/div[1]/span")) > 0 :
            # 存在する時の処理
            check_text = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[3]/div/div[1]/span")
            if check_text.text == "更新年月日":
                # 更新年月日だった場合、変更年月日はなし
                update_date = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[3]/div/div[2]/div")
                csvlist.append(change_date)
                csvlist.append(update_date.text)
            else:
                # 更新年月日じゃない場合、変更年月日に値を入れる
                change_date = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[3]/div/div[2]/div")
                csvlist.append(change_date.text)
                if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[4]/div/div[1]/span")) > 0 :
                    # 更新年月日が存在するか確認。存在する場合の処理
                    update_date = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[4]/div/div[2]/div")
                    csvlist.append(update_date.text)
                else:
                    # 更新年月日が存在しない場合の処理
                    csvlist.append(update_date)
        else:
            # 変更か更新も存在しない場合
            csvlist.append(change_date)
            csvlist.append(update_date)
            
        # 物件種目
        property_event = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[2]/div/div[1]/div/div[2]/div")
        csvlist.append(property_event.text)

        # 物件種目
        ad_kubun = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[2]/div/div[2]/div/div[2]/div")
        csvlist.append(ad_kubun.text)

        # 取引態様
        txn_mode = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[3]/div/div[1]/div/div[2]/div")
        csvlist.append(txn_mode.text)
        
        # 取引状況
        txn_conditions = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[3]/div/div[2]/div/div[2]/div")
        csvlist.append(txn_conditions.text)
        
        # 取引状況の補足
        txn_supplement = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[3]/div/div[3]/div/div[2]/div")
        csvlist.append(txn_supplement.text)
        
        # 担当会社名
        company = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[4]/div/div[1]/div[1]/div/div[2]/div/a")
        csvlist.append(company.text)
        
        # 代表電話番号
        company_tel = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[4]/div/div[1]/div[2]/div[1]/div[2]/div")
        csvlist.append(company_tel.text)
        
        # 問合せ先電話番号
        contact_tel = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[4]/div/div[1]/div[2]/div[2]/div[2]/div")
        csvlist.append(contact_tel.text)
        
        # 物件問合せ担当者
        pic_name = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[4]/div/div[2]/div[1]/div[1]/div[2]/div")
        csvlist.append(pic_name.text)
        
        # 物件担当者電話番号
        pic_tel = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[4]/div/div[2]/div[1]/div[2]/div[2]/div")
        csvlist.append(pic_tel.text)
        
        # 物件担当者Ｅメールアドレス
        pic_email = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[4]/div/div[2]/div[2]/div/div[2]/div/a")
        csvlist.append(pic_email.text)
        
        # 価格
        price = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[5]/div/div/div[1]/div/div[2]/div")
        if len(price.text) == 0:
            price_num = ''
        else:
            price_connma_num = price.text[:-2]
            price_num = price_connma_num.replace(",", "")
        csvlist.append(price_num)
        
        # 変更前価格
        before_price = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[5]/div/div/div[2]/div/div[2]/div")
        if len(before_price.text) == 0:
            before_price_num = ''
        else:
            before_price_connma_num = before_price.text[:-2]
            before_price_num = before_price_connma_num.replace(",", "")
        csvlist.append(before_price_num)
        
        # ㎡単価
        per_tanka = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[5]/div/div/div[3]/div[1]/div[2]/div")
        if len(per_tanka.text) == 0:
            per_tanka_num = ''
        else:
            per_tanka_connma_num = per_tanka.text[:-2]
            per_tanka_num = per_tanka_connma_num.replace(",", "")
        csvlist.append(per_tanka_num)
        
        # 坪単価 ※3.30578で換算
        tsubo_tanka = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[5]/div/div/div[3]/div[2]/div[2]/div")
        if len(tsubo_tanka.text) == 0:
            tsubo_tanka_num = ''
        else:
            tsubo_tanka_connma_num = tsubo_tanka.text[:-2]
            tsubo_tanka_num = tsubo_tanka_connma_num.replace(",", "")
        csvlist.append(tsubo_tanka_num)
        
        # 土地面積
        land_area = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[6]/div/div/div[1]/div/div[2]/div")
        land_connma_num = land_area.text[:-1]
        land_num = land_connma_num.replace(",", "")
        csvlist.append(land_num)
        
        # 面積計測方式
        planimetry = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[6]/div/div/div[2]/div/div[2]/div")
        csvlist.append(planimetry.text)
        
        # セットバック区分
        setback = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[6]/div/div/div[3]/div/div[2]/div")
        csvlist.append(setback.text)        
        
        # 私道負担有無
        shidou_futan = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[6]/div/div/div[4]/div[1]/div[2]/div")
        csvlist.append(shidou_futan.text)        
        
        # 私道面積
        shidou_menseki = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[6]/div/div/div[4]/div[2]/div[2]/div")
        csvlist.append(shidou_menseki.text)
        
        # 都道府県名
        pref_id = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[7]/div/div[1]/div/div[2]/div")
        pref = {"北海道":"1","青森県":"2","岩手県":"3","宮城県":"4","秋田県":"5","山形県":"6","福島県":"7","茨城県":"8","栃木県":"9","群馬県":"10","埼玉県":"12","千葉県":"12","東京都":"13","神奈川県":"14","新潟県":"15","富山県":"16","石川県":"17","福井県":"18","山梨県":"19","長野県":"20","岐阜県":"21","静岡県":"22","愛知県":"23","三重県":"24","滋賀県":"25","京都府":"26","大阪府":"27","兵庫県":"28","奈良県":"29","和歌山県":"30","鳥取県":"31","島根県":"32","岡山県":"33","広島県":"34","山口県":"35","徳島県":"36","香川県":"37","愛媛県":"38","高知県":"39","福岡県":"40","佐賀県":"41","長崎県":"42","熊本県":"43","大分県":"44","宮崎県":"45","鹿児島県":"46","沖縄県":"47"}
        pref_num = pref.get(pref_id.text)
        csvlist.append(pref_num)
        
        # 所在地名１
        address1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[7]/div/div[2]/div[1]/div[2]/div")
        csvlist.append(address1.text)
        
        # 所在地名２
        address2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[7]/div/div[2]/div[2]/div[2]/div")
        csvlist.append(address2.text)
        
        # 所在地名３
        address3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[7]/div/div[3]/div/div[2]/div")
        csvlist.append(address3.text)
        
        # その他所在地表示
        other_address = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[7]/div/div[4]/div/div[2]/div")
        csvlist.append(other_address.text)
        
        # 沿線名1
        line_cd1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[1]/div/div[2]/div")
        csvlist.append(line_cd1.text)
        
        # 駅名1
        station_cd1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[2]/div/div[2]/div")
        csvlist.append(station_cd1.text)
        
        # 駅より徒歩1
        if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[3]/div[1]/div[2]/div")) > 0 :
            eki_toho1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[3]/div[1]/div[2]/div")
            csvlist.append(eki_toho1.text)
        else :
            eki_toho1 = None
            csvlist.append(eki_toho1)
        
        # 駅より車1
        eki_car1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[3]/div[2]/div[2]/div")
        csvlist.append(eki_car1.text)
        
        # 駅よりバス1
        eki_bus1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[4]/div[1]/div[2]/div")
        csvlist.append(eki_bus1.text)
        
        # バス停より徒歩1
        bus_toho1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[4]/div[2]/div[2]")
        csvlist.append(bus_toho1.text)
        
        # バス路線名1
        bus_route1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[5]/div/div[2]/div")
        csvlist.append(bus_route1.text)
        
        # バス停名称1
        bus_stop1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[1]/div[6]/div/div[2]/div")
        csvlist.append(bus_stop1.text)
        
        # 沿線名2
        line_cd2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[1]/div/div[2]/div")
        csvlist.append(line_cd2.text)
        
        # 駅名2
        station_cd2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[2]/div/div[2]/div")
        csvlist.append(station_cd2.text)
        
        # 駅より徒歩2
        if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[3]/div[1]/div[2]/div")) > 0 :
            eki_toho2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[3]/div[1]/div[2]")
            csvlist.append(eki_toho2.text)
        else :
            eki_toho2 = None
            csvlist.append(eki_toho2)
        # //*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[3]/div[1]/div[2]/div ある場合？
        # //*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[3]/div[1]/div[2] ない場合？
        
        # 駅より車2
        eki_car2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[3]/div[2]/div[2]/div")
        csvlist.append(eki_car2.text)
        
        # 駅よりバス2
        eki_bus2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[4]/div[1]/div[2]/div")
        csvlist.append(eki_bus2.text)
        
        # バス停より徒歩2
        bus_toho2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[4]/div[2]/div[2]")
        csvlist.append(bus_toho2.text)
        
        # バス路線名2
        bus_route2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[5]/div/div[2]/div")
        csvlist.append(bus_route2.text)
        
        # バス停名称2
        bus_stop2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[2]/div[6]/div/div[2]/div")
        csvlist.append(bus_stop2.text)
    
        # 沿線名3
        line_cd3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[1]/div/div[2]/div")
        csvlist.append(line_cd3.text)
        
        # 駅名3
        station_cd3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[2]/div/div[2]/div")
        csvlist.append(station_cd3.text)
        
        # 駅より徒歩3
        if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[3]/div[1]/div[2]/div")) > 0 :
            eki_toho3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[3]/div[1]/div[2]/div")
            csvlist.append(eki_toho3.text)
        else :
            eki_toho3 = None
            csvlist.append(eki_toho3)
        
        # 駅より車3
        eki_car3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[3]/div[2]/div[2]/div")
        csvlist.append(eki_car3.text)
        
        # 駅よりバス3
        eki_bus3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[4]/div[1]/div[2]/div")
        csvlist.append(eki_bus3.text)
        
        # バス停より徒歩3
        bus_toho3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[4]/div[2]/div[2]")
        csvlist.append(bus_toho3.text)
        
        # バス路線名3
        bus_route3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[5]/div/div[2]/div")
        csvlist.append(bus_route3.text)
        
        # バス停名称3
        bus_stop3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[3]/div[6]/div/div[2]/div")
        csvlist.append(bus_stop3.text)
    
        # その他交通手段
        other_transportation = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[4]/div/div[1]/div[2]/div")
        csvlist.append(other_transportation.text)
    
        # 交通
        traffic = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[8]/div/div[4]/div/div[2]/div[2]")
        csvlist.append(traffic.text)
        
        # その他一時金なし
        ichijikin = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[9]/div/div[1]/div/div[2]/div")
        csvlist.append(ichijikin.text)
        
        # その他一時金名称１
        ichijikin_name1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[9]/div/div[2]/div[1]/div[2]/div")
        csvlist.append(ichijikin_name1.text)
        
        # 金額１
        ichikin_price1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[9]/div/div[2]/div[2]/div[2]/div")
        csvlist.append(ichikin_price1.text)
        
        # その他一時金名称２
        ichijikin_name2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[9]/div/div[3]/div[1]/div[2]/div")
        csvlist.append(ichijikin_name2.text)
        
        # 金額２
        ichijikin_price2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[9]/div/div[3]/div[2]/div[2]/div")
        csvlist.append(ichijikin_price2.text)
        
        # 現況
        genkyou = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[10]/div/div/div/div[2]/div")
        csvlist.append(genkyou.text)
        
        # 引渡時期
        hikiwatashi_jiki = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[11]/div/div/div[1]/div[2]/div")
        csvlist.append(hikiwatashi_jiki.text)
        
        # 引渡年月
        hikiwatashi_nengetu = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[11]/div/div/div[2]/div[2]/div[1]")
        csvlist.append(hikiwatashi_nengetu.text)
        
        # 報酬形態
        houshu_keitai = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[12]/div/div[1]/div/div[2]/div")
        csvlist.append(houshu_keitai.text)
        
        # 手数料割合率
        fee_rate = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[12]/div/div[2]/div[1]/div[2]/div")
        csvlist.append(fee_rate.text)
        
        # 手数料
        transaction_fee = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[12]/div/div[2]/div[2]/div[2]/div")
        csvlist.append(transaction_fee.text)
        
        # 都市計画
        city_planning = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[1]/div/div[2]/div")
        csvlist.append(city_planning.text)
        
        # 登記簿地目
        toukibo_chimoku = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[2]/div[1]/div[2]/div")
        csvlist.append(toukibo_chimoku.text)
        
        # 現況地目
        genkyou_chimoku = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[2]/div[2]/div[2]/div")
        csvlist.append(genkyou_chimoku.text)
     
        # 用途地域
        youto_chiki = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[3]/div/div[2]/div")
        csvlist.append(youto_chiki.text)
    
        # 最適用途
        saiteki_youto = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[4]/div/div[2]/div")
        csvlist.append(saiteki_youto.text)
    
        # 地域地区
        chiikichiku = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[5]/div/div[2]/div")
        csvlist.append(chiikichiku.text)
    
        # 建ぺい率
        kenpei_rate = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[6]/div[1]/div[2]/div")
        csvlist.append(kenpei_rate.text)
    
        # 容積率
        youseki_rate = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[6]/div[2]/div[2]/div")
        csvlist.append(youseki_rate.text)
    
        # 容積率の制限内容
        youseki_seigen = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[6]/div[2]/div[3]/div/div[2]/div")
        csvlist.append(youseki_seigen.text)
  
        # その他の法令上の制限
        other_seigen = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[7]/div/div[2]/div")
        csvlist.append(other_seigen.text)

        # 再建築不可
        saikenchiku_fuka = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[8]/div/div[2]/div")
        csvlist.append(saikenchiku_fuka.text)
        
        # 国土法届出
        kokudohou_todokede = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[13]/div/div[9]/div/div[2]/div")
        csvlist.append(kokudohou_todokede.text)
        
        # 借地権種類
        shakuchiken_shurui = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[14]/div/div[1]/div/div[2]/div")
        csvlist.append(shakuchiken_shurui.text)
        
        # 借地料
        shakuchi_ryou = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[14]/div/div[2]/div/div[2]/div")
        csvlist.append(shakuchi_ryou.text)
        
        # 借地期限
        shakuchi_kigen = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[14]/div/div[3]/div/div[2]/div")
        csvlist.append(shakuchi_kigen.text)
        
        # 地勢
        chisei = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[15]/div/div[1]/div/div[2]/div")
        csvlist.append(chisei.text)
        
        # 建築条件
        kenchiku_jyouken = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[15]/div/div[2]/div/div[2]/div")
        csvlist.append(kenchiku_jyouken.text)
        
        # 接道状況
        setudou_jyoukyou = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[1]/div/div[2]/div")
        csvlist.append(setudou_jyoukyou.text)

        # 接道舗装
        setudou_hosou = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[2]/div/div[2]/div")
        csvlist.append(setudou_hosou.text)

        # 接道種別1
        setudou_shubetu1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[3]/div[1]/div[1]/div[2]/div")
        csvlist.append(setudou_shubetu1.text)

        # 接道接面1
        setudou_setumen1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[3]/div[1]/div[2]/div[2]/div")
        csvlist.append(setudou_setumen1.text)
        
        # 接道位置指定1
        setudou_ichi1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[3]/div[2]/div[1]/div[2]/div")
        csvlist.append(setudou_ichi1.text)
        
        # 接道方向1
        setudou_houkou1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[3]/div[2]/div[2]/div[2]/div")
        csvlist.append(setudou_houkou1.text)
        
        # 接道幅員1
        setudou_fukuin1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[3]/div[3]/div/div[2]/div")
        csvlist.append(setudou_fukuin1.text)
        
        # 接道種別2
        setudou_shubetu2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[4]/div[1]/div[1]/div[2]/div")
        csvlist.append(setudou_shubetu2.text)

        # 接道接面2
        setudou_setumen2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[4]/div[1]/div[2]/div[2]/div")
        csvlist.append(setudou_setumen2.text)
        
        # 接道位置指定2
        setudou_ichi2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[4]/div[2]/div[1]/div[2]/div")
        csvlist.append(setudou_ichi2.text)
        
        # 接道方向2
        setudou_houkou2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[4]/div[2]/div[2]/div[2]/div")
        csvlist.append(setudou_houkou2.text)
        
        # 接道幅員2
        setudou_fukuin2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[16]/div/div[4]/div[3]/div/div[2]/div")
        csvlist.append(setudou_fukuin2.text)
        
        # 周辺環境1
        shuhenkankyou1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[1]/div/div[2]/div")
        csvlist.append(shuhenkankyou1.text)
        
        # 距離1
        kyori1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[2]/div[1]/div[2]/div")
        csvlist.append(kyori1.text)
        
        # 時間1
        jikan1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[2]/div[2]/div[2]/div")
        csvlist.append(jikan1.text)
        
        # 周辺環境2
        shuhenkankyou2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[3]/div/div[2]/div")
        csvlist.append(shuhenkankyou2.text)
        
        # 距離2
        kyori2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[4]/div[1]/div[2]/div")
        csvlist.append(kyori2.text)
        
        # 時間2
        jikan2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[4]/div[2]/div[2]/div")
        csvlist.append(jikan2.text)
        
        # 周辺環境3
        shuhenkankyou3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[5]/div/div[2]/div")
        csvlist.append(shuhenkankyou3.text)
        
        # 距離3
        kyori3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[6]/div[1]/div[2]/div")
        csvlist.append(kyori3.text)
        
        # 時間3
        jikan3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[6]/div[2]/div[2]/div")
        csvlist.append(jikan3.text)
        
        # 周辺環境4
        shuhenkankyou4 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[7]/div/div[2]/div")
        csvlist.append(shuhenkankyou4.text)
        
        # 距離4
        kyori4 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[8]/div[1]/div[2]/div")
        csvlist.append(kyori4.text)
        
        # 時間4
        jikan4 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[8]/div[2]/div[2]/div")
        csvlist.append(jikan4.text)
        
        # 周辺環境5
        shuhenkankyou5 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[9]/div/div[2]/div")
        csvlist.append(shuhenkankyou5.text)
        
        # 距離5
        kyori5 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[10]/div[1]/div[2]/div")
        csvlist.append(kyori5.text)
        
        # 時間5
        jikan5 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[17]/div/div[10]/div[2]/div[2]/div")
        csvlist.append(jikan5.text)
        
        # 設備・条件
        setubi_jyouken = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[18]/div/div[1]/div/div[2]/div")
        csvlist.append(setubi_jyouken.text)
        
        # 設備
        setubi = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[18]/div/div[2]/div/div[2]/div")
        csvlist.append(setubi.text)
        
        # 条件
        jyouken = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[18]/div/div[3]/div/div[2]/div")
        csvlist.append(jyouken.text)
        
        # 備考1
        bikou1 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[19]/div/div[1]/div/div[2]/div")
        csvlist.append(bikou1.text)
        
        # 備考2
        bikou2 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[19]/div/div[2]/div/div[2]/div")
        csvlist.append(bikou2.text)
        
        # 備考3
        bikou3 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[19]/div/div[3]/div/div[2]/div")
        csvlist.append(bikou3.text)
        
        # 備考4
        bikou4 = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[19]/div/div[4]/div/div[2]/div")
        csvlist.append(bikou4.text)
                        
        # 物件画像のファイル名1～ 10 を配列化
        photos = ["//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[1]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[2]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[3]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[4]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[5]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[6]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[7]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[8]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[9]/div[2]/div/div[2]/div",
                  "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[10]/div[2]/div/div[2]/div"]

        # 物件画像1～ 10 を配列化
        photo_links = ["//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[2]/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[3]/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[4]/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[5]/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[6]/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[7]/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[8]/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[9]/div[1]/div/a/div",
                       "//*[@id='__layout']/div/div[1]/div[1]/div/div[20]/div/div/div[10]/div[1]/div/a/div"]

        
        # 画像を開いて保存する関数
        def imgsave(img_name, photo_link, SAVEDIR):
            photo_link.click()
            time.sleep(3) # 3秒待機
            # 画像１の情報を取得
            open_photo = driver.find_element(by=By.XPATH, value="/html/body/div[2]/div[1]/div/div/div/div/div/div/div")
            photo_close = driver.find_element(by=By.XPATH, value="/html/body/div[2]/div[1]/div/div/footer/div/div/div/button")
            
            # 画像のbackground-imageからURLを抽出
            photo_url = open_photo.value_of_css_property("background-image")
            # 画像のURLか正規表現で、URL形式に変換
            photo_slice_url = re.split('[()]',photo_url)[1]
            img_url = photo_slice_url.replace('"', '')
            #空のタブを開く
            driver.execute_script("window.open();")
            # 開いた空のタブを選択
            driver.switch_to.window(driver.window_handles[1])
            time.sleep(1)
            # 画像のURLを開いたタブで開く
            driver.get(img_url)
            time.sleep(2)
            img = driver.find_element(By.TAG_NAME, "img")
            # 保存処理。os.path.joinでパスとファイル名を結合して指定 SSを撮影
            with open(os.path.join(SAVEDIR,img_name), 'wb') as SAVEDIR:
                SAVEDIR.write(img.screenshot_as_png)
            # ページを閉じる
            driver.close()
            time.sleep(1)
            # 最初のタブを指定
            driver.switch_to.window(driver.window_handles[0])
            time.sleep(1)
            photo_close.click()
            

        # SAVEDIR = PUBDIR + '/images/' + property_num.text
        SAVEDIR = PUBDIR + '/landimages/' + property_num.text

        # 保存するフォルダが未作成の場合、新規作成する
        if len(driver.find_elements(by=By.XPATH, value=photos[0])) > 0 :    
            os.makedirs(SAVEDIR, exist_ok = True)
            
        # 物件画像10が存在する場合の処理
        photo1 = None
        photo2 = None
        photo3 = None
        photo4 = None
        photo5 = None
        photo6 = None
        photo7 = None
        photo8 = None
        photo9 = None
        photo10 = None
            
        if len(driver.find_elements(by=By.XPATH, value=photos[9])) > 0 :
            # 画像10が存在する時の処理
            # 画像のファイル名をcsvに追加
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            photo3 = property_num.text + "_03.jpg"
            photo4 = property_num.text + "_04.jpg"
            photo5 = property_num.text + "_05.jpg"
            photo6 = property_num.text + "_06.jpg"
            photo7 = property_num.text + "_07.jpg"
            photo8 = property_num.text + "_08.jpg"
            photo9 = property_num.text + "_09.jpg"
            photo10 = property_num.text + "_10.jpg"

            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            # exist_images = [photo1.text,photo2.text,photo3.text,photo4.text,photo5.text,photo6.text,photo7.text,photo8.text,photo9.text,photo10.text]
            exist_images = [photo1,photo2,photo3,photo4,photo5,photo6,photo7,photo8,photo9,photo10]

            for num in range(10):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存' + str(num + 1) + '枚目 / 10枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 10枚中')
           
        elif len(driver.find_elements(by=By.XPATH, value=photos[8])) > 0 :
            # 画像9が存在する時の処理
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            photo3 = property_num.text + "_03.jpg"
            photo4 = property_num.text + "_04.jpg"
            photo5 = property_num.text + "_05.jpg"
            photo6 = property_num.text + "_06.jpg"
            photo7 = property_num.text + "_07.jpg"
            photo8 = property_num.text + "_08.jpg"
            photo9 = property_num.text + "_09.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            exist_images = [photo1,photo2,photo3,photo4,photo5,photo6,photo7,photo8,photo9]
            for num in range(9):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存 ' + str(num + 1) + '枚目 / 9枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 9枚中')
            # for num in range(8):
            #     # print(exist_images[num])
            #     img_name = exist_images[num]
            #     photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
            #     imgsave(img_name, photo_link, SAVEDIR)
            
        elif len(driver.find_elements(by=By.XPATH, value=photos[7])) > 0 :
            # 画像8が存在する時の処理
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            photo3 = property_num.text + "_03.jpg"
            photo4 = property_num.text + "_04.jpg"
            photo5 = property_num.text + "_05.jpg"
            photo6 = property_num.text + "_06.jpg"
            photo7 = property_num.text + "_07.jpg"
            photo8 = property_num.text + "_08.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            exist_images = [photo1,photo2,photo3,photo4,photo5,photo6,photo7,photo8]
            
            for num in range(8):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存 ' + str(num + 1) + '枚目 / 8枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 8枚中')
            # for num in range(7):
            #     # print(exist_images[num])
            #     img_name = exist_images[num]
            #     photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
            #     imgsave(img_name, photo_link, SAVEDIR)
            
        elif len(driver.find_elements(by=By.XPATH, value=photos[6])) > 0 :
            # 画像7が存在する時の処理
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            photo3 = property_num.text + "_03.jpg"
            photo4 = property_num.text + "_04.jpg"
            photo5 = property_num.text + "_05.jpg"
            photo6 = property_num.text + "_06.jpg"
            photo7 = property_num.text + "_07.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            exist_images = [photo1,photo2,photo3,photo4,photo5,photo6,photo7]
            
            for num in range(7):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存 ' + str(num + 1) + '枚目 / 7枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 7枚中')
            # for num in range(6):
            #     # print(exist_images[num])
            #     img_name = exist_images[num]
            #     photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
            #     imgsave(img_name, photo_link, SAVEDIR)
                
        elif len(driver.find_elements(by=By.XPATH, value=photos[5])) > 0 :
            # 画像6が存在する時の処理
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            photo3 = property_num.text + "_03.jpg"
            photo4 = property_num.text + "_04.jpg"
            photo5 = property_num.text + "_05.jpg"
            photo6 = property_num.text + "_06.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            exist_images = [photo1,photo2,photo3,photo4,photo5,photo6]
            
            for num in range(6):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存 ' + str(num + 1) + '枚目 / 6枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 6枚中')
            # for num in range(5):
            #     # print(exist_images[num])
            #     img_name = exist_images[num]
            #     photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
            #     imgsave(img_name, photo_link, SAVEDIR)
            
        elif len(driver.find_elements(by=By.XPATH, value=photos[4])) > 0 :
            # 画像5が存在する時の処理
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            photo3 = property_num.text + "_03.jpg"
            photo4 = property_num.text + "_04.jpg"
            photo5 = property_num.text + "_05.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            exist_images = [photo1,photo2,photo3,photo4,photo5]

            for num in range(5):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存 ' + str(num + 1) + '枚目 / 5枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 5枚中')
            # for num in range(4):
            #     img_name = exist_images[num]
            #     photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
            #     imgsave(img_name, photo_link, SAVEDIR)
            
        elif len(driver.find_elements(by=By.XPATH, value=photos[3])) > 0 :
            # 画像4が存在する時の処理
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            photo3 = property_num.text + "_03.jpg"
            photo4 = property_num.text + "_04.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            exist_images = [photo1,photo2,photo3,photo4]

            for num in range(4):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存 ' + str(num + 1) + '枚目 / 4枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 4枚中')
            # for num in range(3):
            #     img_name = exist_images[num]
            #     photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
            #     imgsave(img_name, photo_link, SAVEDIR)
            
        elif len(driver.find_elements(by=By.XPATH, value=photos[2])) > 0 :
            # 画像3が存在する時の処理
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            photo3 = property_num.text + "_03.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            exist_images = [photo1,photo2,photo3]

            for num in range(3):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存 ' + str(num + 1) + '枚目 / 3枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 3枚中')
            # for num in range(2):
            #     img_name = exist_images[num]
            #     photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
            #     imgsave(img_name, photo_link, SAVEDIR)
            
        elif len(driver.find_elements(by=By.XPATH, value=photos[1])) > 0 :
            # 画像2が存在する時の処理
            photo1 = property_num.text + "_01.jpg"
            photo2 = property_num.text + "_02.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
            
            # 画像の名前を変数に格納
            exist_images = [photo1,photo2]

            for num in range(2):
                # 画像が存在する場合は保存しない
                img_name = exist_images[num]
                exist_path = SAVEDIR + '/' + img_name
                is_file = os.path.isfile(exist_path)
                if not is_file:
                    print('画像を保存 ' + str(num + 1) + '枚目 / 2枚中')
                    photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
                    imgsave(img_name, photo_link, SAVEDIR)
                else:
                    print('画像保存をskip ' + str(num + 1) +'枚目 / 2枚中')
            # for num in range(1):
            #     img_name = exist_images[num]
            #     photo_link = driver.find_element(by=By.XPATH, value=photo_links[num])
            #     imgsave(img_name, photo_link, SAVEDIR)
            
        elif len(driver.find_elements(by=By.XPATH, value=photos[0])) > 0 :
            # 画像1が存在する時の処理
            # photo1 = driver.find_element(by=By.XPATH, value=photos[0])
            photo1 = property_num.text + "_01.jpg"
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)

            # 画像1の名前を変数に格納
            img_name = photo1
            exist_path = SAVEDIR + '/' + img_name
            is_file = os.path.isfile(exist_path)
            if not is_file:
                print('画像を保存 1枚目 / 1枚中')
                photo_link = driver.find_element(by=By.XPATH, value=photo_links[0])
                imgsave(img_name, photo_link, SAVEDIR)
            else:
                print('画像保存をskip 1枚目 / 1枚中')
            # img_name = photo1
            # photo_link = driver.find_element(by=By.XPATH, value=photo_links[0])
            # imgsave(img_name, photo_link, SAVEDIR)
        else:
            # 物件画像が存在しないときの処理
            csvlist.append(photo1)
            csvlist.append(photo2)
            csvlist.append(photo3)
            csvlist.append(photo4)
            csvlist.append(photo5)
            csvlist.append(photo6)
            csvlist.append(photo7)
            csvlist.append(photo8)
            csvlist.append(photo9)
            csvlist.append(photo10)
        
        # 物件図面 
        time.sleep(1)
        if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[21]/div/div/div/div[2]/div[1]")) > 0 :
            zumen = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[21]/div/div/div/div[2]/div[1]")
            # csvlist.append(zumen.text)
            zumen_btn = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[21]/div/div/div/div[2]/div[2]/button")
            driver.execute_script("arguments[0].scrollIntoView(true);", zumen_btn)
            zumen_btn.click()

            print('図面pdfを保存します')
            # time.sleep(13)
            
            # 待機タイムアウト時間(秒)設定
            timeout_second = 30

            # 指定時間分待機
            for k in range(timeout_second + 1):
                # ファイル一覧取得
                download_fileName = glob.glob(TMPDIR + '/*.*')
                # download_fileName = glob.glob(TMPDIR)
                # download_fileName = glob.glob(TMPDIR + '/')
                # ファイルが存在する場合
                print(download_fileName)
                if download_fileName:
                    # 拡張子の抽出
                    extension = os.path.splitext(download_fileName[0])
                    # 拡張子が '.crdownload' ではない ダウンロード完了 待機を抜ける
                    if ".crdownload" not in extension[1]:
                        print(property_num.text + 'の図面pdfを保存しました。' + str(k) + '秒かかりました。')
                        time.sleep(2)
                        break
                # 指定時間待っても .crdownload 以外のファイルが確認できない場合 エラー
                if k >= timeout_second:
                    # == エラー処理をここに記載 ==
                    # 終了処理
                    print(property_num.text + 'の図面pdf取得に失敗しました。処理を終了します。')
                    driver.quit()
                    sys.exit()
                # 一秒待つ
                time.sleep(1)

            # 最新のダウンロードファイル名を取得
            def getLatestDownloadedFileName():
                if len(os.listdir(TMPDIR + '/')) == 0:
                    return None
                return max (
                    [TMPDIR + '/' + f for f in os.listdir(TMPDIR + '/')], 
                    key=os.path.getctime
                )

            download_pdf_name = getLatestDownloadedFileName()

            pdf_rename = property_num.text + '_zumen.pdf'
            re_pdf_path = TMPDIR + '/' + pdf_rename
            # ファイル名を変更 日本語対応
            os.rename(download_pdf_name, re_pdf_path) 
            csvlist.append(pdf_rename)

            # フォルダの振り分け
            pdf_dir = PDFDIR + '/' + property_num.text
            os.makedirs(pdf_dir, exist_ok = True)
            chk_pdf = PDFDIR + '/' + property_num.text + '/' + pdf_rename
            if(os.path.isfile(chk_pdf)):
                os.remove(chk_pdf)
            shutil.move(re_pdf_path, pdf_dir + '/')

        else:       
            zumen = None
            print('図面pdfが存在しませんでした')
            csvlist.append(zumen)
        # //*[@id='__layout']/div/div[1]/div[1]/div/div[21]/div/div/div/span ない場合
        # //*[@id='__layout']/div/div[1]/div[1]/div/div[21]/div/div/div/div[2]/div[1] ある場合
        writer.writerow(csvlist)
        i += 1
        print( str(i) + '件目を取り込みました / ' + str(page) + '頁目 / ' + property_num.text)
        driver.back()
        time.sleep(SEC) # 秒

    if page >= page_num:
        # csvを閉じる
        f.close()
        print('正常終了')
        # 終了
        break

    else:
        # 次頁へ移行
        # next_link = driver.find_element(By.CLASS_NAME, "p-pagination-next-icon")
        next_link = driver.find_element(by=By.CSS_SELECTOR, value=".tab-pane > div > div:first-of-type >div > ul.pagination > li.page-item:last-child > button.page-link")
        print(str(page) + '頁目が終了、次頁へ移行します')
        next_link.click()
        time.sleep(SEC) # 秒
        # next_link.click()

# In[ ]:





# In[ ]:




