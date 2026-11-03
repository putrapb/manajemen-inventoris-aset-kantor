# Inventaris Aset Kantor

Aplikasi manajemen inventaris aset kantor berbasis Laravel, MySQL, dan Bootstrap 5.

## Fitur

- Login, logout, dan registrasi publik (akun baru otomatis ber-role `staff`).
- Role `admin` dan `staff` dengan pembatasan akses di sisi server melalui middleware dan policy.
- Admin dapat menambah, mengubah, dan menghapus aset.
- Staff hanya dapat melihat daftar dan detail aset.
- Daftar aset dengan pagination 10 data per halaman.
- Pencarian berdasarkan nama atau kode aset.
- Filter kategori dan status.
- Relasi kategori menggunakan eager loading untuk menghindari N+1.
- Validasi server-side: nama wajib, kode unik, nilai numerik >= 0, kategori harus ada.
- Pesan sukses dan error ditampilkan sebagai toast Bootstrap.
- Draft form tersimpan otomatis di `localStorage` dan dipulihkan saat form dibuka kembali.
- Soft delete pada aset; data tidak hilang permanen saat dihapus.
- Export data aset ke CSV sesuai pencarian dan filter aktif.
- Tampilan responsif: navbar, tabel responsif, form grid, dan modal konfirmasi hapus.
- API publik `GET /api/assets` dengan pagination dan filter status.

## Stack

- PHP 8.2+ (environment ini: PHP 8.4)
- Laravel 13
- MySQL 8
- Bootstrap 5 + Bootstrap Icons
- Vite

## Prasyarat

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- MySQL 8

## Setup

### 1. Install dependency

```sh
composer install
npm install
```

### 2. Konfigurasi environment

Salin `.env.example` menjadi `.env` jika belum ada, lalu sesuaikan:

```env
APP_NAME="Inventaris Aset"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_assets
DB_USERNAME=root
DB_PASSWORD=
```

Generate application key jika diperlukan:

```sh
php artisan key:generate
```

### 3. Siapkan MySQL

Jika MySQL belum berjalan di Windows, jalankan:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/start-mysql.ps1
```

Buat database:

```sh
mysql -u root -e "CREATE DATABASE IF NOT EXISTS inventory_assets CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Catatan environment lokal: MySQL 8 dijalankan secara manual menggunakan konfigurasi `C:\TATA\mysql-my.ini` dan data di `C:\TATA\mysql-data`. MySQL tidak terdaftar sebagai Windows Service.

### 4. Migration dan seeder

```sh
php artisan migrate:fresh --seed
```

Seeder menghasilkan:

- 1 admin
- 1 staff
- 3 kategori
- 15 aset

### 5. Build frontend dan jalankan aplikasi

```sh
npm run build
php artisan serve
```

Untuk mode development:

```sh
npm run dev
php artisan serve
```

Buka `http://localhost:8000`.

## Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | `admin@example.com` | `password` |
| Staff | `staff@example.com` | `password` |

Ganti kredensial ini sebelum digunakan di lingkungan produksi.

## Struktur Data

### users

`id`, `name`, `email`, `password`, `role` (`admin`/`staff`), timestamps.

### categories

`id`, `nama`, timestamps.

### assets

`id`, `nama`, `kode` (unik), `category_id`, `lokasi`, `status` (`tersedia`/`dipinjam`/`rusak`), `tanggal_beli`, `nilai`, timestamps.

## Hak Akses

| Aksi | Admin | Staff |
|---|---|---|
| Lihat daftar aset | Ya | Ya |
| Lihat detail aset | Ya | Ya |
| Export CSV | Ya | Ya |
| Tambah aset | Ya | Tidak (403) |
| Ubah aset | Ya | Tidak (403) |
| Hapus aset | Ya | Tidak (403) |

Pembatasan diterapkan melalui middleware `role:admin` dan `AssetPolicy`, bukan hanya menyembunyikan tombol di tampilan.

## Draft Form

Form tambah/edit aset menyimpan isian ke `localStorage` secara otomatis maksimal 500 ms setelah perubahan.

- Key draft dipisahkan per user dan per form.
- Draft dipulihkan saat form dibuka kembali.
- Draft berlaku maksimal 7 hari.
- Draft dihapus setelah penyimpanan berhasil dikonfirmasi server.
- Tersedia tombol `Hapus draft` pada form.

## Soft Delete

- Tabel `assets` menggunakan kolom `deleted_at`.
- Penghapusan aset oleh admin bersifat soft delete: data tetap tersimpan di database tetapi tidak muncul di daftar, detail, maupun API.
- Kode aset yang sudah dihapus tetap dianggap terpakai karena unique constraint database masih berlaku.

## Export CSV

Tombol `Export CSV` tersedia di halaman daftar aset untuk admin dan staff. Export mengikuti pencarian, filter kategori, dan filter status yang sedang aktif.

Kolom CSV:

`Kode`, `Nama`, `Kategori`, `Lokasi`, `Status`, `Tanggal Beli`, `Nilai`

File menggunakan UTF-8 dengan BOM agar kompatibel dengan Excel, serta melakukan escaping terhadap nilai yang berpotensi menjadi formula spreadsheet.

## API

### GET /api/assets

Publik, read-only, pagination 10 data per halaman.

Query parameter:

| Parameter | Nilai | Keterangan |
|---|---|---|
| `status` | `tersedia`, `dipinjam`, `rusak` | Filter status aset |
| `page` | angka | Halaman pagination |

Contoh:

```text
GET /api/assets?status=tersedia
```

Contoh respons:

```json
{
  "data": [
    {
      "kode": "AST-001",
      "nama_kategori": "Elektronik",
      "status": "tersedia"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/assets?status=tersedia&page=1",
    "last": "http://localhost:8000/api/assets?status=tersedia&page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 10,
    "to": 10,
    "total": 10
  }
}
```

Status tidak valid menghasilkan respons `422 Unprocessable Content` dengan error validasi pada field `status`.

## Pengujian

```sh
php artisan test
vendor/bin/pint --test
npm run build
```

Cakupan pengujian:

- Autentikasi login, registrasi, dan logout.
- Otorisasi admin/staff di sisi server.
- CRUD aset.
- Pagination, pencarian, dan filter.
- Pencegahan N+1 pada relasi kategori.
- Validasi aset.
- Soft delete aset.
- Export CSV dan filter yang diterapkan.
- API pagination dan filter status.
- Jumlah data hasil seeder.
