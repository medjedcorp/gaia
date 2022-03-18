# .env ファイルをロードして環境変数へ反映
from dotenv import load_dotenv
load_dotenv()

# 環境変数を参照
import os
REINS_ID = os.getenv('REINS_ID')
REINS_PASS = os.getenv('REINS_PASS')
REINS_ID = os.getenv('REINS_ID')
REINS_PREF1 = os.getenv('REINS_PREF1')
REINS_LOCATION1 = os.getenv('REINS_LOCATION1')
REINS_PREF2 = os.getenv('REINS_PREF2')
REINS_LOCATION2 = os.getenv('REINS_LOCATION2')
