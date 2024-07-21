FROM php:7.4-fpm

RUN docker-php-ext-install mysqli

COPY src/ /var/www/html/

# RUN mkdir -p -v /var/www/uploads
# RUN chown -R www-data:www-data /var/www/uploads

EXPOSE 9000