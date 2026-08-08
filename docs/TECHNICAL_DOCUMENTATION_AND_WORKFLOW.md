# Dokumentasi Teknis & Workflow System Enterprise COOCA.ID

**Versi Dokumentasi:** 1.0.0  
**Sistem:** `cooca-id` (Enterprise Single Business Management Platform)  
**Teknologi Utama:** Laravel 12, PHP 8.3+, MySQL 8, Livewire v4, Alpine.js, Tailwind CSS v4, Midtrans Payment Gateway, WhatsApp API.

---

## 1. Arsitektur Teknis Sistem (System Architecture)

### 1.1 Diagram Arsitektur Komponen

```mermaid
graph TD
    Client[Browser / Client App] -->|HTTPS| WebServer[Apache / LiteSpeed Web Server]
    WebServer -->|PHP-FPM| LaravelApp[Laravel 12 Application Core]
    
    subgraph Routing Layer
        LaravelApp --> AdminRoute[admin.php - Admin Panel]
        LaravelApp --> CustomerRoute[customer.php - Customer Portal]
        LaravelApp --> AffiliatorRoute[affiliator.php - Affiliate Portal]
        LaravelApp --> ApiRoute[api.php - REST API & Webhooks]
        LaravelApp --> PublicRoute[web.php - Public Landing & CMS]
    end
    
    subgraph Service & Engine Layer
        AdminRoute & CustomerRoute & ApiRoute --> SubEngine[Subscription & Provisioning Engine]
        AdminRoute & CustomerRoute & ApiRoute --> LicEngine[License Validation & Activation Engine]
        AdminRoute & CustomerRoute & ApiRoute --> ProjEngine[Custom Dev Project Engine]
        AdminRoute & CustomerRoute & ApiRoute --> FinEngine[Finance & Accounting Engine]
        AdminRoute & CustomerRoute & ApiRoute --> AffEngine[Affiliate & Commission Engine]
    end
    
    subgraph Storage & External Services
        FinEngine -->|Webhook / API| Midtrans[Midtrans Payment Gateway]
        SubEngine & LicEngine -->|Queue Job| Database[(MySQL Database: Jobs, Cache, Sessions)]
        Database -->|Cron Scheduler| LaravelCron[php artisan schedule:run]
        LaravelApp -->|SMTP| EmailService[Email Server / SMTP]
        LaravelApp -->|Node.js / Fonnte| WAService[WhatsApp Server Gateway]
    end
```

---

## 2. Diagram Workflow Utama (Core Business Workflows)

### 2.1 Workflow 1: Registrasi, Pembelian SaaS, & Auto Provisioning

```mermaid
sequenceDiagram
    autonumber
    actor Customer
    participant PublicWeb as Public Landing Page
    participant Checkout as Billing & Checkout Engine
    participant Midtrans as Midtrans Gateway
    participant SubJob as Provisioning Job Runner
    participant Tenant as Tenant Instance Manager

    Customer->>PublicWeb: Pilih Paket Subskripsi (SaaS Plan)
    PublicWeb->>Checkout: Buat Pesanan (Draft Subscription & Invoice)
    Checkout->>Midtrans: Request Snap Token Pembayaran
    Midtrans-->>Customer: Tampilkan Pop-up Pembayaran
    Customer->>Midtrans: Melakukan Pembayaran (QRIS / Bank Transfer / E-Wallet)
    Midtrans->>Checkout: Webhook Notification (Payment Success)
    Checkout->>Checkout: Update Status Invoice = PAID & Subscription = ACTIVE
    Checkout->>SubJob: Trigger ProvisioningJob (Database Queue)
    SubJob->>Tenant: Eksekusi Step (Create Tenant DB, Run Migration, Setup Subdomain)
    SubJob->>Customer: Kirim Email & WA Notifikasi Akses Tenant
```

---

### 2.2 Workflow 2: Validasi Lisensi Software (Domain & IP Binding)

