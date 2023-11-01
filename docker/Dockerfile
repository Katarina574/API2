# Use a Debian-based PHP image
FROM php:8.2-fpm

# Install required dependencies and tools
RUN apt-get update && apt-get install -y \
   git \
   libpcre3-dev && \
   if ! pecl list | grep -q "psr"; then \
   pecl install psr && \
   echo "extension=psr.so" > /usr/local/etc/php/conf.d/29-psr.ini; \
   fi && \
  if ! pecl list | grep -q "phalcon"; then \
   pecl install phalcon && \
   echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/30-phalcon.ini; \
  fi && \
   docker-php-ext-install pdo pdo_mysql && \
   git clone https://github.com/phalcon/phalcon-devtools.git /usr/local/src/phalcon-devtools && \
   ln -s /usr/local/src/phalcon-devtools/phalcon /usr/local/bin/phalcon && \
   chmod +x /usr/local/src/phalcon-devtools/phalcon && \
   curl -sS https://getcomposer.org/installer -o composer-setup.php \
       && php composer-setup.php --install-dir=/usr/local/bin --filename=composer --version=2.2.21 \
       && rm composer-setup.php
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Start PHP-FPM
CMD ["php-fpm"]