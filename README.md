### 初回起動
docker compose build  
docker compose up -d  
docker compose up -d --build  

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
python3 /var/www/html/app/python/estate01.py  
python3 /var/www/html/app/python/pdf01.py


python3 /var/www/html/app/python/test.py  
python3 /var/www/html/app/python/estate.py
python3 /var/www/html/app/python/estate01.py   
python3 /var/www/html/app/python/estate02.py
python3 /var/www/html/app/python/estate03.py 
python3 /var/www/html/app/python/pdf01.py
python3 /var/www/html/app/python/estateheadless.py
python3 /var/www/html/app/python/googletest.py  
python3 /var/www/html/app/python/mysqltest.py  
python3 /var/www/html/app/python/dtest.py

find -name image.jpg

scp -p 12521 ubuntu@os3-282-30926.vs.sakura.ne.jp:/home/ubuntu/www/gaia/src/storage/app/ss/screen.png /root/app/gaia/src/storage/app/tmp/screen.png
cp /home/ubuntu/www/gaia/src/storage/app/ss/screen.png ../public/landimages/screen.png

### 登録したコマンド
##### landcsvをインポート
php artisan command:importrecsv  

##### DBに物件番号がない画像を削除
<!-- php artisan command:delimg -->
php artisan command:delland

php artisan command:hello


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

### カラムの追加
php artisan make:migration add_hidden_flag_to_lands_table --table=lands

#### migration後について
先に駅関係のcsvを読み込みましょう

### Route 一覧
php artisan route:list

### schedule 一覧
php artisan schedule:list

#### controllerの作成
php artisan make:controller LandUserController
php artisan make:controller RailwayController
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

chown -R 1000:1000 /yourproject && chmod -R 755 /yourproject
RUN chown www-data:www-data storage/ -R

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

## Dockerのlogを溜めない設定
vi /lib/systemd/system/docker.service
[Service]のExecStartを書き換える
ExecStart=/usr/bin/dockerd -H fd:// --containerd=/run/containerd/containerd.sock --log-opt max-size=10m --log-opt max-file=1
リロードする
systemctl daemon-reload
systemctl reload docker

***
## 新サーバーのセットアップ手順
サーバーはubuntuです  
sshはdesktopを使ってデフォルトでログインできるようにする

#### SSHの設定
sshのポート変更
```
sudo vi /etc/ssh/sshd_config
```
Portの値を変更する  

firewallにポートの穴をあける
`sudo ufw status`で状態を確認
```
sudo ufw enable
sudo ufw allow 2222
sudo ufw allow 587
sudo ufw reload
sudo reboot
```
繋がらない場合は、サクラの管理画面上でパケットフィルタで塞がれている可能性があります
> さくらのVPS > サーバー > グローバルネットワーク > パケットフィルター
  
