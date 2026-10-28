FROM php:8.2-apache

# Install required system dependencies for GD, Zip, and PDO
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite & mod_headers
RUN a2enmod rewrite headers

# Configure Apache DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides in DocumentRoot
RUN echo '<Directory /var/www/html/public>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/override.conf \
    && a2enconf override

# Copy source code
WORKDIR /var/www/html
COPY . /var/www/html

# Create downloads and uploads directories and set permissions
RUN mkdir -p /var/www/html/downloads /var/www/html/public/assets/uploads \
    && chown -R www-data:www-data /var/www/html/downloads /var/www/html/public/assets/uploads \
    && chmod -R 775 /var/www/html/downloads /var/www/html/public/assets/uploads

EXPOSE 80
CMD ["apache2-foreground"]