```mermaid
flowchart TD
    Start([Client App / Self-Hosted Client]) --> SendReq[Kirim API Request Aktivasi / Check License]
    SendReq --> CheckExist{Apakah License Key Terdaftar?}
    
    CheckExist -- Tidak --> RetErr1[Return 404: License Key Tidak Valid]
    CheckExist -- Ya --> CheckStatus{Status License = ACTIVE?}
    
    CheckStatus -- Tidak --> RetErr2[Return 403: Lisensi Expired / Suspended / Revoked]
    CheckStatus -- Ya --> CheckIPBlacklist{Domain / IP di Blacklist?}
    
    CheckIPBlacklist -- Ya --> RetErr3[Return 403: Client IP / Domain Terblokir]
    CheckIPBlacklist -- Tidak --> CheckDomainLimit{Activations Count < Domain Limit?}
    
    CheckDomainLimit -- Tidak --> RetErr4[Return 400: Batas Maksimum Domain Terlampaui]
    CheckDomainLimit -- Ya --> RecordLog[Catat ke LicenseActivation & LicenseLog]
    
    RecordLog --> IssueJWT[Generate Token Akses Lisensi Terenkripsi]
    IssueJWT --> Success([Return 200: Lisensi Valid & Aktif])
```

---

### 2.3 Workflow 3: Custom Development Project Lifecycle

```mermaid
stateDiagram-v2
    [*] --> LeadSubmitted: Client Kirim ErpRequest / Inquiry
    LeadSubmitted --> Qualification: Admin / Sales Kualifikasi Scope & Budget
    Qualification --> QuotationDrafted: Admin Buat Quotation (Penawaran Harga)
    QuotationDrafted --> QuotationApproved: Client Setujui Quotation
    QuotationApproved --> ContractSigned: Tanda Tangan Kontrak & Milestones Set
    
    state ProjectExecution {
        [*] --> MilestoneDP: Invoice Down Payment (DP) Issued
        MilestoneDP --> DPPaid: DP Lunas via Midtrans
        DPPaid --> Development: Tim Dev Kerjakan Sprint / Task
        Development --> UAT: Pengujian UAT oleh Client
        UAT --> MilestoneFinal: Invoice Termin Akhir Issued
        MilestoneFinal --> FinalPaid: Pelunasan Lunas
    }

    ContractSigned --> ProjectExecution
    ProjectExecution --> WarrantyPeriod: Serah Terima & Garansi
    WarrantyPeriod --> MaintenanceContract: Opsi Perpanjangan Maintenance SLA
    MaintenanceContract --> [*]
```

---

### 2.4 Workflow 4: Kalkulasi Komisi Afiliasi & Payout Engine

```mermaid
flowchart LR
    RefLink[Referral Link Klik oleh Lead] --> SaveCookie[Simpan Cookie Ref Code]
    SaveCookie --> UserRegister[User mendaftar Customer Account]
    UserRegister --> LinkAff[Relasikan Customer ID ke Affiliator ID]
    
    UserRegister --> UserPay[Customer Bayar Invoice SaaS / Product]
    UserPay --> CheckAffRule{Hitung Aturan Komisi}
    
    CheckAffRule --> CalcComm[Bagi Hasil: Total Paid * Commission Rate %]
    CalcComm --> CreateRecord[Buat Record AffiliateCommission - Status: PENDING]
    
    CreateRecord --> HoldingPeriod{Masa Holding Matang? e.g. 7 Hari}
    HoldingPeriod -- Ya --> CreditWallet[Pindahkan Status ke APPROVED & Tambah Saldo AffiliateWallet]
    
    CreditWallet --> RequestWD[Afiliator Minta Penarikan AffiliateWithdrawal]
    RequestWD --> AdminApprove[Admin Verifikasi & Process Payout]
    AdminApprove --> WDComplete[Saldo Didebit & Notifikasi Transfer Terkirim]
```

---

## 3. Spesifikasi Service Layer & Interface (Clean Architecture)

