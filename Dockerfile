FROM drupal:9.2-apache

RUN apt-get update && apt-get install -y \
	curl \
	git \
  default-mysql-client \
	vim \
	wget

#Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
	php composer-setup.php && \
	mv composer.phar /usr/local/bin/composer && \
	php -r "unlink('composer-setup.php');"

# Install drush
RUN wget -O drush.phar https://github.com/drush-ops/drush-launcher/releases/latest/download/drush.phar && \
	chmod +x drush.phar && \
	cp -Rf drush.phar /usr/local/bin/drush

#Install drupal console
RUN composer require drupal/console:~1.0 --prefer-dist --optimize-autoloader

RUN rm -rf /var/www/html/*

COPY apache-drupal.conf /etc/apache2/sites-enabled/000-default.conf
COPY docker-php-memlimit.ini /usr/local/etc/php/conf.d/docker-php-memlimit.ini

WORKDIR /app
