# Find & Found — Dokumentasi dan Panduan Instalasi (Bahasa Indonesia)

## Ringkasan Singkat
Find & Found adalah aplikasi berbasis Laravel (PHP) untuk melaporkan dan menemukan barang hilang/ketemu. Fitur utama meliputi registrasi/login, pelaporan item (foto + lokasi), pencarian berdasarkan kategori/ lokasi/ kata kunci, sistem chat antar pengguna, moderasi/admin, serta notifikasi dan laporan.

Dokumentasi ini menjelaskan struktur proyek, fungsi file/folder penting yang telah ditelaah, dan panduan instalasi lengkap pada Windows (PowerShell) menggunakan MySQL.

## Checklist Persyaratan Permintaan
- [x] Menjelaskan fungsi folder dan file utama dalam proyek (bahasa Indonesia)
- [x] Menyusun panduan instalasi lengkap menggunakan MySQL pada Windows PowerShell
- [x] Menyertakan perintah yang dapat ditempelkan pada PowerShell dan penjelasan singkat
- [x] Menambahkan informasi kredensial seeder demo dan catatan keamanan

## Kontrak Singkat (inputs / outputs / sukses)
- Input: kode sumber (repo ini), MySQL berjalan, PHP & Composer, Node/npm
- Output: Aplikasi berjalan lokal di http://127.0.0.1:8000
- Sukses: Bisa mengunjungi halaman utama, login admin demo, dan melihat data yang di-seed.

Edge cases yang diperhatikan:
- DB MySQL belum dibuat => panduan membuat DB
- File storage belum di-link => perintah `php artisan storage:link`
- Kredensial default harus diubah di production

## Struktur Direktori & Fungsi (ringkas)
Berikut ringkasan folder/file penting dan fungsinya yang relevan:

- `app/Models/` — Model Eloquent; berisi logika domain dan relasi:
  - `User.php` — model user, autentikasi, relasi (items, comments, chats), helper (avatar, role).
  - `Item.php` — model item (hilang/ditemukan), foto (json array), lokasi, status, scope pencarian, helper untuk foto dan view count.
  - `Category.php` — kategori item; event untuk membuat slug otomatis; scope `active()`.
  - `Location.php` — menyimpan alamat, koordinat; ada fungsi jarak dan scope untuk radius.
  - `Comment.php` — komentar pada item, approval, notifikasi ketika dibuat.
  - `Chat.php` — pesan antar pengguna; scope untuk percakapan, notifikasi saat pesan dibuat.
  - `Report.php` — laporan abuse/reporting untuk item; status workflow (pending/reviewed/resolved/rejected).
  - `Notification.php` — notifikasi internal untuk user.

- `app/Http/Controllers/` — meng-handle request dan business flow:
  - `AuthController.php` — login, register, lupa password, reset password.
  - `HomeController.php` — halaman utama, pencarian, browse, about, contact, FAQ.
  - `ItemController.php` — CRUD item, upload foto, lokasi, daftar milik user, mark resolved.
  - `CategoryController.php`, `LocationController.php`, `CommentController.php`, `ChatController.php`, `ReportController.php` — fitur masing-masing area.
  - `AdminController.php` — dashboard admin, manajemen user/item/category/comments/reports.

- `routes/web.php` — semua route aplikasi (publik, guest, auth, admin). Menentukan endpoint seperti `/items`, `/chats`, `/admin/*`.

- `resources/views/` — blade templates untuk frontend (layout, halaman item, admin dashboard, auth, dll.).

- `database/migrations/` — skema tabel: users, items (photos json), categories, locations, comments, chats, reports, notifications, dll.

- `database/seeders/` — data awal:
  - `UserSeeder` — membuat 1 admin dan 2 user demo (password: `password`).
  - `CategorySeeder` — beberapa kategori default (Elektronik, Dokumen, dsb.).

- `public/` — entri publik dan folder `uploads/` untuk foto; `storage` di-link ke `storage/app/public`.

- `composer.json` — dependensi PHP (Laravel 12, Sanctum, dll.) dan beberapa script artisan.
- `package.json` — dev dependencies untuk asset pipeline (Vite, Tailwind, dll.)