### 3.1 Interface Pengecekan Batas Kuota (Quota & Limit Enforcer)
Terletak pada layer pemrosesan batas penggunaan multi-tenant:

```php
namespace App\Shared\Contracts;

interface QuotaEnforcerInterface {
    /**
     * Memeriksa apakah tenant masih memiliki kuota untuk transaksi tertentu.
     */
    public function checkLimit(int $tenantId, string $metricName, int $requestedAmount = 1): bool;

    /**
     * Mencatat pemakaian kuota secara real-time.
     */
    public function recordUsage(int $tenantId, string $metricName, int $amountUsed = 1): void;
}
```

### 3.2 Service Auto-Reconciliation Keuangan
Mengubah setiap transaksi lunas menjadi jurnal akuntansi (*General Ledger*):

```php
namespace App\Services;

use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Payment;

class FinancialReconciliationService {
    public function reconcilePaidInvoice(Invoice $invoice, Payment $payment): JournalEntry
    {
        // 1. Buat Journal Entry Header
        $entry = JournalEntry::create([
            'entry_number' => 'JE-' . date('Ymd') . '-' . sprintf('%04d', $invoice->id),
            'date' => now(),
            'description' => 'Pembayaran Invoice #' . $invoice->invoice_number,
            'reference_type' => Invoice::class,
            'reference_id' => $invoice->id,
        ]);

        // 2. Debet: Kas / Bank (Sesuai Channel Midtrans / Manual)
        $entry->items()->create([
            'chart_of_account_id' => $payment->bank_account_id,
            'type' => 'debit',
            'amount' => $payment->amount,
        ]);

        // 3. Kredit: Pendapatan Produk / SaaS / Services
        $entry->items()->create([
            'chart_of_account_id' => $invoice->revenue_account_id,
            'type' => 'credit',
            'amount' => $invoice->subtotal,
        ]);

        return $entry;
    }
}
```

---

## 4. Matriks Aksesibilitas Role & Permisi (RBAC Matrix)

| Modul / Operasi | Super Admin | Customer / User | Affiliator | Public / Guest |
| :--- | :---: | :---: | :---: | :---: |
| **Catalog & Products** | Full Access (CRUD) | View Only | View Only | View Only |
| **Subskripsi & SaaS** | Manage All Tenants | Own Subscription | - | Request Trial |
| **License Management** | Issue / Revoke / Block | View Own Keys | - | API Verify Only |
| **Custom Projects** | Full CRM & Quotation | View Own Project & Pay | - | Submit Inquiry |
| **Affiliate Dashboard**| Approve Payouts | - | View Balance & WD | Register Ref |
| **Financial & Journal**| Full Ledger Access | View Own Invoices | - | - |
| **Helpdesk Tickets** | Reply / Close All | Create / Reply Own | - | - |

---

## 5. Panduan Pemeliharaan & Operasional (DevOps / Shared Hosting)

### 5.1 Perintah Operasional Rutin (Artisan Commands)

```bash
# 1. Bersihkan Seluruh Cache Aplikasi
php artisan optimize:clear

# 2. Re-cache Konfigurasi dan Routing (Wajib setelah update .env atau routes)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Jalankan Pengujian Otomatis (Unit & Feature Tests)
php artisan test

# 4. Pengolahan Antrean Job (Driver Database di Shared Hosting)
php artisan queue:work --stop-when-empty --max-time=50 --tries=3
```

### 5.2 Konfigurasi Cron Job (cPanel / Shared Hosting)

Tambahkan perintah berikut pada cPanel Cron Jobs agar berjalan **setiap 1 menit**:

```bash
* * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. Penutup & Garis Besar Pengembangan
Dokumentasi teknis ini menjadi acuan integrasi antar-modul di COOCA.ID. Dengan pemisahan layer yang jelas, arsitektur database yang mencakup 70 model, serta dukungan antrean berbasis database, sistem dapat beroperasi secara handal di lingkungan *shared hosting* maupun berskala besar di VPS/Cloud.
