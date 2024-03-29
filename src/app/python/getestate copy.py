#!/usr/bin/env python
# coding: utf-8

# In[ ]:

from selenium import webdriver
# from selenium.common.exceptions import NoSuchElementException

# from selenium.webdriver import DesiredCapabilities
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

from selenium.webdriver.chrome.service import Service
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
import sys
import glob
import requests
import mysql.connector
import logging
import logging.handlers
import math
# import psutil
# import slackweb
def getestate(USER_ID, PASS, PROPERTY_TYPE1,PREF1_FORM1,ADD1_FORM1,ADD2_FORM1,PREF1_FORM2,ADD1_FORM2,ADD2_FORM2,PREF1_FORM3,ADD1_FORM3,ADD2_FORM3,LINE_TOKEN,ADMIN_COMPANY,DB_HOST,DB_PORT,DB_DATABASE,DB_USERNAME,DB_PASSWORD,ISHEADER):
    #ログイン画面のURL
    LOGIN_URL = "https://system.reins.jp/login/main/KG/GKG001200"

    # ロガーを取得
    log = logging.getLogger(__name__)
    # logレベルの設定
    log.setLevel(logging.DEBUG)
    # ローテーティングファイルハンドラを作成
    rh = logging.handlers.RotatingFileHandler(
            r'/var/www/html/app/python/log/app.log', 
            encoding='utf-8',
            maxBytes=500000,
            backupCount=10
        )
    # ロガーに追加
    log.addHandler(rh)
    formatter = logging.Formatter('%(asctime)s - %(levelname)s:%(message)s')
    rh.setFormatter(formatter)

    sh = logging.StreamHandler()
    sh.setFormatter(formatter)
    log.addHandler(sh)

    now = datetime.datetime.now()
    log.info(now.strftime("=========== Start：%Y年%m月%d日 %H時%M分%S秒 ==========="))

    # 各ページ待機秒数
    SEC = 4
    SEC2 = 2


    # 基本の保存先を同じディレクトリに
    DOWNDIR = os.chdir('/var/www/html/storage/app/tmp')
    TMPDIR = '/var/www/html/storage/app/tmp'
    CSVDIR = '/var/www/html/storage/app/csv/land'
    PUBDIR = '/var/www/html/storage/app/public'
    PDFDIR = '/var/www/html/storage/app/pdfs'
    # testでSS撮影するとき用のディレクトリ
    SSDIR = '/var/www/html/storage/app/ss'
    # SSDIR = os.path.join('/var/www/html/storage/app/ss', "screen.png")
    # driver.save_screenshot(SSDIR)
    # CSVの準備
    csv_date = datetime.datetime.now().strftime("%Y%m%d")
    CSV_NAME = CSVDIR + '/estate' + csv_date + '.csv'
    CSV_NAME2 = CSVDIR + '/estate_num' + csv_date + '.csv'
    # mysql からデータを取得
    cnx = None

    try:
        # DBに接続
        cnx = mysql.connector.connect(
            host=DB_HOST,
            port=DB_PORT,
            user=DB_USERNAME,
            password=DB_PASSWORD,
            database=DB_DATABASE
        )

        cursor = cnx.cursor()

        sql = "SELECT  bukken_num, update_date, change_date FROM lands"

        cursor.execute(sql)
        rows = cursor.fetchall()
    
        cursor.close()

    except Exception as e:
        # print(f"Error Occurred: {e}")
        log.error('データベースへの接続に失敗しました')
        log.error(f"Error Occurred: {e}")
        sys.exit()
    finally:
        if cnx is not None and cnx.is_connected():
            cnx.close()

    # print('DBに接続しました')
    log.info("DBに接続しました")

    # 一時保存フォルダを空にする
    shutil.rmtree(TMPDIR)
    os.mkdir(TMPDIR)
    log.info("TMPフォルダを空にしました")

    # Lineに送るメッセージ
    def send_line_notify(notification_message):
        line_notify_token = LINE_TOKEN
        line_notify_api = 'https://notify-api.line.me/api/notify'
        headers = {'Authorization': f'Bearer {line_notify_token}'}
        data = {'message': f'message: {notification_message}'}
        requests.post(line_notify_api, headers = headers, data = data)

    #  mode = 'w 書き込み 、encoding='cp932' shift-jis
    f = open(CSV_NAME, mode = 'a', encoding='utf-8', errors='ignore')

    writer = csv.writer(f, lineterminator='\n')
    # writer2 = csv.writer(z, lineterminator='\n')
    csv_header = ["bukken_num","touroku_date","change_date","update_date","bukken_shumoku","ad_kubun", "torihiki_taiyou","torihiki_jyoukyou","torihiki_hosoku","company","company_tel","contact_tel","pic_name","pic_tel","pic_email","price","mae_price","heibei_tanka","tsubo_tanka","land_menseki","keisoku_siki","setback","shidou_futan","shidou_menseki","prefecture_id","address1","address2","address3","other_address","line_cd1","station_cd1","eki_toho1","eki_car1","eki_bus1","bus_toho1","bus_route1","bus_stop1","line_cd2","station_cd2","eki_toho2","eki_car2","eki_bus2","bus_toho2","bus_route2","bus_stop2","line_cd3","station_cd3","eki_toho3","eki_car3","eki_bus3","bus_toho3","bus_route3","bus_stop3","other_transportation","traffic","ichijikin","ichijikin_name1","ichijikin_price1","ichijikin_name2","ichijikin_price2","genkyou","hikiwatashi_jiki","hikiwatashi_nengetu","houshu_keitai","fee_rate","transaction_fee","city_planning","toukibo_chimoku","genkyou_chimoku","youto_chiki","saiteki_youto","chiikichiku","kenpei_rate","youseki_rate","youseki_seigen","other_seigen","saikenchiku_fuka","kokudohou_todokede","shakuchiken_shurui","shakuchi_ryou","shakuchi_kigen","chisei","kenchiku_jyouken","setudou_jyoukyou","setudou_hosou","setudou_shubetu1","setudou_setumen1","setudou_ichi1","setudou_houkou1","setudou_fukuin1","setudou_shubetu2","setudou_setumen2","setudou_ichi2","setudou_houkou2","setudou_fukuin2","shuhenkankyou1","kyori1","jikan1","shuhenkankyou2","kyori2","jikan2","shuhenkankyou3","kyori3","jikan3","shuhenkankyou4","kyori4","jikan4","shuhenkankyou5","kyori5","jikan5","setubi_jyouken","setubi","jyouken","bikou1","bikou2","bikou3","bikou4","photo1","photo2","photo3","photo4","photo5","photo6","photo7","photo8","photo9","photo10","zumen"]

    # ISHEADERがtrueの場合のみ、ヘッダー行追加
    if ISHEADER:
        writer.writerow(csv_header)

    csv_header2 = ["bukken_num"]

    # log.info("csv_headerセット")
    def csv_writer2(bukken_num):
        # openの引数aは追記。なければ作成
        with open(CSV_NAME2, 'a') as f:
            writer2 = csv.writer(f, lineterminator='\n')
            writer2.writerow(csv_header2)
            for i in bukken_num:
                # print([i])
                writer2.writerow([i])
            log.info(CSV_NAME2 + "の作成完了")


    # グローバルカウンター　総数数える
    g_count = 0
    def g_func(g_count):
        # global g_count
        g_count += 1
        return g_count

    # ドライバーの場所を指定
    chrome_options = webdriver.ChromeOptions()
    chrome_options.add_argument('--no-sandbox')
    chrome_options.headless = True
    chrome_options.add_argument('--disable-gpu')
    chrome_options.add_argument('--disable-dev-shm-usage')
    chrome_options.add_argument('--window-size=1060,800')
    chrome_options.add_argument('--lang=ja-JP')
    chrome_options.add_argument('--remote-debugging-port=9222')
    chrome_options.add_argument('--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36')
    chrome_options.add_experimental_option('prefs', {
        'download.prompt_for_download': False,
    })
    log.info("chrome_optionsのセット完了")
    # log.info(chrome_options)
    # log.info(webdriver.Remote)
    driver = webdriver.Remote(
        command_executor='http://chrome:4444/wd/hub',
        # options=webdriver.ChromeOptions()

        # desired_capabilities=options.to_capabilities(),
        options=chrome_options,
    )
    # log.info(driver)
    # driver.implicitly_wait(5)
    time.sleep(SEC)
    # driverの読み込み
    
    driver.command_executor._commands["send_command"] = (
        'POST',
        '/session/$sessionId/chromium/send_command'
    )
    
    driver.execute(
        'send_command',
        params={
            'cmd': 'Page.setDownloadBehavior',
            'params': {'behavior': 'allow', 'downloadPath': '/var/www/html/storage/app/tmp/'}
        }
    )
    # actionChains = ActionChains(driver)
    #指定したurlへ遷移
    # print(LOGIN_URL + 'にログインします')
    log.info(LOGIN_URL + 'にログインします')
    for _ in range(3):
        try:
            driver.get(LOGIN_URL)
        except Exception as e:
            # print('ログインエラー：リトライします')
            log.warning('ログインエラー：リトライします')
            time.sleep(4)
        else:
            # print('ログイン成功')
            log.info('ログイン成功')
            break

    # time.sleep(SEC) # 秒
    # 暗黙的に指定時間待つ（秒）
    driver.implicitly_wait(10)
    # time.sleep(4)

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
    except:
        log.error(ADMIN_COMPANY + '：ログインに失敗しました。終了します')
        # print('ログインボタン押下時にエラーが発生しました。終了します。')
        send_line_notify(ADMIN_COMPANY + '：ログインに失敗しました。終了します')
        driver.quit()
        sys.exit()

    # time.sleep(7) # 秒
    driver.implicitly_wait(10)

    try:
        # メインメニューでの操作
        # 売買物件検索をクリック※売買物件検索のテキスト部分を変更すれば、検索方法が変わります
        btn_main_menu = driver.find_element(by=By.XPATH, value="//button[contains(@class, 'btn p-button btn-primary btn-block px-0') and contains(., '売買 物件検索')]")
        btn_main_menu.click()
        time.sleep(SEC) # 秒

        # 売買検索条件入力での操作
        # 売土地、売一戸建、売マンション、売外全(住宅以外建物全部)、売外一(住宅以外建物一部)
        property1 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[4]/div/div[2]/div[1]/div[2]/select")
        select_property1 = Select(property1)
        select_property1.select_by_visible_text(PROPERTY_TYPE1)

        # 都道府県の入力。envファイルより取得
        # 所在地１ 都道府県名
        pref1_form1 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[4]/div[2]/div[1]/div/input")
        pref1_form1.send_keys(PREF1_FORM1)

        address1_form1 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[4]/div[3]/div/div[2]/div/div/input")
        address1_form1.send_keys(ADD1_FORM1)

        address2_form1 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[4]/div[4]/div/div[2]/div[1]/div/input")
        address2_form1.send_keys(ADD2_FORM1)

        # 所在地2 都道府県名
        pref1_form2 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[6]/div[2]/div[1]/div/input")
        pref1_form2.send_keys(PREF1_FORM2)

        address1_form2 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[6]/div[3]/div/div[2]/div/div/input")
        address1_form2.send_keys(ADD1_FORM2)

        address2_form2 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[6]/div[4]/div/div[2]/div[1]/div/input")
        address2_form2.send_keys(ADD2_FORM2)

        # 所在地3 都道府県名
        pref1_form3 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[8]/div[2]/div[1]/div/input")
        pref1_form3.send_keys(PREF1_FORM3)

        address1_form3 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[8]/div[3]/div/div[2]/div/div/input")
        address1_form3.send_keys(ADD1_FORM3)

        address2_form3 = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[6]/div/div[8]/div[4]/div/div[2]/div[1]/div/input")
        address2_form3.send_keys(ADD2_FORM3)


        # 検索をクリック
        trade_search = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[2]/div/div/div/div/div[4]/button")
        trade_search.click()

        time.sleep(SEC) # 秒

        # if len(driver.find_elements(By.CLASS_NAME, "page-item")) > 0 :
        #     pages = driver.find_elements(By.CLASS_NAME, "page-item")
        # else:
        #     log.error(ADMIN_COMPANY + '：ページの取得に失敗しました。５００件以上の可能性があります')
        #     send_line_notify(ADMIN_COMPANY + '：ページの取得に失敗しました。５００件以上の可能性があります')
        #     driver.quit()
        #     sys.exit()
        if not len(driver.find_elements(By.CLASS_NAME, "page-item")) > 0 :
            log.error(ADMIN_COMPANY + '：ページの取得に失敗しました。５００件以上の可能性があります')
            send_line_notify(ADMIN_COMPANY + '：ページの取得に失敗しました。５００件以上の可能性があります')
            driver.quit()
            sys.exit()

        # 例 売土地(100件)
        page_count = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[2]/div/div[1]/ul/li/a")
        pages = re.search(r'\d+', page_count.text)
        page_num = math.ceil(int(pages.group()) / 50)
        page = 0
        
        # next_page = 1
        log.info(str(page_count.text))
        log.info('取得ページ数： ' + str(page_num) + 'ページ')
        # print('取得ページ数： ' + str(page_num) + 'ページ')
        # 物件番号保存用
        csvlist2 = []


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

        def bukkenScan(property_num):
                # mysqlに接続してデータの存在有無を確認
                # 物件番号が存在するか確認
                for row in rows:
                    # 一行ずつ調査開始
                    property_result = property_num.text in row
                    # データない場合はNoneなのでifでfalseが返る
                    if property_result:
                        # 存在した場合はデータを代入してfor文を終了
                        bukken_data = row
                        return bukken_data
                        # break
                    # else:
                    #     # データない場合はNoneなのでifでfalseが返る
                    #     bukken_data = None
                    #     return 
                # 存在しない場合はnoneを代入して、処理終了
                bukken_data = None
                return bukken_data

        # ここからスタート
        while True:
            time.sleep(1)
            page = page + 1
            
            # 検索結果一覧画面へ
            # 詳細ボタンの数を取得
            # elementsにすれば複数取得可能
            detail_elems = driver.find_elements(by=By.XPATH, value="//button[contains(@class, 'btn p-button m-0 py-0 btn-outline btn-block px-0') and contains(., '詳細')]")
            detail_count = len(detail_elems)
            log.info(str(page) + '頁目 / ' + str(detail_count) + '件')
            # print(str(page) + '頁目 / ' + str(detail_count) + '件')
            # ページが変わるごとにリセットする
            i = 0

            for i in range(detail_count):
                
                # driveのキャッシュを毎回削除
                driver.execute_script("location.reload(true);")
                time.sleep(2)

                details = driver.find_elements(by=By.XPATH, value="//button[contains(@class, 'btn p-button m-0 py-0 btn-outline btn-block px-0') and contains(., '詳細')]")
                driver.execute_script("arguments[0].click();", details[i])

                # データ取得
                csvlist = []
            
                # 物件取込スタート
                # 物件番号が見つかるまで待機時間を5回繰り返す。ページ遷移時に時間がかかった場合の対応
                start_time = time.perf_counter()
                # log.info(start_time)
                for _ in range(5):
                    time.sleep(SEC) # 秒
                    if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[1]/div/div[2]/div")) > 0 :
                        property_num = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[1]/div/div[2]/div")
                        break
                end_time = time.perf_counter()
                # log.info(end_time)
                # log.info(property_num.text)
                elapsed_time = end_time - start_time
                
                log.info(str(i + 1) + '件目 / 物件取込開始：' + property_num.text + ' / ' + str(elapsed_time) + '秒')
                # print(str(i + 1) + '件目 / 物件取込開始：' + property_num.text + ' / ' + str(elapsed_time) + '秒')
                b_start_time = time.perf_counter()
                
                registration_date = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[2]/div/div[2]/div").text
               
                # r_date = registration_date.text

                # r_date = r_date.replace(' ', '')

                # r_date = changedate.japanese_calendar_converter(r_date)


                # 変更か更新が存在するかチェック
                if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[3]/div/div[1]/span")) > 0 :

                    # 変更か更新が存在する時
                    check_text = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[3]/div/div[1]/span")
                    if check_text.text == "更新年月日":
                        # 更新年月日だった場合、変更年月日はなし
                        update_date = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[3]/div/div[2]/div").text
                        # u_date = update_date
                        # u_date = u_date.replace(' ', '')
                        # u_date = changedate.japanese_calendar_converter(u_date)
                        change_date = None
                    else:
                        # 更新年月日じゃない場合、変更年月日に値を入れる
                        change_date = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[3]/div/div[2]/div").text
                        # c_date = change_date
                        # c_date = c_date.replace(' ', '')
                        # c_date = changedate.japanese_calendar_converter(c_date)

                        if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[4]/div/div[1]/span")) > 0 :
                            # 更新年月日が存在するか確認。存在する場合の処理
                            update_date = driver.find_element(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[1]/div/div[4]/div/div[2]/div").text
                            # u_date = update_date
                            # u_date = u_date.replace(' ', '')
                            # u_date = changedate.japanese_calendar_converter(u_date)
                        else:
                            # 更新年月日が存在しない場合の処理
                            update_date = None
                else:
                    # 変更か更新も存在しない場合
                    update_date = None
                    change_date = None

                # mysqlに接続してデータの存在有無を確認
                # 物件番号が存在するか確認
                # for row in rows:
                #     # 一行ずつ調査開始
                #     property_result = property_num.text in row
                #     if property_result:
                #         # 存在した場合はデータを代入してfor文を終了
                #         bukken_data = row
                #         break
                #     else:
                #         # データない場合はNoneなのでifでfalseが返る
                #         bukken_data = None
                bukken_data = bukkenScan(property_num)

                # 物件番号から、更新日と変更日を比較
                if bukken_data:
                    # update_dateがNoneの場合は空が返る。値ある場合はそのまま代入
                    update_check = update_date or ""
                    change_check = change_date or ""
                    update_result = update_check in bukken_data[1]
                    change_result = change_check in bukken_data[2]
                else:
                    update_result = False
                    change_result = False

                # 削除用のpdfを作成。物件番号だけのcsvに値を代入
                csvlist2.append(property_num.text)
    
                # pdfの存在有無を確認。ない場合はfalse
                # is_path = PDFDIR + '/' + property_num.text + '/' + property_num.text + '_zumen.pdf'
                # exact_file = os.path.isfile(is_path)

                # 変更がない場合は処理をスキップ
                if update_result and change_result :
                    # print(str(i + 1) + '件目 / 変更なし')
                    log.info(str(i + 1) + '件目 / 変更なし')
                    time.sleep(2) # 秒
                    driver.back()
                    time.sleep(2) # 秒
                    # 追記
                    # driver.execute_script("location.reload(true);")
                    # time.sleep(SEC) # 秒
                    # 次の物件番号へ
                    continue

                # 変更がなくて、pdfもない場合の処理
                # elif update_result and change_result and not exact_file:
                #     print(str(i + 1) + '件目 / 変更なし：PDF存在なし')

                #     if len(driver.find_elements(by=By.XPATH, value="//*[@id='__layout']/div/div[1]/div[1]/div/div[21]/div/div/div/div[2]/div[1]")) > 0 :
                #         print('図面PDF保存開始：詳細')
                #         zumen = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[21]/div/div/div/div[2]/div[2]/button")
                #         driver.execute_script("arguments[0].scrollIntoView(true);", zumen)
                #         driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[21]/div/div/div/div[2]/div[2]/button").click()
                #         pdf_flag = downloadChallenge(property_num)
                #         if not pdf_flag:
                #             download_pdf_name = getLatestDownloadedFileName()
                #             pdf_rename = movePdf(download_pdf_name, property_num)
                #             csvlist.append(pdf_rename)
                #     else:
                #         # zumen = None
                #         print('図面PDFは存在しません：skip')
                #     driver.back()
                #     time.sleep(SEC) # 秒
                #     driver.execute_script("location.reload(true);")
                #     time.sleep(SEC) # 秒
                #     # 次の物件番号へ
                #     continue

                # 当てはまらない場合(変更がある)は処理開始
                csvlist.append(property_num.text)
                csvlist.append(registration_date)
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

                b_end_time = time.perf_counter()
                b_elapsed_time = b_end_time - b_start_time
                log.info('物件取込終了：' + str(b_elapsed_time) + '秒')
                # print('物件取込終了：' + str(b_elapsed_time) + '秒')
                # print('物件取込終了')
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

                # photolistの値をcsvに追加画像１～１０までのファイル名
                photo_list = [None,None,None,None,None,None,None,None,None,None]

                def photo_add(photo_list):
                    for p in photo_list:
                        csvlist.append(p)

                SAVEDIR = PUBDIR + '/landimages/' + property_num.text
                
                log.info('画像取込開始：' + property_num.text)
                # print('画像取込開始：' + property_num.text)

                # 画像が０枚の場合はfalse、存在する場合はtrue
                if len(driver.find_elements(by=By.XPATH, value=photos[0])) > 0 :
                    # 保存するフォルダが未作成の場合、新規作成する
                    os.makedirs(SAVEDIR, exist_ok = True)
                    # mx-autoクラスの数を取得
                    images_count = len(driver.find_elements(By.CLASS_NAME, "mx-auto"))
                    # mx-autoの数だけ回す
                    # print('画像枚数：' + str(images_count + 1) + '枚')
                    log.info('画像枚数：' + str(images_count + 1) + '枚')
                    for image_count in range(images_count):
                        # ４枚の場合
                        photo_list[image_count] = property_num.text + "_" + str(image_count + 1) + ".jpg"
                        # print(photo_list[image_count])
                        log.info(photo_list[image_count])
                        # 画像が存在する場合は保存しない
                        exist_path = SAVEDIR + '/' + photo_list[image_count]
                        is_file = os.path.isfile(exist_path)
                        if not is_file:
                            log.info('画像を保存：' + str(image_count + 1) + '枚目 / ' + str(images_count)  + '枚中')
                            # print('画像を保存：' + str(image_count + 1) + '枚目 / ' + str(images_count)  + '枚中')
                            photo_link = driver.find_element(by=By.XPATH, value=photo_links[image_count])
                            imgsave(photo_list[image_count], photo_link, SAVEDIR)
                        else:
                            log.info('画像保存をskip：' + str(image_count + 1) + '枚目 / ' + str(images_count)  + '枚中')
                            # print('画像保存をskip：' + str(image_count + 1) + '枚目 / ' + str(images_count)  + '枚中')
                    # 値を追加
                    photo_add(photo_list)
                    log.info('画像枚数：' + str(image_count + 1) + '枚')
                    # print('画像枚数：' + str(image_count + 1) + '枚')

                else :
                    # 物件画像が存在しないときの処理
                    photo_add(photo_list)
                    log.info('画像枚数：0枚')
                    # print('画像枚数：0枚')
                

                # 物件図面 
                time.sleep(1)
                if len(driver.find_elements(by=By.XPATH, value='/html/body/div/div/div/div[1]/div[1]/div/div[21]/div/div/div/div[2]/div[2]/button')) > 0 :
                    zumen = property_num.text + '_zumen.pdf'
                else:
                    zumen = None
                
                csvlist.append(zumen)
                writer.writerow(csvlist)
                # writer.writerow(csvlist2)
                i += 1
                
                g_count = g_func(g_count)
                log.info( str(i) + '件目を取り込みました / ' + str(page) + '頁目 / ' + property_num.text + ' / 総数' + str(g_count) + '件')

                driver.back()
                time.sleep(2) # 秒


            if page >= page_num:
                # csvを閉じる
                f.close()
                csv_writer2(csvlist2)
                # z.close()
                log.info('正常終了')
                # print('正常終了')
                send_line_notify(ADMIN_COMPANY + '：csvデータの作成完了')
                log.info(now.strftime("========== End：%Y年%m月%d日 %H時%M分%S秒 =========="))
                # print(now.strftime("End：%Y年%m月%d日 %H時%M分%S秒"))
                driver.quit()
                sys.exit()
                # 終了
                # break

            else:
                # 次頁へ移行
                time.sleep(2)
                next_link = driver.find_element(by=By.CSS_SELECTOR, value=".tab-pane > div > div:first-of-type >div > ul.pagination > li.page-item:last-child > button.page-link")
                driver.execute_script("arguments[0].click();", next_link)
                log.info(str(page) + '頁目が終了、次頁へ移行します')
                driver.implicitly_wait(10)
                # time.sleep(SEC) # 秒

    except Exception as e:
        dt_now = datetime.datetime.now()
        send_line_notify(ADMIN_COMPANY + '：取込中にエラーが発生しました')
        log.error(ADMIN_COMPANY + '：取込中にエラーが発生しました')
        log.error(now.strftime("========== End：%Y年%m月%d日 %H時%M分%S秒 =========="))
        log.error(e)
        # print(now.strftime("End：%Y年%m月%d日 %H時%M分%S秒"))
        # print(e)

        f.close()
        # csv_writer2(csvlist2)

        driver.quit()
        sys.exit()
# In[ ]:





# In[ ]:




