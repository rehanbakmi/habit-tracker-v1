# Sistem Prediksi Relapse Kebiasaan Berbasis Bio-Behavioral Feedback

Aplikasi web untuk melacak kebiasaan harian sekaligus memprediksi risiko relapse (kegagalan mempertahankan rutinitas) menggunakan analisis perilaku dan sentimen jurnal harian berbasis NLP.

---

## Fitur Utama

- **Habit Tracker** — catat, kelola, dan tandai habit selesai setiap hari
- **Prediksi Relapse** — skor risiko otomatis dari 3 faktor: streak, frekuensi skip, dan sentimen jurnal
- **Jurnal Mood Harian** — analisis sentimen teks menggunakan model BERT multilingual
- **Kalender Visual** — lihat histori penyelesaian habit per bulan
- **Mode Bertahan** — sistem memberikan peringatan saat risiko relapse tinggi
- **Panel Admin** — kelola user dan kategori habit
- **Role Management** — role admin dan user

---

## Teknologi

| Komponen | Teknologi |
|---|---|
| Backend utama | Laravel 11 (PHP 8.x) |
| Database | SQLite |
| Antrian & Cache | Redis |
| NLP / Sentimen | Python 3.x + FastAPI + BERT (nlptown/bert-base-multilingual-uncased-sentiment) |
| Frontend | Blade + Tailwind CSS |
| Autentikasi | Laravel Breeze |

---

## Arsitektur Sistem

```
Browser (User)
    ↓
Laravel (Port 8000)
    ├── Habit & Streak Management
    ├── RelapsePredictionService
    │       ├── Skor Streak
    │       ├── Skor Skip (7 hari terakhir)
    │       └── HTTP Request → FastAPI Python
    └── Queue Worker (Redis)

FastAPI Python (Port 8001)
    └── Model BERT → Analisis Sentimen Jurnal
            └── Kembalikan mood_score ke Laravel
```

---

## Instalasi

### Prasyarat

Pastikan sudah terinstall:
- PHP 8.x + Composer
- Python 3.8+
- Redis
- Node.js (opsional, untuk build asset)

### 1. Clone Repository

```bash
git clone https://github.com/username/habit-tracker.git
cd habit-tracker
```

### 2. Setup Laravel

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=CategorySeeder
```

Edit `.env` dan sesuaikan:
```env
DB_CONNECTION=sqlite

SENTIMENT_API_URL=http://127.0.0.1:8001
SENTIMENT_API_KEY=h4b1t-tr4ck3r-s3cr3t-k3y-2026

QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### 3. Setup Python (NLP Service)

```bash
cd ../sentiment-service
python3 -m venv venv
source venv/bin/activate
pip install fastapi uvicorn transformers torch
```

---

## Cara Menjalankan

Buka **3 terminal terpisah** dan jalankan sesuai urutan:

### Terminal 1 — Redis
```bash
sudo service redis start
```

### Terminal 2 — Python FastAPI
```bash
cd sentiment-service
source venv/bin/activate
INTERNAL_API_KEY=h4b1t-tr4ck3r-s3cr3t-k3y-2026 uvicorn main:app --host 127.0.0.1 --port 8001
```
Tunggu hingga muncul: `Model siap.`

### Terminal 3 — Laravel
```bash
cd habit-tracker
php artisan queue:work &
php artisan serve
```

Atau gunakan script otomatis:
```bash
bash run.sh
```

Buka browser: **http://localhost:8000**

---

## Akun Admin

Setelah register, set role admin lewat tinker:

```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'email@anda.com')->first();
$user->role = 'admin';
$user->save();
```

Panel admin tersedia di: **http://localhost:8000/admin**

---

## Struktur Folder Penting

```
habit-tracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HabitController.php
│   │   │   ├── JournalController.php
│   │   │   ├── CalendarController.php
│   │   │   └── AdminController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/
│   │   ├── Habit.php
│   │   ├── HabitLog.php
│   │   ├── HabitJournal.php
│   │   └── Category.php
│   └── Services/
│       └── RelapsePredictionService.php
├── resources/views/
│   ├── dashboard.blade.php
│   ├── habits/
│   ├── calendar/
│   └── admin/
├── routes/
│   └── web.php
├── run.sh
└── stop.sh

sentiment-service/
├── main.py
└── .env
```

---

## Cara Mematikan

```bash
bash stop.sh
```

Atau manual:
```bash
# Ctrl+C di terminal Laravel dan Python
sudo service redis stop
```

---

## Pengembangan Lanjutan (Future Work)

- Integrasi Google Health Connect API untuk data biologis otomatis (jam tidur, detak jantung)
- Model BiLSTM custom yang dilatih dengan dataset bahasa Indonesia
- Notifikasi email/push saat risiko relapse tinggi
- Grafik histori skor risiko harian
- Deploy ke VPS dengan Docker

---

## Lisensi

MIT License — bebas digunakan untuk keperluan akademis.
