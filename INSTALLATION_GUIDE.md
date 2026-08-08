# Panduan Instalasi & Deployment Production — COOCA.ID

**Domain Target:** `https://cooca.id`  
**Platform:** Enterprise Single Business Management Platform & WhatsApp Gateway  
**Stack Utama:** Laravel 12 (PHP 8.3), MySQL 8, Node.js (Baileys WA Gateway), PM2 / Supervisor, Nginx / LiteSpeed.

---

## 1. Persyaratan Sistem (System Requirements)

| Komponen | Versi Minimal | Keterangan |
| :--- | :--- | :--- |
| **PHP** | `^8.3` | Wajib ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `xml`, `zip`, `gd`, `bcmath`, `fileinfo`, `opcache` |
| **Database** | MySQL `^8.0` / MariaDB `^10.5` | Character Set: `utf8mb4_unicode_ci` |
| **Node.js** | `^18.x` / `^20.x` LTS | Diperlukan untuk Node WhatsApp Server Gateway (`wa-server`) |
| **Web Server** | Nginx / Apache / LiteSpeed | Didukung SSL HTTPS Wajib |
| **Process Manager** | PM2 / Supervisor | Untuk menjaga Node WA Gateway dan Queue Worker tetap berjalan |

---

## 2. Langkah-Langkah Instalasi Server Produksi (VPS / Cloud)

### Langkah 1: Clone / Upload Repository
```bash
cd /var/www
git clone https://github.com/username/cooca-id.git cooca-id
cd cooca-id
```

---

### Langkah 2: Konfigurasi Environment (`.env`) untuk Domain `cooca.id`
Buat file `.env` di folder utama aplikasi (`/var/www/cooca-id/.env`):

```env
APP_NAME="COOCA.ID"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cooca.id

# Keamanan Aplikasi
APP_KEY=

# Database MySQL Produksi
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cooca_production
DB_USERNAME=cooca_user
DB_PASSWORD=PasswordDatabaseRahasia123!

# Driver Antrean & Sesi Database (Optimized Shared Hosting / VPS)
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database

# Gateway Pembayaran Midtrans Produksi
MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=true

# Node WhatsApp Gateway Server
WA_SERVER_URL=http://127.0.0.1:3000
WA_WORKER_TOKEN=SecretWorkerTokenCOOCA2026

# Konfigurasi SMTP Email Produksi
MAIL_MAILER=smtp
MAIL_HOST=mail.cooca.id
MAIL_PORT=465
MAIL_USERNAME=noreply@cooca.id
MAIL_PASSWORD=PasswordEmailRahasia123!
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cooca.id"
MAIL_FROM_NAME="COOCA.ID Official"
```

---

### Langkah 3: Install Dependensi & Build Aplikasi
Jalankan perintah berikut di folder aplikasi:

```bash
# 1. Install Dependensi PHP tanpa dev packages
composer install --no-dev --optimize-autoloader

# 2. Generate Key Aplikasi Laravel
php artisan key:generate --force

# 3. Jalankan Migrasi Database
php artisan migrate --force

# 4. Buat Symlink Storage Media
php artisan storage:link

# 5. Install Paket Node & Build Assets Frontend (Vite & Tailwind)
npm install
npm run build

# 6. Jalankan Optimasi Caching Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

### Langkah 4: Setup Node.js WhatsApp Gateway Server (`wa-server`)
Node WhatsApp Gateway bertindak sebagai *REST API WhatsApp Engine (Fonnte-Style)*.

```bash
# Masuk ke direktori wa-server
cd /var/www/cooca-id/wa-server

# Install dependensi Node produksi
npm install --production

# Install PM2 Process Manager
sudo npm install -g pm2

# Jalankan server.js di PM2
pm2 start server.js --name "cooca-wa-gateway"

# Simpan status PM2 agar otomatis jalan saat server restart
pm2 save
pm2 startup
```

---

### Langkah 5: Setup Background Queue Worker & Scheduler

#### **A. Menggunakan Supervisor (Rekomendasi VPS)**
Buat file `/etc/supervisor/conf.d/cooca-worker.conf`:

```ini
[program:cooca-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/cooca-id/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/cooca-id/storage/logs/worker.log
```

Aktifkan Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start cooca-worker:*
```

#### **B. Setup Cron Job Scheduler (Wajib)**
Buka crontab: `crontab -e` dan tambahkan perintah:
```bash
* * * * * cd /var/www/cooca-id && php artisan schedule:run >> /dev/null 2>&1
```

---

### Langkah 6: Konfigurasi Nginx Server Block (`https://cooca.id`)

Buat file `/etc/nginx/sites-available/cooca.id`:

```nginx
server {
    listen 80;
    server_name cooca.id www.cooca.id;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name cooca.id www.cooca.id;

    root /var/www/cooca-id/public;
    index index.php index.html;

    # SSL Certificate (Certbot Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/cooca.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/cooca.id/privkey.pem;

    # Header Keamanan Produksi
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Aktifkan konfigurasi Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/cooca.id /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 3. Instalasi di Shared Hosting (cPanel)

Jika diinstal di **cPanel Shared Hosting**:

1. Upload seluruh file ke `public_html`.
2. Pilih versi **PHP 8.3** pada menu *Select PHP Version* di cPanel.
3. Buat Aplikasi Node.js di menu **Setup Node.js App** (App root: `wa-server`, Startup file: `server.js`).
4. Tambahkan perintah **Cron Job** di cPanel (Berjalan tiap 1 menit):
   ```bash
   * * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
   ```

---

## 4. Verifikasi & Pengujian Pasca-Instalasi

Setelah instalasi selesai, lakukan pengujian berikut:

1. **Akses Portal Admin**: `https://cooca.id/admin/login`
2. **Akses Portal Customer**: `https://cooca.id/customer/login`
3. **Pengujian WhatsApp Gateway**: Buka menu **WhatsApp API Generator** di Admin/Customer Portal -> Klik **Generate WA API Baru** -> Scan QR Code.
4. **Verifikasi Webhook Midtrans**: Daftarkan Notification URL di Dashboard Midtrans: `https://cooca.id/api/v1/midtrans/webhook`.

---

## 5. Cheat Sheet Perintah Pemeliharaan Rutin

```bash
# Membersihkan seluruh cache saat ada update sistem
php artisan optimize:clear && php artisan config:cache && php artisan route:cache

# Mengecek status proses PM2 (WhatsApp Gateway)
pm2 status

# Restart WhatsApp Gateway Node
pm2 restart cooca-wa-gateway

# Mengecek log antrean worker Laravel
tail -f /var/www/cooca-id/storage/logs/worker.log
```
