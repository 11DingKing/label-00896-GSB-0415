#!/bin/bash
set -e

# 确保运行时目录存在
mkdir -p /var/www/html/runtime/cache
mkdir -p /var/www/html/runtime/log
mkdir -p /var/www/html/runtime/schema
mkdir -p /var/www/html/runtime/session
mkdir -p /var/www/html/public/uploads

# 初始化数据库（如果不存在）
if [ ! -f /var/www/html/runtime/database.db ]; then
    echo "Initializing database..."
    sqlite3 /var/www/html/runtime/database.db < /var/www/html/database/schema.sql
    echo "Database initialized successfully."
fi

# 设置权限
chown -R www-data:www-data /var/www/html/runtime
chown -R www-data:www-data /var/www/html/public/uploads
chmod -R 777 /var/www/html/runtime
chmod -R 777 /var/www/html/public/uploads

# 启动 Apache
exec apache2-foreground
