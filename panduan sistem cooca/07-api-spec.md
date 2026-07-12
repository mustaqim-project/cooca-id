# COOCA.ID — API Specification
## Bagian 7 dari Rangkaian Dokumentasi

**Base URL:** `https://api.cooca.id/api/v1`
**Format:** JSON (Content-Type: application/json)
**Autentikasi:** Laravel Sanctum (Bearer Token), multi-guard (`customer`, `affiliator`, `admin`)
**Konvensi Error:** RFC 7807-like — `{ "error_code": "...", "message": "...", "errors": {...} }`

---

# 1. Authentication Endpoints

## 1.1 POST /auth/register
**Request:**
```json
{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "password": "Password123",
  "password_confirmation": "Password123",
  "phone": "+6281234567890",
  "affiliate_code": "SARI2026"
}
```
**Response 201:**
```json
{
  "data": {
    "id": "b1e6...uuid",
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "status": "unverified"
  },
  "message": "Registrasi berhasil, silakan cek email untuk verifikasi."
}
```
**Auth:** Public
**Validation:** name (required, max:255), email (required, unique, email), password (required, min:8, confirmed), phone (required, unique, format Indonesia), affiliate_code (nullable, exists & aktif)
**Error Codes:** `AUTH_EMAIL_EXISTS` (422), `AUTH_INVALID_AFFILIATE_CODE` (422)

## 1.2 POST /auth/login
**Request:** `{ "email": "...", "password": "..." }`
**Response 200:**
```json
{ "data": { "token": "1|abcdef...", "user": { "id": "...", "name": "...", "user_type": "customer" } } }
```
**Auth:** Public | **Error Codes:** `AUTH_INVALID_CREDENTIALS` (401), `AUTH_ACCOUNT_LOCKED` (429), `AUTH_EMAIL_NOT_VERIFIED` (403)

## 1.3 GET /auth/google/redirect → 302 Redirect ke Google OAuth Consent Screen
## 1.4 GET /auth/google/callback → Membuat/login user, redirect ke frontend dengan token
## 1.5 GET /auth/verify-email/{token} → 200 OK / `AUTH_TOKEN_EXPIRED` (410)
## 1.6 POST /auth/resend-verification → Auth: Sanctum | Rate limit: 1x/60 detik
## 1.7 POST /auth/forgot-password → Request: `{ "email": "..." }` → mengirim email reset
## 1.8 POST /auth/reset-password → Request: `{ "token", "password", "password_confirmation" }`
## 1.9 POST /auth/logout → Auth: Sanctum → Revoke token aktif

---

# 2. Customer Profile Endpoints

## 2.1 GET /customer/company-profile
**Auth:** Sanctum (guard: customer) | **Response 200:** data profil atau `null` jika belum dibuat

## 2.2 POST /customer/company-profile
**Request:**
```json
{ "company_name": "Bengkel Jaya Motor", "business_type": "automotive_workshop", "address": "Jl. Merdeka No. 1", "city": "Jakarta", "province": "DKI Jakarta", "npwp": null }
```
**Response 201:** data profil tersimpan | **Error:** `PROFILE_VALIDATION_ERROR` (422)

## 2.3 PUT /customer/company-profile → Update profil, response 200
## 2.4 GET/POST/PUT/DELETE /customer/business-profiles → CRUD standar, scoped ke user_id

---

# 3. Marketplace Endpoints (Public)

## 3.1 GET /marketplace/products
**Query Params:** `category`, `min_price`, `max_price`, `search`, `page`
**Response 200:**
```json
{
  "data": [
    { "id": "...", "name": "Bagema ERP", "slug": "bagema-erp", "category": "Otomotif", "starting_price": 350000, "thumbnail": "https://..." }
  ],
  "meta": { "current_page": 1, "total": 12 }
}
```

## 3.2 GET /marketplace/products/{slug}
**Response 200:** detail lengkap termasuk modules, features, subscription_plans, versions
**Error:** `CATALOG_PRODUCT_NOT_FOUND` (404)

---

# 4. Trial Endpoints

