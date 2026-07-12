# COOCA.ID — Production Readiness Checklist
## Bagian 12 (Terakhir) dari Rangkaian Dokumentasi

Checklist ini adalah gerbang wajib (mandatory gate) sebelum Go-Live. Seluruh item harus berstatus selesai (✔) dan diverifikasi oleh minimal dua peran (maker-checker) sebelum sistem dibuka untuk publik.

---

# 1. Infrastruktur & Environment

- [ ] Shared hosting mendukung PHP 8.4 dan MySQL 8 (diverifikasi via `phpinfo()` dan `SELECT VERSION();`)
- [ ] Cron job terpasang dengan interval 1 menit dan berjalan konsisten (diverifikasi via tabel `scheduler_heartbeats` minimal 24 jam observasi)
- [ ] `.env` production sudah diisi lengkap: `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY` ter-generate unik
- [ ] `QUEUE_CONNECTION=database`, `CACHE_STORE=database`, `SESSION_DRIVER=database` terkonfirmasi aktif
- [ ] SSL (AutoSSL/Let's Encrypt) aktif di domain utama `cooca.id` dan wildcard `*.cooca.id` untuk subdomain tenant
- [ ] Folder `storage` dan `bootstrap/cache` memiliki permission yang benar (writable oleh web server)
- [ ] Struktur symlink deployment (`current` → `releases/`) berfungsi dan teruji rollback

# 2. Database

- [ ] Seluruh migration berhasil dijalankan tanpa error di environment production
- [ ] Seluruh seeder data referensi (ERP Category, default Subscription Plan, Notification Template) dijalankan
- [ ] Index pada seluruh tabel sesuai spesifikasi Bagian 6 Database Design telah diverifikasi (`SHOW INDEX`)
- [ ] Backup harian (`mysqldump` + cPanel native) sudah terjadwal dan **teruji proses restore-nya minimal satu kali** di environment staging

# 3. Autentikasi & Keamanan

- [ ] Login Email/Password berfungsi end-to-end termasuk verifikasi email
- [ ] Login Google OAuth berfungsi dengan kredensial OAuth production (bukan sandbox/test client ID)
- [ ] Rate limiting login, register, forgot-password teruji memicu 429 pada percobaan berlebih
- [ ] Password hashing terverifikasi menggunakan Bcrypt/Argon2id
- [ ] Security header (CSP, X-Frame-Options, X-Content-Type-Options) aktif pada respons HTML
- [ ] Endpoint webhook Midtrans menolak signature tidak valid (403) — diuji dengan payload palsu

# 4. Payment (Midtrans)

- [ ] Kredensial Midtrans production (Server Key, Client Key) terpasang, bukan sandbox
- [ ] Snap popup berfungsi end-to-end dengan minimal satu transaksi uji nominal kecil di production
- [ ] Callback URL Midtrans terdaftar dengan benar mengarah ke endpoint `/webhook/midtrans/callback`
- [ ] Idempotency callback duplikat teruji (mengirim callback yang sama dua kali tidak menghasilkan efek ganda)
- [ ] Rekonsiliasi manual (Admin Panel) tersedia untuk kasus payment_log yang tidak sinkron dengan status invoice

# 5. Provisioning Engine

- [ ] Minimal satu produk ERP pilot (misalnya Bagema ERP) telah terintegrasi penuh dengan endpoint provisioning kontrak (`create-database`, `migrate`, `seed`, `/api/health`)
- [ ] Uji end-to-end trial dari pengajuan hingga `ActiveTrial` berhasil dalam waktu < 5 menit
- [ ] Skenario kegagalan provisioning (simulasi step gagal) teruji melakukan rollback otomatis dengan benar
- [ ] Retry manual dan rollback manual oleh Admin berfungsi sebagaimana mestinya

# 6. Notifikasi (Email SMTP & WhatsApp)

- [ ] Kredensial SMTP production terpasang dan teruji mengirim email nyata (bukan hanya log `MAIL_MAILER=log`)
- [ ] Seluruh template email (lihat daftar event FSD Modul 10.6) telah dibuat dan diuji tampilannya (tidak ada placeholder rusak)
- [ ] Kuota harian SMTP provider diketahui dan tidak berisiko terlampaui pada estimasi volume awal
- [ ] Integrasi WhatsApp (Fonnte) — jika diaktifkan — teruji terkirim, namun kegagalannya dipastikan TIDAK menghentikan proses bisnis inti
- [ ] Notification Log di Admin Panel menampilkan data secara akurat dan real-time

# 7. Affiliate Program

- [ ] Perhitungan komisi Level 1 (25%) dan Level 2 (5%) teruji dengan skenario referral dua level nyata
- [ ] Holding period 14 hari sebelum komisi Available teruji melalui scheduler
- [ ] Proses withdrawal end-to-end (request → approve → completed) teruji minimal satu kali dengan nominal riil kecil

# 8. Domain & License

- [ ] Subdomain otomatis (`*.cooca.id`) berfungsi dan dapat diakses langsung setelah provisioning
- [ ] Alur custom domain (TXT verification → CNAME → SSL) teruji end-to-end dengan domain nyata
- [ ] License Key tervalidasi oleh aplikasi ERP tenant pilot, termasuk skenario rotasi/invalidasi

# 9. Operasional & Support

- [ ] Modul Ticketing berfungsi dengan SLA tracking dan eskalasi otomatis
- [ ] Admin Panel — seluruh menu manajemen (ERP Catalog, Trial, Subscription, Affiliate, CMS) dapat diakses sesuai role masing-masing (Super Admin vs Admin)
- [ ] Activity Log dan Audit Trail tercatat dengan benar untuk aksi kritikal (approval, override, refund)

# 10. Performa & Monitoring

- [ ] Load test dasar (minimal 100 concurrent users pada endpoint Marketplace dan Login) menunjukkan response time dalam target yang dapat diterima
- [ ] External uptime monitoring (misal UptimeRobot) terpasang memantau `/api/health`
- [ ] Error tracking (misal Sentry) terpasang dan menangkap exception dengan benar
- [ ] Log rotasi (`LOG_CHANNEL=daily`) aktif untuk mencegah storage penuh

# 11. Legal & Compliance

- [ ] Halaman Syarat & Ketentuan dan Kebijakan Privasi sudah dipublikasikan dan ditautkan pada form registrasi
- [ ] Struktur invoice mematuhi format yang sesuai untuk keperluan perpajakan (jika berlaku)
- [ ] Data pribadi customer (nama, alamat, NPWP) disimpan dan diproses sesuai prinsip perlindungan data yang berlaku di Indonesia

# 12. Sign-Off

| Peran | Nama | Status | Tanggal |
|---|---|---|---|
| Product Manager | | ☐ Approved | |
| Solution Architect | | ☐ Approved | |
| Security Engineer | | ☐ Approved | |
| QA Lead | | ☐ Approved | |
| DevOps Engineer | | ☐ Approved | |
| Business Owner/Founder | | ☐ Approved | |

> **Ketentuan Go-Live:** Sistem hanya diperbolehkan Go-Live setelah SELURUH item pada checklist ini berstatus selesai dan seluruh peran pada tabel Sign-Off memberikan persetujuan tertulis (approved). Kegagalan pada satu item kategori Autentikasi & Keamanan, Payment, atau Provisioning Engine bersifat **blocking** — tidak dapat di-bypass meskipun item kategori lain sudah selesai.

---

*Dokumen ini menutup rangkaian 18 dokumen COOCA.ID (dikonsolidasikan ke dalam 12 file), mencakup Bagian 1 s.d. Bagian 18 sesuai struktur permintaan awal.*
