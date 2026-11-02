# KemraMart

An online shop for local Cambodian products, built with Laravel. Customers can browse products, add them to a cart and pay with **Bakong KHQR**. Admins manage products, categories and orders from a dashboard.

Live demo: https://kemramart.onrender.com

## Features

- Home and shop pages with search and category filter
- Shopping cart and checkout
- Payment with Bakong KHQR (QR code, payment status check)
- Role-based access: **admin** and **customer**
  - Admin: Admin Dashboard + My Dashboard
  - Customer: My Dashboard (orders, profile)
- Admin product, category and order management
- Stock is reduced automatically when an order is paid
- Responsive UI with Bootstrap 5

## Tech stack

| Layer | Technology |
|---|---|
| Backend | Laravel, PHP 8.4 |
| Frontend | Blade, Bootstrap 5.3 (CDN) |
| Database | SQLite |
| Payment | Bakong KHQR |
| QR code | simplesoftwareio/simple-qrcode |
| Hosting | Render (Docker + Apache) |

## Getting started (local)

### Requirements

- PHP 8.4 with `pdo_sqlite`, `gd`, `mbstring`, `bcmath`
- Composer 2

### Installation

```bash
git clone https://github.com/pheaktra1402/POS_Mart.git
cd POS_Mart

composer install
cp .env.example .env
php artisan key:generate
```

Create the SQLite database file (use `type nul > database\database.sqlite` on Windows CMD):

```bash
touch database/database.sqlite
```

Run migrations, seed demo data and link storage:

```bash
php artisan migrate
php artisan db:seed --class=DummyDataSeeder
php artisan storage:link
php artisan serve
```

Open http://127.0.0.1:8000

### Demo admin account

| Email | Password |
|---|---|
| admin@kemramart.com | 123456 |

> Change this password before using the app for anything real.

## Environment variables

Set these in `.env` locally, or in **Environment** on Render. On Render, enter values **without quotes**.

```env
APP_NAME=KemraMart
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=https://kemramart.onrender.com

DB_CONNECTION=sqlite

SESSION_DRIVER=database
CACHE_STORE=database

# Bakong KHQR
BAKONG_ACCOUNT_ID=
BAKONG_API_TOKEN=
KHQR_MERCHANT_NAME=
KHQR_MERCHANT_CITY=
KHQR_ACCOUNT_NUMBER=
KHQR_BANK_NAME=ABA Bank
KHQR_P2P_REF=
KHQR_P2P_ACCOUNT_1=
KHQR_P2P_ACCOUNT_2=
```

Never commit your real `.env`, API tokens or account numbers.

## Demo data and images

`database/seeders/DummyDataSeeder.php` creates the admin account, categories and sample products using `updateOrCreate`, so it is safe to run more than once.

Product images for the seeder live in `database/seed-image/`. During seeding they are copied to `storage/app/public/products/`, the same place used by admin uploads. If an image is missing, the product is still created without one.

## Deployment on Render

The app runs in Docker (`Dockerfile` + `start.sh`). On every start `start.sh`:

1. Creates the SQLite file
2. Runs `php artisan migrate --force`
3. Runs `php artisan db:seed --class=DummyDataSeeder --force`
4. Links storage and starts Apache

Steps:

1. Push the project to GitHub
2. On Render, create a **Web Service** from the repository (runtime: Docker)
3. Add the environment variables above
4. Deploy

### Important: data on the free plan

On Render's free plan the filesystem is ephemeral. The SQLite database and uploaded images are **reset on every deploy or restart**. Data created by the seeder comes back, but new users, orders and admin uploads are lost.

To keep data permanently, use one of these:

- A Render Persistent Disk (paid) and set `DB_DATABASE=/var/data/database.sqlite`
- An external database such as PostgreSQL (Neon, Supabase) or MySQL (TiDB Cloud)

## Project structure

```
app/
  Http/Controllers/     Home, Shop, Cart, Checkout, dashboards
  Models/               User, Category, Product, Order, OrderItem
  Services/KhqrService.php   Builds KHQR payment payloads
database/
  migrations/
  seeders/DummyDataSeeder.php
  seed-image/           Demo product images
resources/views/        Blade templates
routes/web.php
Dockerfile
start.sh
```

## Notes for Linux hosting

Linux file names are case-sensitive. Folder and class names must match exactly (for example `app/Services/KhqrService.php` with `namespace App\Services;`), otherwise the app works on Windows but fails on Render.

## License

This project is for learning and demonstration purposes.
