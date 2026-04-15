# 企业网站管理系统 Dockerfile
# 支持 ARM64 和 AMD64 架构

FROM php:8.2-apache

# 设置工作目录
WORKDIR /var/www/html

# 替换为阿里云镜像源
RUN sed -i 's|deb.debian.org|mirrors.aliyun.com|g' /etc/apt/sources.list.d/debian.sources \
    && sed -i 's|security.debian.org|mirrors.aliyun.com|g' /etc/apt/sources.list.d/debian.sources

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsqlite3-dev \
    sqlite3 \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# 安装 PHP 扩展
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_sqlite \
    zip \
    gd \
    fileinfo

# 启用 Apache 模块
RUN a2enmod rewrite headers

# 配置 Apache
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/thinkphp.conf \
    && a2enconf thinkphp

# 修改 Apache 默认站点配置
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 安装 Composer（直接下载，避免拉取镜像问题）
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# 复制项目文件
COPY . /var/www/html/

# 设置 Composer 阿里云镜像
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# 安装 PHP 依赖
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 创建运行时目录
RUN mkdir -p runtime/cache runtime/log runtime/schema runtime/session public/uploads

# 复制并设置启动脚本
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 设置文件权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 runtime public/uploads

# 暴露端口
EXPOSE 80

# 使用启动脚本
ENTRYPOINT ["docker-entrypoint.sh"]
