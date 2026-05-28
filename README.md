# 📦 Ultimate Inventory Suite & Infrastructure Monitor

Sebuah **Sistem Manajemen Inventaris** tingkat lanjut (*production-ready*) yang dirancang menggunakan **Laravel 12**, **Spatie Permissions**, **Tailwind CSS**, dan **Alpine.js**. Aplikasi ini dilengkapi dengan integrasi peta gudang geografis menggunakan **Leaflet.js**, pemantau performa sistem secara realtime (*system metrics monitor*) menggunakan **Chart.js**, dan kompilasi laporan asinkron menggunakan antrean database (*Laravel Database Queues*).

---

<p align="left">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2" />
  <img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Leaflet-199900?style=for-the-badge&logo=leaflet&logoColor=white" alt="Leaflet.js" />
  <img src="https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white" alt="Chart.js" />
</p>

---

## 🚀 Fitur Utama

### 🗺️ Peta Gudang Geografis (Leaflet.js)
* **Peta Interaktif Realtime**: Menampilkan koordinat geografis nyata dari seluruh pusat logistik terdaftar menggunakan OpenStreetMap.
* **Bilah Samping Fokus Kamera**: Klik pada kartu informasi gudang di bilah samping untuk memutar kamera peta (*pan and zoom*) secara otomatis ke titik penanda (*marker*) dan menampilkan popup detailnya.
* **Sinkronisasi Koordinat Instan**: Geser (*drag-and-drop*) penanda di peta preview saat membuat/mengedit gudang untuk mengisi koordinat Latitude & Longitude di formulir secara otomatis.

### 📊 Diagnostik Sistem Realtime & Error Console
* **Grafik Gelombang Realtime (Chart.js)**: Grafik area interaktif yang menampilkan data CPU, beban memori (RAM), dan Latency aplikasi secara mulus.
* **Pemantauan AJAX Polling**: Data metrik diperbarui secara otomatis di latar belakang setiap 3 detik menggunakan Fetch API.
* **Error Console Terintegrasi**: Kotak pencarian dan filter log berdasarkan tingkat bahaya (`critical` berdenyut merah, `warning` bersinar kuning, dan `info` biru).
* **Simulator Log Error**: Masukkan isu acak secara instan atau simulasikan sendiri data pengecualian (*exception*) ke dalam log database SQLite.

### 🔍 Filter Pencarian, Pengurutan, dan Paginasi Advanced
* **Pencarian Cerdas**: Pencarian multi-kolom berdasarkan nama produk, SKU, atau nama kategori secara bersamaan.
* **Filter Presisi**: Penyaringan katalog berdasarkan Kategori, Supplier, Warehouse, dan Alert Stok Rendah.
* **Indikator Loading Otomatis**: Dropdown penyaring akan mengirimkan formulir secara otomatis sambil menampilkan overlay pemuatan (*spinning loader*) yang transparan.
* **Menjaga State Query**: Mempertahankan filter dropdown dan kata kunci pencarian pada tautan paginasi ketika pengguna berpindah halaman produk.

### ⚙️ Pemrosesan Latar Belakang (Queue)
* **`ProductReportJob`**: Mengompilasi katalog produk berukuran besar, membuat berkas Excel di latar belakang, menyimpannya di *public storage*, dan memberi notifikasi kepada pengguna setelah selesai.
* **`LowStockNotificationJob`**: Memindai gudang yang kekurangan stok secara asinkron tanpa membebani server web utama, lalu mengirimkan notifikasi database kepada administrator.
* **Ledger Status Pekerjaan**: Menampilkan status antrean secara realtime (`pending`, `processing`, `completed`, `failed`).

---

## 🛠️ Arsitektur Teknologi

* **Back-end Utama**: Laravel 12 (PHP 8.2+)
* **Penyimpanan Database**: SQLite 3
* **Struktur Frontend**: Blade Templates + Tailwind CSS (Utility-First styling)
* **Interaktivitas UI**: Alpine.js (State management) & Vanilla ES6 JavaScript
* **Pemetaan Geografis**: Leaflet.js & OpenStreetMap
* **Visualisasi Performa**: Chart.js (Area gradient curves)
* **Manajemen Peran**: Spatie Laravel-Permission (Admin, Manager Gudang, Staff Gudang, Viewer)
* **Log Aktivitas**: Spatie Laravel-Activitylog (Auth logging dan pelacakan CRUD produk)

---

## 📥 Panduan Instalasi

Ikuti langkah-langkah berikut untuk memasang aplikasi di komputer lokal Anda:

### 1. Kloning Repositori
```bash
git clone https://github.com/username-anda/inventory-system.git
cd inventory-system
```

### 2. Pasang Dependensi Composer (PHP)
```bash
composer install
```

### 3. Pasang Paket NPM & Kompilasi Aset (Frontend)
```bash
npm install
npm run build
```

---