## 4.1 POST /customer/trials
**Request:**
```json
{ "erp_product_id": "uuid", "subscription_plan_id": "uuid", "subdomain": "bengkeljaya" }
```
**Response 201:**
```json
{ "data": { "id": "uuid", "status": "submitted", "erp_product": "Bagema ERP", "subdomain": "bengkeljaya.cooca.id" } }
```
**Error Codes:** `TRIAL_QUOTA_EXCEEDED` (422), `TRIAL_SUBDOMAIN_TAKEN` (422), `TRIAL_PROFILE_INCOMPLETE` (403)

## 4.2 GET /customer/trials → List trial milik customer (paginated)
## 4.3 GET /customer/trials/{id} → Detail + status history
## 4.4 POST /admin/trials/{id}/approve → Auth: Admin | Response 200: trigger provisioning job
## 4.5 POST /admin/trials/{id}/reject
**Request:** `{ "rejection_reason": "Subdomain tidak sesuai kebijakan penamaan" }`

---

# 5. Provisioning Endpoints (Internal & Admin)

## 5.1 GET /admin/provisioning-jobs → List dengan filter status
## 5.2 GET /admin/provisioning-jobs/{id} → Detail termasuk seluruh provisioning_steps
## 5.3 POST /admin/provisioning-jobs/{id}/retry → Auth: Admin
## 5.4 POST /admin/provisioning-jobs/{id}/rollback → Auth: Admin

### Endpoint Kontrak yang WAJIB Diimplementasikan oleh Setiap Produk ERP Mitra (Tenant Side)
| Endpoint (di server Tenant ERP) | Method | Fungsi |
|---|---|---|
| /provisioning/create-database | POST | Membuat database dan user MySQL sesuai kredensial dari COOCA |
| /provisioning/migrate | POST | Menjalankan `php artisan migrate` |
| /provisioning/seed | POST | Menjalankan `php artisan db:seed` |
| /api/health | GET | Mengembalikan `{ "status": "ok" }` bila aplikasi siap |

Autentikasi endpoint ini menggunakan **shared secret HMAC signature** per request, dikirim melalui header `X-COOCA-Signature`.

---

# 6. Subscription & Invoice Endpoints

## 6.1 GET /customer/subscriptions
## 6.2 POST /customer/subscriptions
**Request:** `{ "trial_id": "uuid" }` atau `{ "erp_product_id", "subscription_plan_id" }` (subscribe langsung tanpa trial)
**Response 201:** subscription berstatus `pending`, otomatis membuat invoice terkait

## 6.3 POST /customer/subscriptions/{id}/upgrade
**Request:** `{ "subscription_plan_id": "uuid" }` → menghasilkan invoice prorata

## 6.4 POST /customer/subscriptions/{id}/downgrade
## 6.5 POST /customer/subscriptions/{id}/cancel
## 6.6 GET /customer/invoices → List invoice
## 6.7 POST /customer/invoices/{id}/pay
**Response 200:**
```json
{ "data": { "snap_token": "abc123...", "snap_redirect_url": "https://app.sandbox.midtrans.com/snap/v2/vtweb/abc123" } }
```

## 6.8 POST /webhook/midtrans/callback
**Auth:** Signature validation (bukan Sanctum)
**Request (dari Midtrans):**
```json
{ "order_id": "INV-2026-0001", "status_code": "200", "gross_amount": "350000.00", "transaction_status": "settlement", "signature_key": "..." }
```
**Response 200:** `{ "message": "OK" }` (wajib selalu 200 jika signature valid, agar Midtrans tidak retry berlebihan)
**Error:** `PAY_SIGNATURE_INVALID` (403)

---

# 7. Affiliate Endpoints

## 7.1 POST /affiliator/register → Mendaftar sebagai affiliator (dari akun customer/user existing atau baru)
## 7.2 GET /affiliator/dashboard-summary → Total komisi, pending, available, jumlah referral
## 7.3 GET /affiliator/referrals → List customer hasil referral
## 7.4 GET /affiliator/commissions → List komisi dengan filter status
## 7.5 GET /affiliator/referral-qr → Response: URL gambar QR Code
## 7.6 POST /affiliator/withdrawals
**Request:** `{ "amount": 500000, "bank_name": "BCA", "bank_account_number": "1234567890" }`
**Error:** `AFF_WITHDRAWAL_BELOW_MINIMUM` (422), `AFF_INSUFFICIENT_AVAILABLE_BALANCE` (422)
## 7.7 POST /admin/withdrawals/{id}/approve
## 7.8 POST /admin/withdrawals/{id}/reject

