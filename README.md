# Aplikasi Perpustakaan

Aplikasi web untuk membantu pengelolaan perpustakaan: data koleksi buku, kategori, anggota, serta pencatatan peminjaman dan pengembalian buku. Dibuat oleh **Kelompok 9**.

## Fitur

- **Katalog buku**: tampilan kartu dengan pencarian (judul, penulis, kategori), filter "hanya yang tersedia", dan indikator jumlah salinan tersedia.
- **Peminjaman**: pinjam buku langsung dari katalog, lengkap dengan tanggal pengembalian.
- **Pengembalian**: daftar buku yang sedang dipinjam beserta status jatuh tempo (aman, segera jatuh tempo, terlambat), bar sisa masa pinjam, tab filter, dan tombol kembalikan.
- **Kategori buku**: kelola kategori untuk mengelompokkan koleksi.
- **Manajemen anggota** (khusus admin): lihat daftar anggota, hapus anggota, serta jadikan atau cabut peran admin.
- **Hak akses berdasarkan peran**: tambah, ubah, dan hapus buku hanya untuk admin.
- **Autentikasi**: registrasi, login, dan pengelolaan profil (Laravel Breeze).
- **Mode terang dan gelap**: dapat diganti lewat tombol di navbar, pilihan tersimpan di browser.
- **Tampilan responsif**: nyaman dipakai di desktop maupun HP.

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | Laravel (PHP 8.1 atau lebih baru) |
| Autentikasi | Laravel Breeze |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Build tool | Vite |
| Database | MySQL |
| Lingkungan lokal | Laragon |

## Prasyarat

- PHP 8.1 atau lebih baru
- Composer
- Node.js dan npm
- MySQL
- Laragon (disarankan untuk Windows)

## Instalasi

1. **Clone repository** ke folder `www` Laragon:

   ```bash
   cd D:\laragon\www
   git clone <url-repository> perpus
   cd perpus
   ```

2. **Buka Terminal Laragon** (bukan PowerShell biasa) supaya perintah `php`, `composer`, dan `npm` dikenali.

3. **Install dependency**:

   ```bash
   composer install
   npm install
   ```

4. **Siapkan file environment**:

   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

5. **Buat database** (misalnya `perpus`) lewat HeidiSQL atau phpMyAdmin di Laragon, lalu sesuaikan `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=perpus
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Jalankan migration dan seeder**:

   ```bash
   php artisan migrate --seed
   ```

   Untuk mengulang dari awal (semua data di database akan terhapus):

   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Jalankan aplikasi**:

   ```bash
   npm run dev
   ```

   Biarkan terminal ini tetap terbuka selama development. Lalu buka `http://perpus.test` (Laragon membuat alamat ini otomatis dari nama folder) atau jalankan `php artisan serve` di terminal lain dan buka `http://127.0.0.1:8000`.

Untuk tampilan siap pakai tanpa menjalankan Vite terus-menerus:

```bash
npm run build
```

## Peran pengguna

| Peran | Hak akses |
| --- | --- |
| Anggota | Melihat katalog, meminjam buku, melihat dan mengembalikan pinjaman, mengelola profil |
| Admin | Semua hak anggota, ditambah kelola buku (tambah, ubah, hapus) dan kelola anggota |

Akun awal dibuat oleh seeder. Lihat `database/seeders` untuk email dan kata sandi yang digunakan. Akun admin juga bisa dibuat dengan menjadikan anggota sebagai admin lewat menu **Anggota**.

## Struktur halaman

| Halaman | URL | Akses |
| --- | --- | --- |
| Beranda | `/` | Publik |
| Peminjaman (halaman utama setelah login) | `/peminjamen` | Login |
| Katalog buku | `/bukus` | Login |
| Kategori | `/category` | Login |
| Profil | `/profile` | Login |
| Anggota | `/user` | Admin |

Alamat `/dashboard` tetap tersedia dan otomatis diarahkan ke halaman Peminjaman.

## Struktur folder penting

```
app/Http/Controllers/    BukuController, PeminjamanController, CategoryController, UserController
resources/views/
  welcome.blade.php      Halaman beranda
  layouts/               Layout dan navbar
  bukus/                 Halaman buku
  peminjamen/            Halaman peminjaman
  category/              Halaman kategori
  user/                  Halaman anggota
public/images/           Gambar (ilustrasi perpustakaan)
routes/web.php           Daftar route
```
