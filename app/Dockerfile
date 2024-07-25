FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install mysqli

COPY src/ /var/www/html/

# RUN mkdir -p -v /var/www/uploads
# RUN chown -R www-data:www-data /var/www/uploads

EXPOSE 9000