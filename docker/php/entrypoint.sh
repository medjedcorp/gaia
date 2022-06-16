#!/bin/sh

# エラーが起きたらスクリプトを強制終了する。
set -e

# デフォルトの引数はCMDで指定している『php-fpm』のみ
# 1番目の引数が『-』から始まる場合（1番目の引数が-fとか-eとかである場合）
if [ "${1#-}" != "$1" ]; then
	# 1番目の引数に『php-fpm』を加える
	set -- php-fpm "$@"
fi

# 全ての引数を実行する
exec "$@"

. /usr/local/bin/docker-php-entrypoint php-fpm