FROM php:8.2.3-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    wget \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libaio1 \
    libaio-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    intl \
    gd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Oracle Instant Client
RUN mkdir -p /opt/oracle \
    && wget https://download.oracle.com/otn_software/linux/instantclient/216000/instantclient-basic-linux.x64-21.6.0.0.0dbru.zip \
    && wget https://download.oracle.com/otn_software/linux/instantclient/216000/instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip \
    && unzip instantclient-basic-linux.x64-21.6.0.0.0dbru.zip -d /opt/oracle \
    && unzip instantclient-sdk-linux.x64-21.6.0.0.0dbru.zip -d /opt/oracle \
    && rm -rf *.zip \
    && mv /opt/oracle/instantclient_21_6 /opt/oracle/instantclient

# Set Oracle environment variables
ENV LD_LIBRARY_PATH=/opt/oracle/instantclient

# Install OCI8 and PDO_OCI extensions
RUN echo 'instantclient,/opt/oracle/instantclient/' | pecl install oci8 \
    && docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient \
    && docker-php-ext-install pdo_oci \
    && docker-php-ext-enable oci8

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . /var/www
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
