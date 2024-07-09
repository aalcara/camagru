# Use a imagem base oficial do PHP com FPM
FROM php:7.4-fpm

RUN docker-php-ext-install mysqli

COPY src/ /var/www/html/

EXPOSE 9000