---

# 8. Domain & License Endpoints

## 8.1 GET /customer/domains
## 8.2 POST /customer/domains
**Request:** `{ "domain_name": "erp.bengkeljaya.com" }`
**Response 201:** `{ "data": { "verification_token": "cooca-verify=abc123...", "status": "pending_verification" } }`
## 8.3 POST /customer/domains/{id}/verify → melakukan DNS TXT lookup, response status
## 8.4 GET /customer/license → Menampilkan license key aktif (masked, hanya beberapa karakter terakhir ditampilkan penuh)
## 8.5 GET /customer/api-tokens, POST /customer/api-tokens, DELETE /customer/api-tokens/{id}

---

# 9. Notification (Admin) Endpoints

## 9.1 GET /admin/notifications → Filter: channel, status, event_code
## 9.2 GET/POST/PUT /admin/notification-templates
## 9.3 GET/PUT /admin/settings/smtp
**Request PUT:** `{ "host": "smtp.provider.com", "port": 587, "username": "...", "password": "...", "encryption": "tls", "from_address": "no-reply@cooca.id", "from_name": "COOCA.ID" }`
## 9.4 GET/PUT /admin/settings/whatsapp

---

# 10. Ticketing Endpoints

## 10.1 GET/POST /customer/tickets (atau /affiliator/tickets)
## 10.2 GET /customer/tickets/{id}
## 10.3 POST /customer/tickets/{id}/replies
## 10.4 GET /admin/tickets → Filter: priority, status, assigned_to
## 10.5 POST /admin/tickets/{id}/assign
## 10.6 POST /admin/tickets/{id}/close

---

# 11. Admin Panel — Entitas Manajemen Umum

Seluruh entitas berikut mengikuti pola RESTful standar (`GET` list, `GET` detail, `POST` create, `PUT` update, `DELETE` soft-delete) di bawah prefix `/admin/`:

`erp-categories`, `erp-products`, `erp-modules`, `erp-versions`, `erp-features`, `subscription-plans`, `coupons`, `promotions`, `blog-posts`, `faqs`, `documentation-pages`, `testimonials`, `roles`, `permissions`, `users`.

---

# 12. Konvensi Umum

## 12.1 Format Response Sukses
```json
{ "data": { }, "message": "..." }
```
atau untuk list dengan pagination:
```json
{ "data": [ ], "meta": { "current_page": 1, "per_page": 20, "total": 100 }, "links": { } }
```

## 12.2 Format Response Error
```json
{ "error_code": "TRIAL_QUOTA_EXCEEDED", "message": "Anda sudah pernah mengajukan trial untuk produk ini.", "errors": {} }
```

## 12.3 HTTP Status Code Standar

| Kode | Penggunaan |
|---|---|
| 200 | Sukses (GET, PUT, aksi tanpa resource baru) |
| 201 | Resource baru berhasil dibuat |
| 400 | Bad Request (payload tidak valid secara struktural) |
| 401 | Tidak terautentikasi |
| 403 | Terautentikasi tapi tidak memiliki izin / prasyarat belum terpenuhi |
| 404 | Resource tidak ditemukan |
| 409 | Konflik state (misal aksi tidak valid untuk status saat ini) |
| 422 | Validasi gagal |
| 429 | Rate limit terlampaui |
| 500 | Kesalahan server internal |

## 12.4 Rate Limiting
Menggunakan Laravel Rate Limiter dengan driver `database` (bukan Redis): endpoint publik sensitif (`login`, `register`, `forgot-password`) dibatasi 5 request/menit per kombinasi IP+email; endpoint umum dibatasi 60 request/menit per token.

---

*Lanjut ke Bagian 8: Role & Permission Matrix dan Security Design pada file `08-security-rbac.md`.*
