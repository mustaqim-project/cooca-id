# COOCA.ID — Role & Permission Matrix dan Security Design
## Bagian 8 dari Rangkaian Dokumentasi

---

# 1. Role & Permission Matrix

## 1.1 Daftar Role

| Role | Guard | Deskripsi |
|---|---|---|
| Super Admin | admin | Akses penuh termasuk pengaturan sistem kritikal (SMTP, Midtrans, Security) |
| Admin | admin | Operasional harian (approval trial, ticketing, monitoring) tanpa akses setting kritikal |
| Customer | customer | Pemilik akun utama tenant, akses penuh terhadap data tenant miliknya |
| Customer Manager | customer | Sub-user dengan akses hampir penuh kecuali billing/pembatalan subscription |
| Customer Staff | customer | Sub-user dengan akses terbatas (operasional harian saja, tanpa akses billing/domain/license) |
| Affiliator | affiliator | Akses ke data referral dan komisi miliknya sendiri |

## 1.2 Permission Matrix Detail

| Modul/Aksi | Super Admin | Admin | Customer | Customer Manager | Customer Staff | Affiliator |
|---|---|---|---|---|---|---|
| Kelola ERP Catalog (CRUD) | ✔ | ✔ | ✘ | ✘ | ✘ | ✘ |
| Approve/Reject Trial | ✔ | ✔ | ✘ | ✘ | ✘ | ✘ |
| Ajukan Trial | ✘ | ✘ | ✔ | ✔ | ✘ | ✘ |
| Kelola Subscription (upgrade/downgrade/cancel) | ✔ (override) | Read-only | ✔ | ✔ | ✘ | ✘ |
| Bayar Invoice | ✘ | ✘ | ✔ | ✔ | ✘ | ✘ |
| Kelola Domain & License | ✔ (override) | Read-only | ✔ | ✔ | ✘ | ✘ |
| Kelola API Token | ✘ | Read-only | ✔ | ✔ | ✘ | ✘ |
| Lihat Dashboard Referral & Komisi | ✘ | Read-only (semua) | ✘ | ✘ | ✘ | ✔ (miliknya) |
| Ajukan Withdrawal | ✘ | ✘ | ✘ | ✘ | ✘ | ✔ |
| Approve Withdrawal | ✔ | ✔ | ✘ | ✘ | ✘ | ✘ |
| Kelola CMS (Blog, FAQ, Docs) | ✔ | ✔ (sesuai role Content) | ✘ | ✘ | ✘ | ✘ |
| Kelola Setting SMTP/Midtrans/Security | ✔ | ✘ | ✘ | ✘ | ✘ | ✘ |
| Kelola Role & Permission | ✔ | ✘ | ✘ | ✘ | ✘ | ✘ |
| Lihat Activity Log & Audit Trail | ✔ | ✔ (scope operasional) | ✔ (miliknya) | ✘ | ✘ | ✘ |
| Buat & Balas Ticket | ✔ | ✔ | ✔ | ✔ | ✔ | ✔ |
| Kelola User Management (invite Staff/Manager) | ✘ | ✘ | ✔ | ✘ | ✘ | ✘ |

## 1.3 Implementasi Teknis
Menggunakan **Spatie Laravel-Permission** dengan struktur:
- `roles` table: `super_admin`, `admin`, `customer`, `customer_manager`, `customer_staff`, `affiliator`.
- `permissions` table granular per aksi (contoh: `trial.approve`, `subscription.cancel`, `domain.manage`, `settings.smtp.manage`).
- Middleware `can:` diterapkan pada setiap route API sesuai permission yang relevan, bukan hanya pengecekan role secara langsung — memastikan fleksibilitas penambahan role baru di masa depan tanpa mengubah kode controller.

---

# 2. Security Design

## 2.1 Authentication
- Password di-hash menggunakan **Bcrypt** (default Laravel, cost factor 12) atau **Argon2id** bila didukung PHP build hosting.
- Login API menggunakan **Laravel Sanctum** dengan Personal Access Token yang di-scope per guard (`customer`, `affiliator`, `admin`) — token dari satu guard tidak dapat digunakan mengakses endpoint guard lain.
- Login Google OAuth memvalidasi `email_verified` dari payload Google sebelum mengaktifkan akun.

## 2.2 Authorization
RBAC granular sebagaimana Bagian 1. Seluruh keputusan otorisasi dilakukan di layer **Policy** Laravel (`TrialPolicy`, `SubscriptionPolicy`, dst.), bukan dicek manual berulang di setiap Controller, untuk konsistensi dan testability.

## 2.3 RBAC
Dijabarkan pada Bagian 1. Permission bersifat additive (tidak ada permission implisit dari role lain) — audit lebih mudah karena setiap permission eksplisit tercatat per role.

## 2.4 Rate Limiting
- Endpoint sensitif publik (login, register, forgot-password, resend-verification): 5 request/menit per kombinasi IP + identifier.
- Endpoint API umum (tersertifikasi token): 60 request/menit per token.
- Implementasi menggunakan Laravel `RateLimiter` dengan **cache driver `database`** (bukan Redis), karena keterbatasan shared hosting — cache limiter menyimpan counter di tabel `cache` dengan TTL yang divalidasi ulang setiap request.

