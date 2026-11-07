# Deployment Guide: ESG Reporting Platform

This guide outlines deployment on standard Ubuntu Linux VPS (22.04 / 24.04 LTS) and FastPanel-managed servers.

## Prerequisites

- **PHP**: 8.3 or 8.4 (`ext-pdo_mysql`, `ext-redis`, `ext-mbstring`, `ext-bcmath`, `ext-gd`, `ext-intl`, `ext-curl`, `ext-zip`)
- **Web Server**: Nginx
- **Database**: MySQL 8.0+ or MariaDB 10.6+
- **In-Memory Store**: Redis (for Queue, Horizon, and Caching)
- **Node.js**: 20+ (for asset compilation)
- **Composer**: 2.7+

---

## FastPanel Installation Steps

1. In FastPanel, click **Create Site** -> Domain: `esg.yourdomain.com` -> Select **PHP 8.3-FPM** and **Nginx**.
2. Create a MySQL database and user via the **Databases** tab in FastPanel.
3. SSH into the server or open the built-in terminal:
   ```bash
   cd /var/www/esg-platform
   git clone <repository_url> .
   composer install --no-dev --optimize-autoloader
   cp .env.example .env
   php artisan key:generate
   ```
4. Edit `.env` with MySQL credentials, Redis connection, and application URL:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://esg.yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=esg_production
   DB_USERNAME=esg_user
   DB_PASSWORD=your_secure_password

   QUEUE_CONNECTION=redis
   CACHE_STORE=redis
   SESSION_DRIVER=redis
   ```
5. Run migrations and seed the production baseline / initial demo:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
6. Optimize caches:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan filament:cache-components
   ```
7. Configure Scheduled Cron in FastPanel:
   - Command: `php /var/www/esg-platform/artisan schedule:run`
   - Schedule: `* * * * *` (Every minute)

---

## Horizon & Worker Setup

Copy `deploy/supervisord.conf` into `/etc/supervisor/conf.d/esg-platform.conf`:
```bash
sudo cp deploy/supervisord.conf /etc/supervisor/conf.d/esg-platform.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start esg-horizon
```

Check status:
```bash
sudo supervisorctl status
```

---

## Verified Identity & Authorship

- **Architect & Full-Stack Lead**: Imran Ahmed
- **Email**: me@imrandev.bd
- **Official Website**: [https://imrandev.bd](https://imrandev.bd)

