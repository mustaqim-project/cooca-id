# COOCA.ID — Testing Documentation
## Bagian 10 dari Rangkaian Dokumentasi

---

# 1. Unit Test Scenario

| Kelas/Service | Skenario | Expected Result |
|---|---|---|
| `TrialEligibilityService` | Customer profil belum lengkap mengajukan trial | Exception `TrialProfileIncompleteException` |
| `TrialEligibilityService` | Customer sudah pernah trial produk sama (non-rejected) | Exception `TrialQuotaExceededException` |
| `CommissionCalculationService` | Invoice Rp 350.000 dengan referral Level1+Level2 | Level1 = Rp 87.500 (25%), Level2 = Rp 17.500 (5%) |
| `CommissionCalculationService` | Invoice tanpa referral | Tidak ada Commission dibuat |
| `LicenseSigningService` | Generate signature untuk tenant tertentu | Signature valid saat diverifikasi ulang dengan public key/secret yang sama |
| `LicenseSigningService` | Verifikasi signature dengan payload yang telah diubah | Verifikasi gagal (invalid signature) |
| `SubscriptionRenewalService` | Subscription `active_until` H-7 | Invoice perpanjangan baru dibuat, status tetap Active |
| `SubscriptionRenewalService` | Subscription melewati grace period 3 hari tanpa bayar | Status berubah menjadi Suspended |
| `MidtransSignatureValidator` | Signature valid sesuai rumus SHA512 | Return true |
| `MidtransSignatureValidator` | Signature dimanipulasi | Return false, callback ditolak |

---

# 2. Feature Test (Level Modul)

| Modul | Skenario | Expected Result |
|---|---|---|
| Authentication | Register dengan email valid | User dibuat, email verifikasi terkirim (mock Mail::fake) |
| Authentication | Login 6x gagal berturut-turut | Response 429 pada percobaan ke-6 |
| Trial Management | Ajukan trial dengan subdomain sudah dipakai | Response 422 `TRIAL_SUBDOMAIN_TAKEN` |
| Trial Management | Admin approve trial | ProvisioningJob dibuat berstatus Queued |
| Subscription | Upgrade plan mid-cycle | Invoice prorata dibuat dengan nominal sesuai perhitungan sisa hari |
| Invoice & Payment | Callback Midtrans dengan signature valid | Invoice berubah status Paid, Subscription Active |
| Invoice & Payment | Callback Midtrans duplikat (order_id sama, status sama) | Invoice tetap Paid, tidak ada Commission kedua dibuat |
| Affiliate | Withdrawal request di bawah minimum | Response 422 `AFF_WITHDRAWAL_BELOW_MINIMUM` |
| Notification | Event TrialSubmitted dipicu | Record `notifications` dibuat dengan channel=email, status=Queued |

---

# 3. Integration Test

| Skenario | Sistem Terlibat | Expected Result |
|---|---|---|
| End-to-end Trial hingga ActiveTrial | Trial Module + Provisioning Engine + Tenant ERP Mock Server | Status Trial berubah bertahap sesuai state machine hingga ActiveTrial, tenant dapat diakses via health check |
| End-to-end Subscription Payment | Subscription + Invoice + Midtrans Sandbox + Commission | Status Subscription Active, Commission Level1/2 tercatat sesuai referral |
| Notification Cross-Channel | Notification Service + SMTP Sandbox + Fonnte Sandbox | Email terkirim sukses meskipun WhatsApp API down (independent channel, tidak saling menggagalkan) |
| Provisioning Rollback | Provisioning Engine + Tenant ERP Mock (simulasi gagal di step migration) | Database ter-drop, status ProvisioningJob = RolledBack, notifikasi kegagalan terkirim |

---

# 4. API Test

| Endpoint | Test Case | Expected Response |
|---|---|---|
| POST /auth/register | Payload lengkap valid | 201, data user tanpa password ter-expose |
| POST /auth/register | Email duplikat | 422, error_code AUTH_EMAIL_EXISTS |
| POST /customer/trials | Tanpa Bearer Token | 401 |
| POST /customer/trials | Token guard salah (affiliator mencoba akses endpoint customer) | 403 |
| POST /webhook/midtrans/callback | Signature invalid | 403, error_code PAY_SIGNATURE_INVALID |
| GET /marketplace/products | Filter kategori tidak ditemukan | 200, data kosong (bukan error) |
| POST /affiliator/withdrawals | Saldo Available cukup | 201, status Requested |

Automasi menggunakan **Pest PHP** atau **PHPUnit** dengan `RefreshDatabase` trait dan seeding data uji terisolasi per test case.

---

# 5. UAT (User Acceptance Testing)

| Skenario UAT | Peserta | Kriteria Diterima |
|---|---|---|
| Customer baru menyelesaikan trial hingga aktif tanpa bantuan tim | 5 pengguna representatif UMKM | Minimal 4 dari 5 peserta berhasil tanpa bantuan dalam waktu < 20 menit |
| Affiliator mendaftar dan melacak komisi pertama | 3 mitra afiliasi pilot | Seluruh peserta memahami dashboard tanpa penjelasan tambahan |
| Admin memproses approval trial dan memantau provisioning | Tim Admin Internal | Waktu rata-rata approval < 5 menit per trial |

---

# 6. Security Test

| Kategori | Test | Alat Bantu |
|---|---|---|
| Injection | SQLi pada seluruh input form (login, search, filter) | OWASP ZAP / manual payload testing |
| XSS | Input skrip pada field bebas teks (nama perusahaan, deskripsi ticket) | Manual + Burp Suite |
| Broken Authentication | Brute force login, session fixation | Manual scripted test |
| Sensitive Data Exposure | Cek response API tidak membocorkan password hash/token mentah | Manual review response payload |
| Webhook Forgery | Kirim callback Midtrans palsu dengan signature acak | Manual test, harus ditolak 403 |
| File Upload | Upload file `.php` menyamar sebagai `.jpg` | Manual test, harus ditolak validasi MIME |

---

# 7. Performance Test

| Skenario | Target | Alat |
|---|---|---|
| Load 200 concurrent users browsing Marketplace | Response time P95 < 500ms, tidak ada error 5xx | k6 / Apache JMeter |
| Stress test endpoint login | Mengetahui breaking point pada shared hosting (CPU/proses limit) | k6 (ramping VUs) |
| Queue throughput | Job database queue diproses stabil oleh cron per menit tanpa penumpukan signifikan | Custom script + monitoring tabel `jobs` |

---

# 8. Load Test

Dilakukan bertahap: 50 → 100 → 200 → 300 concurrent users, mencatat titik di mana response time atau error rate melebihi ambang batas yang diterima, mengingat keterbatasan resource shared hosting (jumlah proses PHP-FPM simultan terbatas sesuai paket hosting yang digunakan).

---

# 9. Regression Test

Dijalankan otomatis pada setiap CI/CD pipeline (lihat Bagian 11 DevOps Documentation) mencakup seluruh Feature Test dan API Test di atas, memastikan perubahan kode baru tidak merusak fungsionalitas modul lain — khususnya modul finansial (Payment, Commission) yang paling kritikal terhadap regresi.

---

*Lanjut ke Bagian 11: DevOps Documentation pada file `11-devops.md`.*