## 2.5 CSRF Protection
- Untuk route berbasis session (Guest/Customer Web routes non-API, misal form CMS/Blog admin melalui panel web), CSRF token wajib disertakan (`@csrf` Blade directive / `X-CSRF-TOKEN` header untuk AJAX).
- Endpoint API murni (`/api/v1/*`) menggunakan Sanctum token, tidak memerlukan CSRF karena bersifat stateless, KECUALI SPA Authentication (jika frontend menggunakan cookie-based Sanctum) yang tetap memerlukan CSRF cookie (`/sanctum/csrf-cookie`).

## 2.6 XSS Protection
- Seluruh output Blade menggunakan `{{ }}` (auto-escape) secara default; penggunaan `{!! !!}` (raw output) dilarang kecuali untuk konten CMS yang telah melalui sanitasi HTML (menggunakan library sanitizer seperti `HTMLPurifier`) sebelum disimpan.
- Header `Content-Security-Policy` diterapkan pada respons HTML untuk membatasi sumber skrip eksternal yang diizinkan.

## 2.7 SQL Injection Prevention
- Seluruh akses database menggunakan **Eloquent ORM** atau **Query Builder** dengan parameter binding otomatis; tidak ada raw query dengan string concatenation input pengguna.
- Apabila raw query diperlukan (kasus khusus reporting kompleks), wajib menggunakan parameter binding (`?` placeholder) dan melalui code review keamanan.

## 2.8 File Upload Security
- Validasi tipe file berdasarkan MIME type asli (bukan hanya ekstensi) menggunakan `finfo` PHP.
- Ukuran maksimal upload dibatasi (contoh: 5MB untuk dokumen, 2MB untuk gambar).
- File disimpan di luar document root yang dapat dieksekusi langsung sebagai PHP (folder `storage/app` dengan symlink terkontrol ke `public/storage`), mencegah eksekusi file berbahaya yang diunggah sebagai gambar/dokumen palsu.
- Nama file di-generate ulang (UUID) untuk mencegah path traversal dan penebakan nama file.

## 2.9 Session Management
- Session menggunakan **driver `database`** (tabel `sessions`) — konsisten meskipun proses PHP-FPM shared hosting berpindah-pindah worker.
- Session timeout default 120 menit tidak aktif; session diinvalidasi otomatis saat logout atau perubahan password.
- Login history (`login_histories`) mencatat setiap sesi aktif untuk visibilitas customer atas "Perangkat yang Login" pada Security Setting.

## 2.10 Encryption
- Data sensitif tenant (kredensial database tenant, `app_key_encrypted`) dienkripsi menggunakan Laravel `Crypt` (AES-256-CBC) dengan `APP_KEY` platform sebelum disimpan di database.
- Koneksi eksternal (Midtrans, SMTP, Fonnte, DNS API) seluruhnya melalui HTTPS/TLS 1.2+.
- License Key ditandatangani secara kriptografis (HMAC-SHA256) menggunakan secret key yang hanya diketahui platform COOCA.ID, memungkinkan aplikasi ERP tenant memverifikasi keabsahan tanpa perlu koneksi online setiap saat (offline signature verification dengan public key/shared secret yang di-embed saat provisioning).

## 2.11 Audit Trail
Sebagaimana dijabarkan pada Bagian 8 Database Design — tabel `audit_trails` generik mencatat seluruh aksi administratif kritikal dengan snapshot `old_values`/`new_values` dalam format JSON, tidak dapat diubah/dihapus melalui aplikasi (append-only, hanya dapat diakses melalui query Read).

## 2.12 Webhook Security (Midtrans)
- Signature callback divalidasi dengan rumus: `SHA512(order_id + status_code + gross_amount + ServerKey)`.
- Callback yang gagal validasi signature ditolak dengan HTTP 403 dan dicatat sebagai `SUSPICIOUS_CALLBACK` pada security log terpisah (`security_events` table) untuk monitoring potensi serangan.
- IP source callback dapat di-whitelist tambahan (opsional, sebagai defense-in-depth) sesuai daftar IP resmi Midtrans jika penyedia hosting mendukung firewall level aplikasi.

## 2.13 Security Checklist Ringkas (Untuk Referensi Cepat)

| Item | Status Wajib |
|---|---|
| HTTPS/TLS di seluruh endpoint | Wajib |
| Password hashing Bcrypt/Argon2id | Wajib |
| Rate limiting endpoint sensitif | Wajib |
| Validasi signature webhook | Wajib |
| CSP header pada respons HTML | Wajib |
| Sanitasi konten CMS (HTMLPurifier) | Wajib |
| Audit trail aksi finansial | Wajib |
| Enkripsi kredensial tenant | Wajib |
| Rotasi License Key pada indikasi pelanggaran | Wajib |
| 2FA untuk Super Admin | Direkomendasikan (Fase 2) |

---

*Lanjut ke Bagian 9: UI/UX Specification pada file `09-uiux.md`.*