## ⚙️ Konfigurasi Environment

### 1. Salin Berkas Lingkungan
Salin berkas contoh konfigurasi ke berkas `.env` aktif:
```bash
copy .env.example .env
```

### 2. Pastikan Konfigurasi Database di Berkas `.env`
Gunakan SQLite dan aktifkan driver queue database seperti berikut:
```env
DB_CONNECTION=sqlite
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=15
```

### 3. Generate Kunci Keamanan Aplikasi
```bash
php artisan key:generate
```

---

## 🗄️ Migrasi & Penyemaian Database

Jalankan skema database dan semai seluruh data uji komersial berkualitas tinggi secara instan:
```bash
php artisan migrate:fresh --seed
```

Perintah ini akan menjalankan:
1. `RoleSeeder`: Mengonfigurasi hak akses peran standard.
2. `AdminSeeder`: Membuat akun operator default.
3. `InventorySeeder`: Menyemai **8 kategori**, **10 suppliers**, **5 warehouses**, **35 produk** (25 normal, 10 stok rendah), **distribusi stok gudang**, dan **100 riwayat transaksi**.

---

## ⚙️ Menjalankan Pekerja Antrean (Queue Worker)

Pekerjaan yang memakan waktu lama diproses asinkron di latar belakang. Jalankan pekerja antrean di terminal Anda untuk mulai memproses ekspor Excel dan pemindaian stok rendah:
```bash
php artisan queue:work
```

---

## 🔐 Akun Login Default

Gunakan kredensial berikut untuk masuk ke dalam aplikasi setelah proses seeding selesai:

| Peran | Alamat Email | Kata Sandi |
|---|---|---|
| **Admin** (Akses Penuh) | `admin@example.com` | `password` |
| **Staff Gudang** (Operator) | `operator@example.com` | `password` |

---

## 📂 Struktur Folder Penting

```text
├── app/
│   ├── Exports/            # Ekspor spreadsheet Maatwebsite
│   │   └── ProductsExport.php
│   ├── Http/Controllers/
│   │   ├── MonitoringController.php   # Diagnostik Sistem & Simulator
│   │   ├── QueueTriggerController.php  # Pemicu Eksekusi Background Job
│   │   └── WarehouseController.php     # CRUD & Geografis Gudang
│   ├── Jobs/               # Pekerjaan Latar Belakang (Asinkron)
│   │   ├── LowStockNotificationJob.php
│   │   └── ProductReportJob.php
│   ├── Models/             # Model Database Eloquent
│   │   ├── MonitoringLog.php
│   │   └── Report.php
│   └── Notifications/      # Notifikasi Database Lonceng
│       └── ReportGeneratedNotification.php
├── database/
│   ├── factories/          # Factory Dummy Data (Integrasi Faker)
│   └── seeders/            # Penyemai Data Awal
│       └── InventorySeeder.php
└── resources/views/
    ├── components/         # Blade Components Reusable (sidebar-link, dll.)
    ├── errors/             # Desain Halaman Error Kustom (403, 404)
    ├── monitoring/         # Dashboard Diagnostik Realtime
    └── warehouses/         # Halaman Peta Interaktif Leaflet
```

---

## 🛡️ Pengerasan Keamanan (Security Hardening)

* **Kompleksitas Kata Sandi**: Memaksa penggunaan kata sandi berkekuatan tinggi secara global (min 8 karakter, wajib kombinasi huruf besar-kecil, angka, dan simbol khusus).
* **Timeout Sesi Idle**: Sesi pengguna otomatis dihancurkan setelah **15 menit tidak ada aktivitas** untuk melindungi dari penyalahgunaan perangkat fisik.
* **Anti-Brute Force (Throttling)**: Membatasi maksimal 5 kali kegagalan login berturut-turut sebelum akun ditangguhkan sementara.
* **Pencegahan SQLi**: Seluruh transaksi database menggunakan Eloquent ORM parameter binding guna menutup celah injeksi SQL manual.
* **Proteksi XSS**: Double curly braces `{{ }}` pada Blade mensterilkan skrip HTML berbahaya secara dinamis.
* **Validasi CSRF**: Token verifikasi `@csrf` divalidasi ketat pada seluruh formulir transaksi data.

---

## 🔮 Pengembangan Masa Depan

1. **Backup Server Otomatis**: Menerapkan pencadangan otomatis berkala (*cron task*) untuk database SQLite dan aset media.
2. **Dukungan Multi-Bahasa**: Menyediakan file lokalisasi terjemahan lengkap untuk Bahasa Indonesia dan Inggris.
3. **Analisis Login & Audit Trail**: Menampilkan grafik visual pola waktu masuk pengguna dan riwayat perubahan data krusial.

---

## ✍️ Penulis

* **Antigravity** — Lead Pair-Programming AI Agent, Google DeepMind team.
* **Tim Proyek** — Advanced Agentic Coding Workspace.
