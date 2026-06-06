# 🎓 Sistem UAS Pemrograman Web — Panduan Setup

## Persyaratan
- PHP 8.2+
- Composer
- MySQL 5.7+ / MySQL 8+
- Web server: Apache (Laragon/XAMPP) atau Nginx

---

## 🚀 Langkah Deploy di Hosting / Lokal

### 1. Extract & Upload
Upload semua file ke folder hosting Anda (biasanya `public_html` atau subdirectory).

### 2. Install Dependensi
```bash
composer install --optimize-autoloader --no-dev
```

### 3. Salin File .env
```bash
cp .env.example .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Konfigurasi Database
Edit file `.env` sesuai database Anda:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uas_web        ← nama database yang sudah dibuat
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Jalankan Migrasi & Seeder
```bash
php artisan migrate --seed
```
Perintah ini akan:
- Membuat semua tabel database
- Membuat akun dosen default
- Mengisi 20 soal UAS beserta kunci jawaban

### 7. Buat Storage Link
```bash
php artisan storage:link
```

### 8. Set Permission (Linux/Hosting)
```bash
chmod -R 775 storage bootstrap/cache
```

### 9. Konfigurasi APP_URL
Di `.env`, ubah:
```
APP_URL=https://domain-anda.com
```

---

## 👤 Akun Default

| Role   | Email             | Password   |
|--------|-------------------|------------|
| Dosen  | dosen@uas.ac.id   | dosen123   |

> **Mahasiswa wajib register sendiri** melalui halaman `/register`

---

## 📱 Alur Penggunaan

### Mahasiswa:
1. Buka `/register` → isi Nama, NIM, Email, Password
2. Otomatis login & timer 120 menit mulai berjalan
3. Kerjakan 20 soal, klik **Simpan** di setiap soal
4. Klik **Selesai Ujian** → isi nilai harapan & alasan
5. Lihat rekap hasil + estimasi nilai

### Dosen:
1. Login dengan `dosen@uas.ac.id` / `dosen123`
2. Dashboard menampilkan semua mahasiswa + status ujian
3. Klik **Lihat Jawaban** untuk melihat detail per mahasiswa
4. Klik **Export CSV** untuk unduh semua data

---

## ⚙️ Fitur Sistem

| Fitur | Keterangan |
|-------|-----------|
| ✅ Registrasi | NIM + email unik, otomatis role mahasiswa |
| ✅ Timer 120 menit | Berjalan sejak login, dijeda saat logout |
| ✅ Lanjut timer | Login ulang → lanjut dari detik terakhir |
| ✅ Auto-save jawaban | Tersimpan saat blur textarea + tombol Simpan |
| ✅ Estimasi nilai | Keyword matching terhadap kunci jawaban |
| ✅ Form selesai | Nilai harapan + alasan mahasiswa |
| ✅ Dashboard dosen | Pantau semua mahasiswa real-time |
| ✅ Export CSV | Unduh semua jawaban + nilai |

---

## 🔒 Keamanan
- CSRF protection aktif
- Role-based middleware (mahasiswa vs dosen)
- Mass assignment protection
- Password di-hash dengan bcrypt

---

## 📂 Struktur Direktori Penting

```
app/
├── Http/Controllers/
│   ├── Auth/AuthController.php     ← Login, Register, Logout
│   ├── ExamController.php          ← Halaman soal, simpan jawaban, timer
│   └── DosenController.php         ← Dashboard, detail, export CSV
├── Models/
│   ├── User.php                    ← Model mahasiswa/dosen
│   ├── Question.php                ← Model soal + kunci jawaban
│   ├── Answer.php                  ← Model jawaban mahasiswa + estimasi skor
│   └── ExamSession.php             ← Model sesi ujian + timer
database/
├── migrations/                     ← Struktur tabel
└── seeders/
    ├── DatabaseSeeder.php          ← Akun dosen default
    └── QuestionSeeder.php          ← 20 soal UAS + kunci jawaban
resources/views/
├── auth/{login,register}.blade.php
├── exam/{index,finish,hasil}.blade.php
├── dosen/{dashboard,detail}.blade.php
└── layouts/app.blade.php
routes/web.php                      ← Semua route
```
