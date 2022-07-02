#!/usr/bin/env python
# coding: utf-8

# In[ ]:

import datetime
import config
import getpdf

#ログイン情報：ユーザー名
USER_ID = config.REINS_ID
#ログイン情報：パスワード
PASS = config.REINS_PASS
# 物件種別１ 必須項目
PROPERTY_TYPE1 = "売土地"
# 所在地・沿線
# 所在地範囲選択１
# 都道府県名
PREF1_FORM1 = config.REINS_PREF7
ADD1_FORM1 = config.ADD1_FORM7
ADD2_FORM1 = config.ADD2_FORM7
PREF1_FORM2 = config.REINS_PREF8
ADD1_FORM2 = config.ADD1_FORM8
ADD2_FORM2 = config.ADD2_FORM8
PREF1_FORM3 = config.REINS_PREF9
ADD1_FORM3 = config.ADD1_FORM9
ADD2_FORM3 = config.ADD2_FORM9

LINE_TOKEN = config.LINE_TOKEN
ADMIN_COMPANY = config.ADMIN_COMPANY


# CSVの準備
# CSVDIR = '/var/www/html/storage/app/csv/land'
# csv_date = datetime.datetime.now().strftime("%Y%m%d")

getpdf.getpdf(USER_ID, PASS, PROPERTY_TYPE1,PREF1_FORM1,ADD1_FORM1,ADD2_FORM1,PREF1_FORM2,ADD1_FORM2,ADD2_FORM2,PREF1_FORM3,ADD1_FORM3,ADD2_FORM3,LINE_TOKEN,ADMIN_COMPANY)

# In[ ]:





# In[ ]:




