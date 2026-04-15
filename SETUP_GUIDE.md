# School ERP System — Complete Setup & Deployment Guide

> **No terminal required.** This guide uses only cPanel File Manager,
> phpMyAdmin, and the browser-based installer.

---

## Tech Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 5.7+ / MariaDB 10.3+ (or SQLite)
- **Frontend**: Bootstrap 5, Chart.js
- **Payment**: PayU Payment Gateway
- **Email**: SMTP (Gmail / Custom)

---

## cPanel Shared Hosting — Step-by-Step

### Step 1 — Create a MySQL Database (cPanel)

1. Log in to **cPanel**
2. Go to **MySQL Databases**
3. Under *Create New Database*, enter a name (e.g. `schoolerp`) → click **Create Database**
4. Under *MySQL Users*, create a new user with a strong password
5. Under *Add User to Database*, add that user to the database and give **ALL PRIVILEGES**
6. Note down: **Database name**, **Username**, **Password**, **Host** (usually `localhost`)

---

### Step 2 — Upload Project Files

**Option A — File Manager (recommended)**

1. Open **cPanel → File Manager**
2. Navigate to the folder where you want the site (e.g. `public_html` for the main domain,
   or `public_html/school` for a subdirectory)
3. Click **Upload** → upload the project ZIP file → extract it
4. You should now have a `school-erp/` folder with subfolders: `app/`, `public/`, `vendor/`, etc.

**Option B — FTP**

Upload all project files using FileZilla or any FTP client to the same location.

---

### Step 3 — Point the Domain to the `/public` Folder

Laravel's webroot is the `public/` subfolder, not the project root.
You have two options:

**Option A — Subdomain pointing to `/public` (cleanest)**

1. cPanel → **Subdomains** (or Addon Domains)
2. Create a subdomain or use an existing domain
3. Set the **Document Root** to `public_html/school-erp/public`
4. Save

**Option B — Move `public/` contents to `public_html/`**

If you must use `public_html` directly:

1. Copy everything inside `school-erp/public/` into `public_html/`
2. Edit `public_html/index.php` — change these two lines:

```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```
to (adjust the path to match where you uploaded):
```php
require __DIR__.'/../school-erp/vendor/autoload.php';
$app = require_once __DIR__.'/../school-erp/bootstrap/app.php';
```

---

### Step 4 — Set Folder Permissions

In **cPanel → File Manager**, right-click each folder below → **Change Permissions**:

| Folder | Permission |
|---|---|
| `storage/` (and all subfolders) | `755` |
| `bootstrap/cache/` | `755` |
| `database/` | `755` |

To set permissions recursively on `storage/`:
- Right-click `storage` → Change Permissions → check **755** → tick **Recurse into subdirectories** → Save.

---

### Step 5 — Run the Web Installer

1. Open your browser and go to:
   ```
   https://yourdomain.com/install.php
   ```
2. The installer has 5 steps:

| Step | What it does |
|---|---|
| 1. Requirements | Checks PHP version, extensions, writable folders |
| 2. Database | Enter MySQL details (or choose SQLite) |
| 3. Configuration | Set school name, website URL, admin email & password |
| 4. Install | Writes `.env`, runs migrations, seeds data, creates admin |
| 5. Complete | Shows login URL and next steps |

3. After install completes, **delete `install.php`** from File Manager immediately.

---

### Step 6 — Verify Installation

Visit your site:

| URL | What you see |
|---|---|
| `https://yourdomain.com/` | Public homepage |
| `https://yourdomain.com/login` | Login page |
| `https://yourdomain.com/admission` | Admission form |
| `https://yourdomain.com/admin/dashboard` | Admin panel (after login) |

Default admin login is whatever you entered in Step 3 of the installer.

---

### Step 7 — Configure SMTP Email (Gmail)

**Get a Gmail App Password:**

