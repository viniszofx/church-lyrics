FROM php:8.2-apache

# Update system packages and install security updates
RUN apt-get update && apt-get upgrade -y \
  && docker-php-ext-install mysqli \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
