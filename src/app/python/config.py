# .env ファイルをロードして環境変数へ反映
from dotenv import load_dotenv
load_dotenv()

# 環境変数を参照
import os
REINS_ID = os.getenv('REINS_ID')
REINS_PASS = os.getenv('REINS_PASS')

REINS_PREF1 = os.getenv('REINS_PREF1')
ADD1_FORM1 = os.getenv('REINS_ADD1_FORM1')
ADD2_FORM1 = os.getenv('REINS_ADD2_FORM1')
REINS_PREF2 = os.getenv('REINS_PREF2')
ADD1_FORM2 = os.getenv('REINS_ADD1_FORM2')
ADD2_FORM2 = os.getenv('REINS_ADD2_FORM2')
REINS_PREF3 = os.getenv('REINS_PREF3')
ADD1_FORM3 = os.getenv('REINS_ADD1_FORM3')
ADD2_FORM3 = os.getenv('REINS_ADD2_FORM3')
LINE_TOKEN = os.getenv('LINE_TOKEN')
ADMIN_COMPANY = os.getenv('ADMIN_COMPANY')
# SLACK_PTOKEN = os.getenv('SLACK_PTOKEN')

DB_HOST = os.getenv('DB_HOST')
DB_PORT = os.getenv('DB_PORT')
DB_DATABASE= os.getenv('DB_DATABASE')
DB_USERNAME = os.getenv('DB_USERNAME')
DB_PASSWORD = os.getenv('DB_PASSWORD')