1. Go to [myaccount.google.com](https://myaccount.google.com) → **Security**
2. Enable **2-Step Verification** (required)
3. Search **App passwords** → create one → choose **Mail** → copy the 16-character code

**Set it in the Admin Panel (no file editing needed):**

1. Login as Admin → **Settings → SMTP Settings**
2. Fill in:

| Field | Value |
|---|---|
| SMTP Host | `smtp.gmail.com` |
| SMTP Port | `587` |
| Username | `yourname@gmail.com` |
| Password | The 16-character App Password |
| From Address | `noreply@yourschool.com` |
| Encryption | `TLS` |

3. Click **Save SMTP Settings**

---

### Step 8 — Configure PayU Payment Gateway

**Test account (for testing):**

1. Register at [developer.payu.in](https://developer.payu.in)
2. Get your **Merchant Key** and **Merchant Salt** from the test dashboard

**Live account:**

1. Complete PayU KYC verification
2. Get live credentials from the PayU merchant dashboard

**Set it in the Admin Panel:**

1. Login as Admin → **Settings → Payment Settings**
2. Fill in Merchant Key and Merchant Salt
3. For test mode change the PayU URL to `https://test.payu.in/_payment`
4. For live mode use `https://secure.payu.in/_payment`

**Callback URLs (register these in your PayU dashboard):**

```
Success URL: https://yourdomain.com/payment/success
Failure URL: https://yourdomain.com/payment/failure
```

---

### Step 9 — SEO Settings

1. Login as Admin → **Settings → SEO Settings**
2. Fill in:
   - Meta Title, Meta Description, Keywords
   - Google Analytics ID (e.g. `G-XXXXXXXXXX`)
   - Schema Organization details (name, phone, address)
3. **Verify sitemap**: visit `https://yourdomain.com/sitemap.xml`
4. **Submit to Google Search Console**:
   - Go to [search.google.com/search-console](https://search.google.com/search-console)
   - Add property → enter your domain
   - Verify via HTML meta tag (paste it in Admin → SEO → Verification Tag)
   - Submit sitemap URL

---

## Common cPanel Issues & Fixes

### White screen / 500 error after upload

**Cause**: `.env` file missing or permissions wrong.
**Fix**: The web installer creates `.env` automatically. If you skipped the installer,
copy `.env.example` to `.env` via File Manager.

### "Class not found" errors

**Cause**: `vendor/` folder not uploaded.
**Fix**: Make sure you upload the `vendor/` folder from the project. It contains all PHP
dependencies. It is large (~50 MB) but must be present.

### Images / uploads not showing

**Cause**: Storage symlink not created.
**Fix**: The installer creates this automatically. If it failed, in File Manager
create a folder named `storage` inside `public/` and point it to `storage/app/public/`
— or contact hosting support to run `php artisan storage:link` from their backend.

### Session / login issues

**Fix**: In File Manager, ensure `storage/framework/sessions/` exists and is writable (755).

### "Writable: NOT WRITABLE" in installer

**Fix**: Set permissions to `755` on the listed folder via File Manager
(right-click → Change Permissions).

---

## Project Structure

```
school-erp/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/         — Login, OTP password reset
│   │   ├── Admin/        — Admin panel (students, fees, settings…)
│   │   ├── Student/      — Student portal + PayU payment
│   │   ├── Teacher/      — Attendance, homework
│   │   ├── Parent/       — Children overview
│   │   └── Public/       — Homepage, admission, blog, sitemap
│   ├── Mail/             — Email classes (OTP, admission, fee, notification)
│   ├── Models/           — Eloquent models
│   └── Http/Middleware/
│       ├── RoleMiddleware.php      — Role-based access control
│       └── SettingsMiddleware.php  — Injects site settings into all views
├── database/
│   ├── migrations/       — All table schemas
│   ├── seeders/          — Default data (classes 1–12, settings, categories)
│   └── school_erp.sql    — Complete MySQL dump (alternative to migrations)
├── public/
│   ├── index.php         — Application entry point
│   └── install.php       — Web installer (delete after use!)
└── resources/views/
    ├── layouts/          — app.blade.php (SaaS), public.blade.php (website)
    ├── auth/             — Login, forgot password, OTP, reset
    ├── admin/            — All admin panel views
    ├── student/          — Student portal views
    ├── teacher/          — Teacher portal views
    ├── parent/           — Parent portal views
    ├── public/           — Public website pages
    └── emails/           — HTML email templates
```

---

## Security Notes

- All passwords hashed with bcrypt (cost 12)
- CSRF protection on every form
- Role-based access control via `RoleMiddleware`
- SQL injection prevention via Eloquent ORM
- Session regeneration after login
- PayU SHA512 hash verification on payment callbacks
- Input validation on all user-facing endpoints
- Install lock file prevents re-running the installer

---

## User Roles & Login URLs

| Role | Login URL | Default path after login |
|---|---|---|
| Admin | `/login` | `/admin/dashboard` |
| Teacher | `/login` | `/teacher/dashboard` |
| Student | `/login` | `/student/dashboard` |
| Parent | `/login` | `/parent/dashboard` |

---

## Gmail SMTP — Quick Reference

| Setting | Value |
|---|---|
| Host | `smtp.gmail.com` |
| Port | `587` |
| Encryption | `TLS` |
| Username | Your full Gmail address |
| Password | 16-character App Password (not your Gmail password) |
