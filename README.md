# Sistem Inventaris Sekolah

Aplikasi pencatatan barang inventaris sekolah beserta proses peminjaman dan pengembaliannya.
Dibangun dengan Laravel 12, Blade, Laravel Breeze, dan MySQL.

## Fitur

- Dua role: **Admin** (mengelola penuh) dan **User** (guru/siswa peminjam)
- CRUD kategori, barang, dan akun pengguna (Admin)
- Pengajuan peminjaman oleh User, stok berkurang otomatis
- Pencatatan pengembalian oleh Admin, stok bertambah otomatis
- Status peminjaman: dipinjam, terlambat (dihitung dari batas pengembalian), dikembalikan
- Activity log: CRUD, peminjaman, pengembalian, login, logout, dan register
- Pencarian, filter, dan pagination di setiap tabel
- Dashboard statistik, format angka dan tanggal Indonesia

## Kebutuhan

PHP 8.2+, Composer, Node.js, dan MySQL.

## Instalasi

```bash
git clone <url-repository-anda>
cd <nama-folder>
composer install
cp .env.example .env
php artisan key:generate
```

Buat database kosong bernama `inventaris_sekolah`, lalu sesuaikan `DB_USERNAME` dan `DB_PASSWORD` di `.env`.

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Untuk pengembangan, jalankan `npm run dev` sebagai pengganti `npm run build`.
Aplikasi dapat dibuka di http://127.0.0.1:8000

## Akun hasil seeder

| Role  | Email             | Password |
|-------|-------------------|----------|
| Admin | admin@example.com | password |
| User  | budi@example.com  | password |
| User  | siti@example.com  | password |

## Alur singkat

1. Admin membuat kategori dan barang.
2. User membuka **Daftar barang**, lalu mengajukan peminjaman. Stok langsung berkurang.
3. Saat barang kembali, Admin membuka **Peminjaman**, memilih **Proses pengembalian**, dan mengisi kondisi barang. Stok bertambah kembali.
4. Setiap aktivitas penting tercatat di **Activity log**.

## Struktur database

`users`, `categories`, `items`, `loans`, `return_logs`, dan `activity_logs`.
`loans` dan `return_logs` memakai Soft Delete.