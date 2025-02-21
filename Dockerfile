# Використовуємо офіційний образ PHP-FPM з PHP 8.2
FROM php:8.2-fpm

# Встановлюємо необхідні системні залежності для Symfony та cron
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    cron \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Встановлюємо Composer глобально
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Встановлюємо робочу директорію
WORKDIR /var/www/html

# Копіюємо код додатку в контейнер
COPY . /var/www/html

# Встановлюємо права доступу для користувача www-data
RUN chown -R www-data:www-data /var/www/html

COPY crontab /etc/cron.d/cron-schedule

RUN chmod 0644 /etc/cron.d/cron-schedule

RUN crontab /etc/cron.d/cron-schedule

CMD ["sh", "-c", "cron && php-fpm"]
