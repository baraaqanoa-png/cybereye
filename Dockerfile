FROM serversideup/php:8.3-fpm-nginx

COPY . /var/www/html

USER root
RUN chown -R www-data:www-data /var/www/html
USER www-data

# تحديد مسار الـ public الخاص بلاراول ليعمل Nginx بشكل صحيح
ENV WEBROOT=/var/www/html/public
ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 8080