## Kredensial Demo (dari Seeder)
- Admin:
  - Email: `admin@findandfound.com`
  - Password: `password`

- User demo:
  - `john@example.com` / `password`
  - `jane@example.com` / `password`

(cuma demo)

Ganti kredensial ini ketika dipakai di lingkungan produksi.

## Panduan Instalasi Lengkap (Windows PowerShell)
Langkah-langkah di bawah diasumsikan Anda bekerja pada Windows PowerShell (v5.1). Ubah sesuai kebutuhan jika menggunakan WSL, Docker, atau environment lain.

1) Prasyarat
- PHP >= 8.2 dengan ekstensi: pdo_mysql, mbstring, openssl, tokenizer, curl, fileinfo
- Composer (https://getcomposer.org)
- MySQL server
- Git (opsional)

2) Clone repository dan pindah ke folder proyek

```powershell
# contoh: jika belum di-clone
git clone <repo-url> findandfound
cd 'c:\New folder (2)\findandfound'
```

3) Copy file lingkungan dan edit `.env`

```powershell
# buat salinan .env
copy .env.example .env
# lalu buka .env  VS Code
.env
```

Ubah konfigurasi database di `.env` menjadi seperti ini (contoh):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=findandfound
DB_USERNAME=root
DB_PASSWORD=your_mysql_password_here
```

4) Instalasi mail di .env
login di mailtrap buatsandbox lalu copy host,port,username,password

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=(port mailtrap)
MAIL_USERNAME=(your usename host in mailtrap)
MAIL_PASSWORD=(your password in mailtrap)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@findandfound.com"
MAIL_FROM_NAME="Find & Found"
```

5) Install dependensi PHP (Composer)

```powershell
composer install --no-interaction --prefer-dist
```

6) Install dependensi Node

```powershell
npm install
```

7) Buat APP_KEY, jalankan migrasi dan seeder
Pastikan file `.env` sudah berisi kredensial DB MySQL yang benar.

```powershell
# generate key
php artisan key:generate

# jalankan migrasi ke MySQL
php artisan migrate

# jalankan seeder
php artisan db:seed
```

Catatan: composer.json juga memiliki script yang dapat menjalankan `artisan migrate` setelah install di beberapa kondisi. Kami merekomendasikan menjalankan perintah di atas secara eksplisit untuk transparansi.

8) Buat symbolic link untuk storage (agar file upload bisa diakses dari public)

```powershell
php artisan storage:link
```

9) Build atau jalankan assets (development)

```powershell
# untuk development (hot reload)
npm run dev

# atau untuk produksi (build statis)
npm run build
```

10) Jalankan server lokal

```powershell
php artisan serve
# hasil: http://127.0.0.1:8000
```

11) Cek aplikasi
Buka browser ke `http://127.0.0.1:8000`. Login dengan kredensial admin demo jika ingin masuk ke area admin.

## Troubleshooting Singkat
- Error koneksi DB: cek variabel DB_* di `.env`, pastikan MySQL berjalan dan user/password benar.
- Migration error (foreign key): pastikan semua migrasi berada di folder `database/migrations` dan urutannya benar. Hapus DB dan buat ulang jika perlu untuk development.
- Tidak bisa upload gambar: pastikan `storage/app/public/items` ada dan `php artisan storage:link` telah dijalankan.
- `mysql` command not found: tambahkan folder bin MySQL ke PATH atau gunakan GUI untuk membuat database.

## Penjelasan Fitur Utama (singkat)
- Registrasi & Auth: user dapat daftar, login, reset password.
- Laporkan barang: user membuat posting (status lost/found) dengan foto dan lokasi (latitude/longitude + city).
- Pencarian: full-text-like search pada judul/deskripsi, filter kategori, kota, radius lokasi.
- Chat: user dapat saling chat terkait item; chat membuat notifikasi otomatis.
- Komentar: user bisa komentar pada item; pemilik item mendapat notifikasi.
- Admin: manajemen user, item, kategori, komentar, chat, laporan.