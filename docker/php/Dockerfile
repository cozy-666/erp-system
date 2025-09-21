FROM php:8.2-fpm

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor

# PHP拡張をインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nginx設定
COPY nginx.conf /etc/nginx/sites-available/default

# Supervisor設定
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 作業ディレクトリ
WORKDIR /var/www

# 権限設定
RUN chown -R www-data:www-data /var/www

# ポート公開
EXPOSE 80

# 起動
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]