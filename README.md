# Cordova Water System Inc. - Full-Stack Website

A complete water utility management system with user authentication (Facebook/Google OAuth + email/password), admin dashboard for billing, payments, and service request management. Built with **PHP** and **MySQL**.

## How to Run

```bash
# 1. Copy environment config
copy .env.example .env   # Windows (use cp on Mac/Linux)

# 2. Edit .env - set DB_HOST, DB_NAME, DB_USER, DB_PASS, BASE_URL=http://localhost:8000

# 3. Setup database (one-time)
php setup.php

# 4. Start the server
php -S localhost:8000

# 5. Open browser: http://localhost:8000
# Admin: admin@cordovawater.com / admin123
```

## Features

- **User Authentication**: Login with Facebook, Google, or email/password
- **User Dashboard**: View service requests, billing, and payments
- **Admin Dashboard**: Manage service requests, billing records, and payments
- **Service Requests**: Submit new connection, reconnection, leak repair, etc.
- **Billing & Payments**: Track water consumption and payment status
- **Responsive Design**: Tailwind CSS, mobile-friendly

## Quick Start (Local)

### 1. Requirements

- PHP 7.4+ (with PDO, pdo_mysql, curl, mbstring)
- MySQL 5.7+ or MariaDB
- Web server (Apache with mod_rewrite) or PHP built-in server

### 2. Setup

```bash
# Clone or copy the project
cd SAMPLE

# Copy environment config
copy .env.example .env   # Windows
# cp .env.example .env  # Linux/Mac

# Edit .env - add your database credentials and BASE_URL
# For local: BASE_URL=http://localhost:8000
```

### 3. Database Setup

```bash
# Option A: Run setup script (creates DB, tables, admin user)
php setup.php

# Option B: Import schema manually
mysql -u root -p < sql/schema.sql
# Then run: php setup.php  # to create admin user
```

### 4. Run Locally

```bash
# PHP built-in server
php -S localhost:8000

# Visit http://localhost:8000
# Admin login: admin@cordovawater.com / admin123 (change this!)
```

## OAuth Setup (Facebook & Google)

### Google OAuth

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a project → APIs & Services → Credentials → Create OAuth 2.0 Client ID
3. Application type: Web application
4. Authorized redirect URIs: `https://yourdomain.com/auth/google-callback.php`
5. Copy Client ID and Client Secret to `.env`:
   ```
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   ```

### Facebook OAuth

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create App → Add Facebook Login product
3. Settings → Basic: copy App ID and App Secret
4. Facebook Login → Settings: Valid OAuth Redirect URIs: `https://yourdomain.com/auth/facebook-callback.php`
5. Add to `.env`:
   ```
   FB_APP_ID=your_app_id
   FB_APP_SECRET=your_app_secret
   ```

## Deployment

### Option 1: Railway (Recommended - Easiest)

1. Sign up at [Railway.app](https://railway.app)
2. New Project → Deploy from GitHub (connect your repo)
3. Add MySQL plugin: Railway → New → Database → MySQL
4. Add environment variables: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `BASE_URL`, `GOOGLE_CLIENT_ID`, etc.
5. Set `BASE_URL` to your Railway URL (e.g., `https://your-app.up.railway.app`)
6. Deploy! Run `php setup.php` via Railway CLI or one-time deploy to initialize DB

### Option 2: Render

1. Sign up at [Render.com](https://render.com)
2. New → Web Service → Connect repo
3. Environment: PHP (or Docker)
4. Add MySQL database (Render PostgreSQL or external MySQL like PlanetScale)
5. Set environment variables
6. Build command: (empty or `composer install` if using Composer)
7. Start command: `php -S 0.0.0.0:$PORT`

### Option 3: Vercel (PHP Serverless)

1. Vercel supports PHP via [vercel-community/php](https://github.com/vercel-community/php)
2. Use external MySQL (PlanetScale, Railway, etc.) - set `DB_*` env vars
3. Deploy: `vercel` or connect GitHub
4. **Note**: Sessions may behave differently on serverless. Consider Redis for production.

### Option 4: Traditional Hosting (cPanel, InfinityFree, etc.)

1. Upload files via FTP
2. Create MySQL database in cPanel
3. Import `sql/schema.sql`
4. Run `setup.php` once (or create admin manually)
5. Copy `.env.example` to `.env` and configure
6. Ensure `.htaccess` is enabled (Apache mod_rewrite)

## File Structure

```
├── index.php           # Home
├── login.php           # Login (FB, Google, email)
├── logout.php          # Logout
├── request-service.php # Submit service request
├── about.php, services.php, contact.php, payment.php, plans.php
├── auth/               # OAuth callbacks
│   ├── google-callback.php
│   └── facebook-callback.php
├── dashboard/          # User dashboard
│   └── index.php
├── admin/              # Admin dashboard (staff/admin only)
│   ├── index.php       # Overview
│   ├── requests.php    # Service requests
│   ├── billing.php     # Billing records
│   └── payments.php    # Payments
├── includes/
│   ├── config.php      # Config (loads .env)
│   ├── auth.php        # Auth helpers
│   └── header.php      # Shared nav
├── sql/
│   └── schema.sql      # Database schema
├── js/main.js          # Frontend scripts
├── images/             # Logo, background, QR codes
├── .env.example        # Environment template
├── setup.php           # One-time DB setup (DELETE after use!)
├── vercel.json         # Vercel config
├── railway.json        # Railway config
└── nixpacks.toml       # Nixpacks/Railway PHP
```

## Security Notes

- **Delete `setup.php`** after initial setup
- Change default admin password immediately
- Use HTTPS in production
- Keep `.env` out of version control (add to .gitignore)

## Support

For issues, contact Cordova Water System Inc. or check the codebase.
