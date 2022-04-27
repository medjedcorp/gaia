import PyPDF2

def downloadChallenge():
    timeout_second = 25
    for k in range(timeout_second + 1):
        download_fileName = glob.glob(TMPDIR + '/*.*')
        # ファイルが存在する場合
        if download_fileName:
            # 拡張子の抽出
            extension = os.path.splitext(download_fileName[0])
            # 拡張子が '.crdownload' ではない ダウンロード完了 待機を抜ける
            if ".crdownload" not in extension[1]:
                time.sleep(2)
                print('図面PDF保存完了 / ' + str(k + 1) + '秒')
                # pdf_flag = False
                return False
                # break6
                # 指定時間待っても .crdownload 以外のファイルが確認できない場合 エラー
        if k >= timeout_second:
            # == エラー処理をここに記載 ==
            # 終了処理
            print('図面PDF取得失敗')
            return True
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

def movePdf(download_pdf_name, property_num):
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

def splitPDF(src_path, new_pdf):
    org_pdf = PyPDF2.PdfFileReader(src_path)
    for i in range(org_pdf.numPages):
        new_pdf = PyPDF2.PdfFileWriter()
        new_pdf.addPage(org_pdf.getPage(i))
        with open('{}_zumen.pdf'.format(new_pdf[i]), 'wb') as f:
            new_pdf.write(f)

def getelem(detail_count):
    driver.find_elements(by=By.XPATH, value="//button[contains(@class, 'btn p-button m-0 py-0 btn-outline btn-block px-0') and contains(., '詳細')]")

    for p in range(detail_count):
        bukken_num = []
        zumen_check = []
        try:
            zumen_btn = driver.find_element(by=By.CSS_SELECTOR, value="div.tabs > div.tab-content > div.tab-pane > div > div.p-table.small > div.p-table-body > div:nth-child(" + str(p + 1) + ") > div:nth-child(2) > div > div > label")
            driver.execute_script("arguments[0].click();", zumen_btn)
            zumen_check.append(zumen_btn)
        except:
            # 例外が発生した場合
            print('図面なし')
        else:
            # 例外が発生しなかった場合
            bukken = driver.find_element(by=By.CSS_SELECTOR, value="div.tab-content > div > div > div.p-table.small > div.p-table-body > div:nth-child(" + str(p + 1) + ") > div:nth-child(4)")
            bukken_num.append(bukken.text)
        time.sleep(1)

        if p == 19 or p == 39:
            # 一括取得ボタンをクリック
            driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[2]/div/div/div/div/div/div[1]/button").click()
            # ダウンロード確認
            pdf_flag = downloadChallenge()
            # ダウンロード後にクリックしてチェックを外す
            for x in zumen_check
                driver.execute_script("arguments[0].scrollIntoView(true);", x)
                x.click()
                time.sleep(1)
            zumen_check = []

        if not pdf_flag:
            download_pdf_name = getLatestDownloadedFileName()

            print(download_pdf_name)
            # 分割処理
            splitPDF(download_pdf_name, bukken_num)

            # # 分割したファイルを移動
            # movePdf(download_pdf_name, property_num)

    # 終了後配列に値がある場合は一括取得をクリック
    if zumen_check:
        driver.find_element(by=By.XPATH, value="/html/body/div/div/div/div[2]/div/div/div/div/div/div[1]/button").click()
        pdf_flag = downloadChallenge()

        if not pdf_flag:
            download_pdf_name = getLatestDownloadedFileName()
            splitPDF(download_pdf_name, bukken_num)
            # movePdf(download_pdf_name, property_num)

