### 初回起動
docker compose build
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

#### migrate コマンド
php artisan migrate --seed
php artisan migrate:fresh --seed

### Route 一覧
php artisan route:list

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

##### **以下注意事項。必要に応じて設定してください**
権限設定など、permissionエラーがでる場合
```

chmod -R 777 storage/framework/sessions/
chmod -R 777 storage/logs vendor
chmod -R 777 storage/framework/views
chmod -R 777 storage/logs/laravel.log
chown www-data storage/ -R  
```

**configファイルが読み取れない場合など**
キャッシュ削除
`$ php artisan cache:clear`
`$ php artisan optimize`
`$ php artisan clear-compiled`
`$ php artisan view:cache`
`$ php artisan config:cache`
`$ composer install --optimize-autoloader --no-dev`
`$ composer dump-autoload`
`$ php artisan route:cache`  

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
