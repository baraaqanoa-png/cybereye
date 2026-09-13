FROM richarvey/nginx-php-fpm:3.1.6

# نسخ ملفات Composer أولاً للاستفادة من الكاش
COPY composer.json composer.lock /var/www/html/

WORKDIR /var/www/html

# تثبيت الاعتماديات بدون تفاعلات
RUN composer install --no-dev --optimize-autoloader --no-interaction

# نسخ باقي ملفات المشروع
COPY . /var/www/html

ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]