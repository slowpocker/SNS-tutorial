#FROM：どのOSやミドルウェアの環境を作るか
#イメージビルドのためのベースイメージを設定する
FROM php:8.0-fpm-buster

#SHELL ["/bin/sh", "-c"] がデフォルト。必須ではない
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

#ENV命令はコンテナ内のサーバー環境変数を設定する
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer

#COPY：どのファイルをホストPCからコンテナに移動させるか
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

#RUN：Imageを作る時に、どういうコマンドを実行するか
RUN apt-get update && \
    apt-get -y install git unzip libzip-dev libicu-dev libonig-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install intl pdo_mysql zip bcmath

COPY ./php.ini /usr/local/etc/php/php.ini

WORKDIR /work