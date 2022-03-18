### 初回起動
docker compose build
docker compose up -d --build
docker compose up -d

### コンテナIN
docker compose exec app bash

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
python3 /var/www/html/app/python/googletest.py

### 登録したコマンド
##### landcsvをインポート
php artisan command:importrecsv

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

### Route 一覧
php artisan route:list

### schedule 一覧
php artisan schedule:list

#### controllerの作成
php artisan make:controller HelloController
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
