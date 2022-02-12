初回起動
docker compose build
docker compose up -d

コンテナIN
docker compose exec app bash

ノード最新版install
docker pull node

docker compose down

npm run watch

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