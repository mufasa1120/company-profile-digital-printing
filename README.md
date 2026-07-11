# Digital Printing — Company Profile (PHP Native + MySQL)

Landing page company profile untuk bisnis digital printing dengan **admin panel custom**
(upload iklan/banner, layanan, produk, portfolio, pesan masuk, pengaturan).
Gaya UI: **brutalism**. Dibangun dari nol tanpa framework, arsitektur **MVC-lite** +
**front controller**.

> Status: **Fondasi/akar sudah jadi & teruji.** Mesin (routing, autoload, model, auth,
> upload, CSRF, validasi) lengkap. Modul **Ads** sudah CRUD penuh sebagai pola contoh.
> Modul lain (Service, Product, Portfolio, Message, Setting) + design system brutalism
> penuh = tahap berikutnya.

---

## Cara Menjalankan (XAMPP / Laragon)

1. **Taruh folder** ini di dalam `htdocs` (XAMPP) atau `www` (Laragon).

2. **Buat file `.env`** — sudah disiapkan dengan default XAMPP (root, tanpa password).
   Kalau MySQL lu pakai password, edit `DB_PASS` di `.env`.

3. **Buat database & isi data awal.** Buka phpMyAdmin lalu import:
   - `database/schema.sql`  (bikin struktur tabel)
   - `database/seed.sql`    (akun admin + contoh data)

   Atau via terminal:
   ```bash
   mysql -u root -p < database/schema.sql
   mysql -u root -p digital_printing < database/seed.sql
   ```

4. **Arahkan web server ke folder `public/`** (bukan root proyek).
   - Laragon: otomatis, akses `http://digital-printing.test`
   - XAMPP: akses `http://localhost/digital-printing/public`
   - Atau pakai built-in server dari root proyek:
     ```bash
     php -S localhost:8000 -t public
     ```
     Lalu sesuaikan `APP_URL=http://localhost:8000` di `.env`.

5. **Login admin:** buka `/admin/login`
   - Email: `admin@printing.test`
   - Password: `admin123`  ← **ganti setelah login pertama!**

---

## Struktur Proyek

```
public/          → docroot. index.php = front controller (satu pintu masuk)
  assets/        → css (brutalism), js, img
  uploads/       → hasil upload admin (ads/products/portfolio)
app/
  Core/          → mesin: Database, Router, Controller, Model, Auth, Upload, Validator, Csrf
  Controllers/   → Front (publik) & Admin (butuh login)
  Models/        → 1 model per tabel
  Views/         → layouts, front, admin
  Helpers/       → fungsi global (url, asset, e, csrf_field, flash, dll)
config/          → config.php (+ loader .env), database.php
routes/          → web.php (publik), admin.php (admin)
database/        → schema.sql, seed.sql
storage/logs/    → log error (mode produksi)
```

## Alur Request

```
Browser → public/index.php → Router → Controller → Model → View → Browser
                                 ↑
                       middleware 'auth' (halaman admin)
```

## Keamanan yang Sudah Terpasang

- **CSRF**: semua POST diverifikasi token (`csrf_field()` di form).
- **Auth guard**: halaman `/admin/*` wajib login, session di-regenerate saat login.
- **Password**: disimpan sebagai hash bcrypt (`password_hash`).
- **Upload**: validasi MIME asli (bukan cuma ekstensi), batas 3 MB, nama file di-randomize.
- **XSS**: helper `e()` untuk escape semua output data.
- **SQL Injection**: seluruh query pakai prepared statement (PDO).
- **Mass-assignment**: dibatasi lewat `$fillable` di tiap model.

## Menambah Modul Baru (pola)

Modul `Ad` (lihat `app/Controllers/Admin/AdController.php`) adalah **cetakan**.
Untuk modul baru (mis. Portfolio):
1. Model `app/Models/Portfolio.php` (sudah ada) — set `$table` & `$fillable`.
2. Controller `app/Controllers/Admin/PortfolioController.php` — copy pola AdController.
3. View `app/Views/admin/portfolio/index.php` & `form.php` — copy pola ads.
4. Daftarkan rute di `routes/admin.php`.

---

Default akun & data seed hanya untuk development. **Ganti sebelum produksi.**
