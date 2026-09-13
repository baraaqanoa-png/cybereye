FROM richarvey/nginx-php-fpm:3.1.6

# نسخ ملفات المشروع كاملة
COPY . /var/www/html

# تحميل وتثبيت Composer يدوياً للتأكد من توفره أثناء البناء
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# تثبيت الاعتماديات الخاصة بإنتاج لاراول
RUN composer install --no-dev --optimize-autoloader --no-interaction

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