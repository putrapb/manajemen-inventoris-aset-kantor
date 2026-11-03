# Inventaris Aset Kantor

Aplikasi inventaris aset kantor berbasis Laravel.

## Kebutuhan

- PHP 8.4
- Composer
- Node.js dan npm
- MySQL 8

## Instalasi

```sh
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Sesuaikan koneksi database pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_assets
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `inventory_assets`, lalu jalankan migration dan seeder:

```sh
php artisan migrate --seed
```

## Menjalankan Aplikasi

```sh
npm run dev
php artisan serve
```

Aplikasi berjalan di `http://localhost:8000`.

## Akun Demo

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| Staff | `staff@example.com` | `password` |

## Pengujian

```sh
php artisan test
```
