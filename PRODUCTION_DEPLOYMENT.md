# Production Deployment Guide

## 🔒 Database Security Setup

### 1. Create Dedicated Database User

**Current Setup (Development Only):**

-   Username: `root`
-   Password: `(empty)`
-   ⚠️ **NEVER use root in production!**

**Production Setup:**

```sql
-- Connect to MySQL as root
mysql -u root -p

-- Create dedicated database and user
CREATE DATABASE IF NOT EXISTS ukmspace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user with strong password
CREATE USER 'ukmspace_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD_HERE';

-- Grant only necessary privileges (no DROP, CREATE, ALTER)
GRANT SELECT, INSERT, UPDATE, DELETE ON ukmspace.* TO 'ukmspace_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Verify user
SHOW GRANTS FOR 'ukmspace_user'@'localhost';
```

### 2. Update .env for Production

```env
# Change these values:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use dedicated database user
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ukmspace
DB_USERNAME=ukmspace_user
DB_PASSWORD=YOUR_STRONG_PASSWORD_HERE

# Database Performance & Security
DB_TIMEOUT=5
DB_PERSISTENT=false

# Enable SSL/TLS for remote connections
MYSQL_ATTR_SSL_CA=/path/to/ca-cert.pem
MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=true
```

### 3. Enable SSL/TLS Encryption (Optional but Recommended)

**For Remote Database Connections:**

```bash
# Generate SSL certificates or get them from your database provider
# Place certificates in secure location (e.g., /etc/mysql/certs/)

# Update .env
MYSQL_ATTR_SSL_CA=/etc/mysql/certs/ca-cert.pem
MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=true
```

## 🛡️ Security Checklist

-   [ ] Create dedicated database user with limited privileges
-   [ ] Use strong password (min 16 chars, mixed case, numbers, symbols)
-   [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
-   [ ] Configure firewall to restrict database port (3306) access
-   [ ] Enable SSL/TLS for database connections
-   [ ] Regular database backups configured
-   [ ] Update `.env` with production credentials
-   [ ] Ensure `.env` file has restricted permissions (chmod 600)
-   [ ] Never commit `.env` to version control

## 📊 Database Optimization

The application includes:

-   ✅ Database indexes on frequently queried columns
-   ✅ Eager loading to prevent N+1 queries
-   ✅ Query result caching
-   ✅ Connection timeout protection
-   ✅ Prepared statements (SQL injection protection)
-   ✅ Mass assignment protection

## 🚀 Deployment Steps

1. **Backup Current Database**

    ```bash
    mysqldump -u root -p ukmspace > backup_$(date +%Y%m%d).sql
    ```

2. **Create Production User** (see step 1 above)

3. **Update Environment**

    ```bash
    cp .env .env.backup
    # Edit .env with production values
    ```

4. **Clear Caches**

    ```bash
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear
    ```

5. **Optimize for Production**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    composer install --optimize-autoloader --no-dev
    ```

6. **Run Migrations**

    ```bash
    php artisan migrate --force
    ```

7. **Set File Permissions**
    ```bash
    chmod 600 .env
    chmod -R 755 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    ```

## 🔐 Password Security Best Practices

**Strong Password Requirements:**

-   Minimum 16 characters
-   Mix of uppercase and lowercase letters
-   Include numbers and special characters
-   Avoid dictionary words
-   Don't reuse passwords

**Example:** `Km9#nP2$xR7@qL4&vB8^`

## 📝 Monitoring

Monitor these metrics in production:

-   Database connection count
-   Query execution time
-   Failed login attempts
-   Disk space usage
-   Error logs in `storage/logs/`

## 🆘 Troubleshooting

**Connection Timeout:**

-   Increase `DB_TIMEOUT` in .env
-   Check database server performance

**SSL Connection Failed:**

-   Verify certificate paths
-   Check MySQL SSL configuration
-   Ensure server supports SSL

**Access Denied:**

-   Verify username and password
-   Check user privileges with `SHOW GRANTS`
-   Ensure user can connect from application server
