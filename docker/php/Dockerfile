FROM php:8.2.19-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
    && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
    && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update

WORKDIR /var/www

# システムのパッケージリストを更新し、必要なパッケージをインストールします
RUN apt-get update && apt-get install -y \
    cron \
    && apt-get clean

# crontabファイルを作成し、適切な場所に配置します
COPY my-cron-file /etc/cron.d/my-cron-file

# crontabファイルに実行権限を付与します
RUN chmod 0644 /etc/cron.d/my-cron-file

# crontabファイルをcrontabに追加します
RUN crontab /etc/cron.d/my-cron-file

# crontabログの出力先を設定します
RUN touch /var/log/cron.log

# スクリプトを作成して、PHP-FPMとcronを同時に実行します
COPY start.sh /start.sh
RUN chmod +x /start.sh

# エントリーポイントスクリプトを実行します
CMD ["/start.sh"]