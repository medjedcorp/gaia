import PyPDF2

org_fileName = 'file.pdf'  # 分割したいファイルのファイル名
new_fileName = 'page'  # 分割後のファイル名


def splitPDF(src_path, new_basepath):
    org_pdf = PyPDF2.PdfFileReader(src_path)
    for i in range(org_pdf.numPages):
        new_pdf = PyPDF2.PdfFileWriter()
        new_pdf.addPage(org_pdf.getPage(i))
        i = '{0:02}'.format(i)
        with open('{}_{}.pdf'.format(new_basepath, i), 'wb') as f:
            new_pdf.write(f)


splitPDF(org_fileName, new_fileName)


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
                print(property_num.text + '：図面PDF保存完了 / ' + str(k + 1) + '秒')
                # pdf_flag = False
                return False
                # break6
                # 指定時間待っても .crdownload 以外のファイルが確認できない場合 エラー
        if k >= timeout_second:
            # == エラー処理をここに記載 ==
            # 終了処理
            print(property_num.text + '：図面PDF取得失敗')
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