FROM php:7.2-alpine

# Libs
RUN docker-php-ext-install pdo_mysql

# composer - required for symfony/phpunit-bridge
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN mv composer.phar /usr/bin/composer
RUN php -r "unlink('composer-setup.php');"
