### 初回起動
docker compose build  
docker compose up -d  
docker compose up -d --build  
※CPU使用率制限
docker compose --compatibility up -d  
docker compose --privileged up -d --build  

### コンテナIN
docker compose exec app bash
docker compose exec python bash
docker compose exec db bash

docker compose --compatibility exec python bash

### ノード最新版install
docker pull node

### Docker 終了
###### コンテナOUT
exit  
Docker 終了  
docker compose down  

npm run watch


### cron 動作確認と設定
service cron status  
service cron start  

cron -f 

vi /etc/cron.d/crontab
crontabに最後改行を入れる

### python 実行
python3 /var/www/html/app/python/test.py  
python3 /var/www/html/app/python/estate.py  
python3 /var/www/html/app/python/estateheadless.py
python3 /var/www/html/app/python/googletest.py  
python3 /var/www/html/app/python/mysqltest.py  

scp -p 12521 ubuntu@os3-282-30926.vs.sakura.ne.jp:/home/ubuntu/www/gaia/src/storage/app/ss/screen.png /root/app/gaia/src/storage/app/tmp/screen.png
cp /home/ubuntu/www/gaia/src/storage/app/ss/screen.png ../public/landimages/screen.png

### 登録したコマンド
##### landcsvをインポート
php artisan command:importrecsv
##### DBに物件番号がない画像を削除
<!-- php artisan command:delimg -->
php artisan command:delland

###### csvのインポート時エラーになる場合
ファイルの場所  
src\app\Console\Commands\ImportReCsv.php

lineコードの検索で引っかかっている可能性があります。  
var_dumpコマンドを使用。  
exception_line配列にキーと値を追記してください。  
変換したい値 => csvの値  

document.querySelectorAll('div.row.mb-3.align-items-center > div.d-flex.flex-horizontal.align-items-center.col-6 > ul > li:last-child > button')

php artisan queue:work

ファイル情報
cd /var/www/log


### Docker コマンド色々
docker compose ps  
docker compose logs  
docker system prune  

#### migrate コマンド
php artisan migrate --seed  
php artisan migrate:fresh --seed  
php artisan make:model land_line --controller --migration  
php artisan make:model land_station --controller --migration  
php artisan make:model land_train --controller --migration  

#### migration後について
先に駅関係のcsvを読み込みましょう

### Route 一覧
php artisan route:list

### schedule 一覧
php artisan schedule:list

#### controllerの作成
php artisan make:controller LandUserController
php artisan make:controller UserController --resource
php artisan make:controller TranisImportController --model=Customer

#### modelの作成(単数系)
php artisan make:model Train

### Request バリデーションの作成
php artisan make:request UserRequest
### seederの作成
php artisan make:seeder PrefectureSeeder

``` Dockerfile 修正
# Composer install
COPY --from=composer /usr/bin/composer /usr/bin/composer

# install Node.js
COPY --from=node /usr/local/bin /usr/local/bin
COPY --from=node /usr/local/lib /usr/local/lib
```
##### **以下注意事項。必要に応じて設定してください**
権限設定など、permissionエラーがでる場合
```
chmod -R 777 storage
chmod -R 777 storage/framework/sessions/
chmod -R 777 storage/logs vendor
chmod -R 777 storage/framework/views
chmod -R 777 storage/logs/laravel.log
chown www-data storage/ -R  
```

**configファイルが読み取れない場合など**
キャッシュ削除
php artisan cache:clear
php artisan optimize
php artisan clear-compiled
composer dump-autoload

composer install --optimize-autoloader --no-dev
php artisan route:cache
php artisan config:cache
php artisan view:cache

**composerのインストール**  
`$ composer install`

**laravelのキーを作成**  
`php artisan key:generate`

**シンボリックリンクを作成**  
`$ php artisan storage:link`

**composerのアップデート(Class "Faker\Factory" not found でる場合)**
composer update

##### Gitコマンドまとめ  
**現在のブランチを確認**  
`$ git branch`

**ブランチの作成**  
`$ git branch ブランチ名`

**ブランチの移動**  
`$ git checkout 移動先ブランチ名`  
`$ git checkout -b 移動先新規ブランチ名`

**ブランチをコミット**  
`$ git add .`  
`$ git commit -m 'コミット名'`

**ブランチをGitHubへプッシュ**  
masterブランチへはアップしないでください  
`$ git push origin ブランチ名`

**現在の状態を確認**  
`$ gitgit status`

**差分を確認  
`$ git diff --cached`

**ブランチ名を変更**  
`$ git branch -m oldbranchname newbranchname`

**ブランチ削除**  
`$ git branch -D ブランチ名`

**gitのユーザー名とメール確認**  
`$ git config --list | grep user`

**リモートリポジトリ先の確認**  
`$ git remote -v`


## chrome driverのバージョン対応表
https://chromedriver.chromium.org/downloads

### 探すコマンド
which google-chrome-stable

### cron系コマンド
###### 内容確認
crontab -e
###### 一覧表示
crontab -l
file -i /etc/cron.d/crontab
ls -la /etc/ | grep cron
ロックファイルの削除
rm /var/run/crond.pid
cron 動作状況 コンテナ外
docker container exec -it gaia-web-1 tail -f /var/log/cron.log
cronステータス
service cron status
cronスタート
service cron start

### 信頼できるプロキシの設定 全て許可してるからアップ後確認
ローカルをhttps化したときに、URLがhttpになる問題を解決。
ローカルもhttpsに変更されます。
https://readouble.com/laravel/8.x/ja/requests.html#configuring-trusted-proxies
App\Http\Middlewar\TrustProxies.php

### 確認項目
・画像がある場合は保存しない設定にしてるけど、毎日更新した方が良いか？

### SSH 接続
ssh ubuntu@os3-282-30926.vs.sakura.ne.jp

### さくら初期設定
https://laraweb.net/environment/8287/
サーバー初期設定
パケットフィルタ利用する　SSHとWEB
サーバーへのSSH キー登録
SSHパスワードを利用したログインを無効にする

↓コピー
sudo cp /etc/ssh/sshd_config /etc/ssh/sshd_config.org
vi /etc/ssh/sshd_config
↓変更
PermitRootLogin no
↓リスタート
/etc/init.d/ssh restart

firewallの設定
https://ja.linux-console.net/?p=141

ssh-keygen -t rsa -b 4096 -C tateyokokumin.siegzeon@gmail.com
git clone https://github.com/medjedcorp/rocker.git rocker

find . -name cert.pem | xargs -n 1 -I{} sh -c 'echo {}; openssl x509 -noout -dates -in {} | grep notAfter'
docker exec nginx-proxy-acme /app/cert_status
docker exec nginx-proxy-acme /app/force_renew

./docker/encrypt/certs/rocker.medjed.jp/cert.pem

sudo vi /etc/nginx/sites-enabled/medjed.jp.conf
sudo nginx -t
sudo systemctl reload nginx
sudo ln -s /etc/nginx/sites-available/medjed.jp.conf /etc/nginx/sites-enabled/medjed.jp.conf

#cron 動いてるか確認
service cron status
#cron起動
service cron start