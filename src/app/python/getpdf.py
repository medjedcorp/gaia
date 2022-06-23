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
import logging
import logging.handlers
import math
# import psutil
# import slackweb
def getestate(USER_ID, PASS, PROPERTY_TYPE1,PREF1_FORM1,ADD1_FORM1,ADD2_FORM1,PREF1_FORM2,ADD1_FORM2,ADD2_FORM2,PREF1_FORM3,ADD1_FORM3,ADD2_FORM3,LINE_TOKEN,ADMIN_COMPANY,DB_HOST,DB_PORT,DB_DATABASE,DB_USERNAME,DB_PASSWORD,CSV_NAME,CSV_NAME2):
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
    PUBDIR = '/var/www/html/storage/app/public'
    PDFDIR = '/var/www/html/storage/app/pdfs'

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
    log.info(LOGIN_URL + 'にログインします')
    for _ in range(3):
        try:
            driver.get(LOGIN_URL)
        except Exception as e:
            log.warning('ログインエラー：リトライします')
            time.sleep(4)
        else:
            log.info('ログイン成功')
            break

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
            # ページが変わるごとにリセットする
            i = 0

            for i in range(detail_count):
                
                # driveのキャッシュを毎回削除
                # driver.execute_script("location.reload(true);")
                time.sleep(2)

                property_num = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[2]/div/div[2]/div/div/div[2]/div[2]/div["+ str(i + 1) +"]/div[4]").text
                detail_elems = driver.find_element(by=By.XPATH, value="//button[contains(@class, 'btn p-button m-0 py-0 btn-outline btn-block px-0') and contains(., '詳細')]")

                zumen_btn = driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[1]/div[1]/div/div[2]/div/div[2]/div/div/div[2]/div[2]/div["+ str(i + 1) +"]/div[27]/button")
                driver.execute_script("arguments[0].click();", zumen_btn)

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

                b_end_time = time.perf_counter()
                b_elapsed_time = b_end_time - b_start_time
                log.info('物件取込終了：' + str(b_elapsed_time) + '秒')
               

                # 物件図面 
                time.sleep(1)

                zumen = None

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




