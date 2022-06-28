import glob
import time
import os
import shutil

def downloadChallenge(TMPDIR):
    timeout_second = 25
    for k in range(timeout_second + 1):
        download_fileName = glob.glob(TMPDIR + '/*.*')
        # print(str(download_fileName) + '：ディレクトリ')
        # print(TMPDIR + '：TMPDIRディレクトリ')
        # ファイルが存在する場合
        if download_fileName:
            # 拡張子の抽出
            extension = os.path.splitext(download_fileName[0])
            # print('extension:' + str(extension))
            # 拡張子が '.crdownload' ではない ダウンロード完了 待機を抜ける
            if ".crdownload" not in extension[1]:
                time.sleep(2)
                print('図面PDF保存完了 / ' + str(k + 1) + '秒')
                return True
                # 指定時間待っても .crdownload 以外のファイルが確認できない場合 エラー
        if k >= timeout_second:
            # == エラー処理をここに記載 ==
            # 終了処理
            print('図面PDF取得失敗')
            return False
        # 一秒待つ
        time.sleep(1)

# 最新のダウンロードファイル名を取得
def getLatestDownloadedFileName(TMPDIR):
    list_of_files = glob.glob(TMPDIR + '/*')
    if len(os.listdir(TMPDIR + '/')) == 0:
        return None
    return max (
        list_of_files, 
        key=os.path.getctime
    )


def movePdf(download_pdf_name, TMPDIR, property_num):
    pdf_rename = property_num + '_zumen.pdf'
    re_pdf_path = TMPDIR + '/' + pdf_rename
    # ファイル名を変更 日本語対応
    os.rename(download_pdf_name, re_pdf_path) 

    # フォルダの振り分け
    PDFDIR = '/var/www/html/storage/app/pdfs'
    chk_pdf = PDFDIR + '/' + pdf_rename
    if(os.path.isfile(chk_pdf)):
        os.remove(chk_pdf)
    shutil.move(re_pdf_path, PDFDIR + '/')