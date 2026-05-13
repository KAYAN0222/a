FROM php:8.2-apache

# تثبيت الحزم المطلوبة للنظام
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# تفعيل mod_rewrite الخاص بـ Apache ليعمل توجيه Laravel
RUN a2enmod rewrite

# توجيه الخادم لقراءة ملفات المشروع من مجلد public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# تحديد مسار العمل
WORKDIR /var/www/html

# نسخ ملفات المشروع إلى الحاوية
COPY . /var/www/html

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تحميل حزم Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts 
# إعطاء الصلاحيات اللازمة لمجلدات التخزين
# إنشاء كامل بنية المجلدات المطلوبة للتخزين والمؤقتات
RUN mkdir -p /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/framework/cache \
             /var/www/html/bootstrap/cache

# إعطاء الصلاحيات الكاملة للمستخدم www-data على كامل المجلد
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
EXPOSE 80