#### dockerの導入
[公式サイトを参照](https://docs.docker.com/engine/install/ubuntu/)

```
sudo apt-get update
sudo apt-get install ca-certificates curl gnupg lsb-release
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-compose-plugin
apt-cache madison docker-ce
↑で表示されたバージョンを例`5:20.10.17~3-0~ubuntu-focal`をVERSION_STRINGに入れて実行
sudo apt-get install docker-ce=<VERSION_STRING> docker-ce-cli=<VERSION_STRING> containerd.io docker-compose-plugin
sudo docker run hello-world
docker version
docker compose version
```

Dockerの権限を変更
```
sudo chmod 666 /var/run/docker.sock
docker ps
# 無理なら以下も必要かも要確認
sudo gpasswd -a $(whoami) docker
sudo chgrp docker /var/run/docker.sock
sudo service docker restart

```

#### Gitインストール
```
sudo apt install -y git
git --version
#ユーザー名の設定
git config --global user.name "Medjed"

#メールアドレス
git config --global user.email tateyokokumin.siegzeon@gmail.com

#フォルダ作成
mkdir app

#フォルダ移動
cd app
```

#### GithubActionsを使った自動デプロイ設定
[参考サイト](https://hsmtweb.com/tool-service/github-actions.html)
```
# VPS上で入力
ssh-keygen -t rsa -b 4096 -C tateyokokumin.siegzeon@gmail.com
```
実行すると、パスフレーズ の設定を聞かれますが、空にしておきます。  
デフォルトの設定でそのまま作成すると、デプロイサーバー内に~/.ssh/id_rsa と ~/.ssh/id_rsa.pub が生成されているはずです。  
id_rsa が秘密鍵、id_rsa.pub が公開鍵です。

> 公開鍵を GitHub リポジトリの Deploy keys として登録する
Deploy keys の登録をするため、ブラウザで、今回 pull や merge を検知したい GitHub リポジトリを開きます。  
Deploy keys の登録画面は Settings メニューの中にあります。
「Add deploy key」 のボタンをクリックすると、キーを登録できます。
ターミナルの画面に戻り、デプロイサーバー上で下記のコマンドを実行します。
```
cat ~/.ssh/id_rsa.pub
```
表示された中身をまるっとコピーして、Deploy keys の登録画面で「Key」のエリアにペーストします。
「Title」は自分でわかりやすい任意のもので大丈夫です。

「Allow write access」のチェックは、デプロイサーバー側から GitHub リポジトリに対して push する必要がある場合にチェックを入れます。
今回は、GitHub → デプロイサーバー への一方通行なので、チェックは外しておきます
「Add Key」 ボタンを押下して、Deploy keys の登録完了

ターミナルでデプロイサーバーにSSH接続していることを確認し、実際にデプロイしたいディレクトリまで移動した上で、git clone します。
git clone git@github.com:medjedcorp/gaia.git

pull もちゃんとできるか確認
cd gaia
git pull origin main

> GitHub Actions の Workflow を作成する
Deploy keys を登録した時と同じように、ブラウザでGitHubのリポジトリを開き、Settings メニューから Secrets の登録画面を開きます。
「New repository secret」ボタンを押下して、変数を登録していきましょう。
同じ変数名は登録できないので、数値や記号で追記しましょう
SERVER_USERNAME → SERVER_USERNAME_KKとか

| Name（任意のわかりやすいものでOK | 記載する内容                                                                                   | 例                                                                                                                                                                                                                                    | 
| -------------------------------- | ---------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | 
| SERVER_USERNAME                  | デプロイサーバーログインのusername                                                             | ubuntu（サーバー情報を確認してください）※同じでも問題なし                                                                                                                                                                            | 
| SERVER_HOST                      | サーバーのホスト                                                                               | os3-286-32835.vs.sakura.ne.jp（サーバー情報を確認してください）                                                                                                                                                                       | 
| SSH_PORT                         | サーバーにSSH接続するためのポート<br>※ 通常22ですが、サーバーによっては異なります。           | 2273（変更した値）                                                                                                                                                                                                                    | 
| SSH_PRIVATE_KEY                  | デプロイサーバーへSSH接続するための秘密鍵。<br>※ 先ほどサーバー上で生成したものではありません | デプロイサーバーへのSSH接続で利用しているものです。ローカルでターミナルを起動して、cat ~/.ssh/desktopを実行し、表示された中身を貼り付けます。<br>秘密鍵のファイル名に合わせてコマンドの内容を変えてください。※同じならそのままでも可 | 
| SSH_PASS                         | 上記の秘密鍵とセットになっているパスワード                                                     | パスフレーズを設定している場合は必要です。                                                                                                                                                                                            | 
| SERVER_DEPLOY_DIR                | 先ほどデプロイサーバー上で pull のテストをしたディレクトリパス                                 | ~/app/gaia                                                                                                                                                                                                                            | 
> Workflow を作成する
github actionsに以下の内容を登録する。変数は変更してね。
```
name: CI-KK

on:
  push:
    branches:
      - main

jobs:
  build:
    runs-on: ubuntu-latest

    steps:
      - name: Deploy
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SERVER_HOST_KK }}
          username: ${{ secrets.SERVER_USERNAME }}
          port: ${{ secrets.SSH_PORT_KK }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd ${{ secrets.SERVER_DEPLOY_DIR_APP_GAIA }}
            git pull origin main
```

###### 細かい設定
.envファイルの設定×２枚
/home/ubuntu/app/gaia/docker/nginx/default.conf をローカルからコピー
以下をドメイン名に変更
server_name areas-kk.com;

googlemapを使えるように、IPアドレスを追加
メールアドレスはサーバー契約。gmailでもいいかも？
dokcer compose up -d

#### dokcer compose up -d 後
コンテナ内に入る  
dokcer compose exec app bash 
composer update  
phpに問題ないか確認  
php artisan --version

アプリケーションキーを生成  
php artisan key:generate


起動
php artisan storage:link
chmod -R 777 storage
php artisan queue:work

URLを入力して確認！
mysqlworkベンチを設定。アカウントにsystemかadminを付与
電車系csvを読み込んでおく
mysqlworkの設定例  
![mysqlworkの設定例](https://gaia.medjed.jp/images/workbenchsetting2.gif)

DBの作成
/home/ubuntu/app/gaia/src/database/seeders/DatabaseSeeder.php  
$this->call(UsersSeeder::class);
userのseedをコメントアウトしておく
php artisan migrate --seed  
コメントアウト戻しておく


