# School ERP System - Complete Setup & Deployment Guide

## Tech Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Frontend**: Bootstrap 5, Chart.js
- **Payment**: PayU Payment Gateway
- **Email**: SMTP (Gmail / Custom)

---

## 🚀 Quick Start (Local Development)

### Prerequisites
- PHP 8.2+ with extensions: mbstring, pdo_mysql, gd, zip, curl, openssl
- Composer 2.x
- MySQL 5.7+
- Node.js (optional, for Vite)

### Step 1: Clone & Install
```bash
git clone <repo-url> school-erp
cd school-erp
composer install
cp .env.example .env
php artisan key:generate
```

### Step 2: Database Setup
```bash
# Create MySQL database
mysql -u root -p -e "CREATE DATABASE school_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# OR import the SQL file directly:
mysql -u root -p school_erp < database/school_erp.sql
```

### Step 3: Configure Environment (.env)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_erp
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=noreply@yourschool.com
MAIL_FROM_NAME="School ERP"

PAYU_MERCHANT_KEY=your_payu_key
PAYU_MERCHANT_SALT=your_payu_salt
```

### Step 4: Run Migrations & Seed
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### Step 5: Start Server
```bash
php artisan serve
# Visit: http://localhost:8000
```

### Default Admin Login
- **URL**: http://localhost:8000/login
- **Email**: admin@school.com
- **Password**: admin@123
- **Role**: Admin

---

## 🌐 cPanel Shared Hosting Deployment

### Step 1: Upload Files
1. Upload all files to `public_html/` or a subdomain folder
2. Move the `/public` folder contents to the document root
3. Update `public_html/index.php`:
```php
require __DIR__.'/../school-erp/vendor/autoload.php';
$app = require_once __DIR__.'/../school-erp/bootstrap/app.php';
```

### Step 2: MySQL Setup
1. Create database in cPanel MySQL Manager
2. Import `database/school_erp.sql`
3. Update `.env` with cPanel DB credentials

### Step 3: File Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Step 4: Configure .htaccess (public/)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Step 5: Run via cPanel Terminal
```bash
cd /home/username/school-erp
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📧 Gmail SMTP Setup

1. Go to Google Account → Security
2. Enable 2-Step Verification
3. Search for "App passwords" → Create App Password
4. Choose "Mail" and "Other (Custom name)"
5. Copy the 16-character password

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yourname@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx   # App password (no spaces)
MAIL_ENCRYPTION=tls
```

**OR update from Admin Panel:**
- Login as Admin → Settings → SMTP Settings

---

## 💳 PayU Payment Gateway Setup

### Test Environment
1. Register at https://developer.payu.in
2. Get test merchant key and salt
3. Update `.env`:
```env
PAYU_MERCHANT_KEY=your_test_key
PAYU_MERCHANT_SALT=your_test_salt
PAYU_BASE_URL=https://test.payu.in/_payment
```

### Live Environment
1. Complete PayU KYC verification
2. Get live merchant credentials
3. Update `.env`:
```env
PAYU_MERCHANT_KEY=your_live_key
PAYU_MERCHANT_SALT=your_live_salt
PAYU_BASE_URL=https://secure.payu.in/_payment
```

### Success/Failure Callbacks
These must be accessible URLs:
- Success: `https://yourdomain.com/payment/success`
- Failure: `https://yourdomain.com/payment/failure`

---

## 🔍 SEO Setup Guide

### 1. Configure SEO from Admin Panel
- Login as Admin → Settings → SEO Settings
- Fill in: Meta Title, Description, Keywords
- Add Google Analytics ID (G-XXXXXXX)
- Add Schema Organization data

### 2. Verify Sitemap
- Access: `https://yourdomain.com/sitemap.xml`
- Submit to Google Search Console

### 3. Verify Robots.txt
- Access: `https://yourdomain.com/robots.txt`

### 4. Google Search Console
1. Visit https://search.google.com/search-console
2. Add property → Enter domain
3. Verify via HTML tag (paste in Admin → SEO → Google Site Verification)
4. Submit sitemap URL

---

## 📁 Project Structure

```
school-erp/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/         # Authentication (login, OTP, password reset)
│   │   │   ├── Admin/        # Admin panel controllers
│   │   │   ├── Student/      # Student portal + payment
│   │   │   ├── Teacher/      # Teacher portal
│   │   │   ├── Parent/       # Parent portal
│   │   │   └── Public/       # Public pages (home, admission, blog)
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php     # Role-based access control
│   │       └── SettingsMiddleware.php # Global settings injection
│   ├── Mail/             # Email classes (OTP, admission, fee, notification)
│   └── Models/           # Eloquent models
├── database/
│   ├── migrations/       # Database schema migrations
│   ├── seeders/          # Default data seeder
│   └── school_erp.sql    # Complete SQL dump
├── resources/
│   └── views/
│       ├── layouts/      # Main layout (app.blade.php, public.blade.php)
│       ├── auth/         # Login, forgot password, verify OTP, reset
│       ├── admin/        # Admin panel views
│       ├── student/      # Student portal views
│       ├── teacher/      # Teacher portal views
│       ├── parent/       # Parent portal views
│       ├── public/       # Public website views
│       └── emails/       # Email templates
└── routes/
    └── web.php           # All application routes
```

---

## 🔐 Security Notes

- All passwords hashed with bcrypt (cost factor: 12)
- CSRF protection on all forms
- Role-based access control via middleware
- SQL injection prevention via Eloquent ORM
- Session regeneration after login
- PayU hash verification for payment callbacks
- Input validation and sanitization on all endpoints

---

## 📞 Support

For technical support:
- Email: support@schoolerp.com
- Documentation: Available in /docs folder

---

## 🧰 Useful Artisan Commands

```bash
# Clear all caches
php artisan optimize:clear

# Cache for production
php artisan optimize

# Generate storage symlink
php artisan storage:link

# View all routes
php artisan route:list

# Run specific migration
php artisan migrate --path=database/migrations/specific_file.php

# Reset and re-seed database
php artisan migrate:fresh --seed

# Test email configuration
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));
```
