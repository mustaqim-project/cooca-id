# Panduan Implementasi Teknis & Spesifikasi Database

**Sistem:** `cooca-id` Enterprise Platform  
**Fokus:** Implementasi Fase 1 & 2 (Product, Pricing, Custom Development)

Dokumen ini merupakan penjabaran teknis dari _Enterprise Business Logic Audit_. Berisi spesifikasi tabel database (migrations) dan arsitektur service layer yang harus diimplementasikan oleh tim developer.

---

## 1. Spesifikasi Database (Migration Schema)

### A. Modul Product & Pricing (Phase 1)

**Tabel `product_types` (Master Data)**

- `id` (PK)
- `name` (SaaS, Lifetime, Add-on, Bundle, Custom Service)
- `slug`

**Tabel `product_variants`**

- `id` (PK)
- `product_id` (FK -> products)
- `name` (e.g., Basic Cloud, Enterprise Self-Hosted)
- `sku` (Unique)
- `is_active` (Boolean)

**Tabel `product_dependencies`**

- `id` (PK)
- `product_id` (FK) -> Produk utama yang dibeli (Add-on)
- `depends_on_product_id` (FK) -> Produk prasyarat yang harus dimiliki

**Tabel `price_books` (Pricing Tiers / Tipe Customer)**

- `id` (PK)
- `name` (e.g., IDR Standard, USD Enterprise)
- `currency_code` (e.g., IDR, USD)

**Tabel `pricing_rules` (Dynamic Pricing per Variant)**

- `id` (PK)
- `product_variant_id` (FK)
- `price_book_id` (FK)
- `billing_cycle` (one_time, monthly, annually, usage_based)
- `price` (Decimal)
- `usage_metric` (String, nullable - e.g., 'per_api_call', 'per_gb')

---

### B. Modul Custom Development & Project (Phase 2)

**Tabel `leads` (Pengganti ErpRequest)**

- `id` (PK)
- `customer_id` (FK - Nullable)
- `company_name`
- `project_type` (Web, Mobile, ERP, dll)
- `estimated_budget`
- `status` (New, Contacted, Meeting Scheduled, Qualified, Lost)

**Tabel `quotations`**

- `id` (PK)
- `lead_id` (FK)
- `quotation_number` (Unique)
- `total_amount` (Decimal)
- `valid_until` (Date)
- `status` (Draft, Sent, Approved, Rejected)

**Tabel `projects`**

- `id` (PK)
- `quotation_id` (FK)
- `customer_id` (FK)
- `name`
- `status` (Initiated, Active, UAT, Warranty, Completed)
- `start_date`
- `end_date`

**Tabel `project_milestones` (Terhubung ke Invoice)**

- `id` (PK)
- `project_id` (FK)
- `name` (e.g., Down Payment 30%, Termin 1 40%)
- `percentage` (Int)
- `amount` (Decimal)
- `status` (Pending, Invoiced, Paid)
- `due_date`

---

## 2. Struktur Interface & Service Layer

Untuk menjaga Clean Architecture, buat interface di folder `app/Shared/Contracts/`.

### Quota & Limit Enforcer

```php
namespace App\Shared\Contracts;

interface QuotaEnforcerInterface {
    public function checkLimit(int $tenantId, string $metricName, int $requestedAmount): bool;
    public function recordUsage(int $tenantId, string $metricName, int $amountUsed): void;
}
```

### Invoice Generator Engine

```php
namespace App\Modules\Financial\Services;

class InvoiceGenerationService {
    public function generateFromMilestone(ProjectMilestone $milestone): Invoice
    {
        // 1. Generate Invoice Record
        // 2. Add Invoice Items (PPN, Service fee)
        // 3. Dispatch Event: InvoiceCreated
    }

    public function generateFromSubscription(Subscription $subscription): Invoice
    {
        // 1. Calculate Proration if any
        // 2. Apply Affiliate Discounts
        // 3. Generate Recurring Invoice
    }
}
```

### API License Validator (Web-based focus)

```php
namespace App\Modules\Licensing\Services;

class DomainActivationService {
    public function verifyAndActivate(string $licenseKey, string $domain, string $serverIp): array
    {
        $license = License::where('key', $licenseKey)->firstOrFail();

        // Cek Blacklist
        if ($this->isBlacklisted($domain, $serverIp)) {
            throw new LicenseBlockedException();
        }

        // Cek Limit Aktivasi Domain
        $activeCount = $license->activations()->count();
        if ($activeCount >= $license->domain_limit) {
            throw new DomainLimitExceededException();
        }

        // Catat Aktivasi
        $license->activations()->create([
            'domain' => $domain,
            'server_ip' => $serverIp,
            'activated_at' => now()
        ]);

        return [
            'status' => 'success',
            'token' => $this->generateJwtToken($license, $domain)
        ];
    }
}
```

---

## 3. Langkah Pertama Pengerjaan (Next Actions)

Bagi tim Backend Developer, jalankan urutan ini:

1. **Setup Core Entities (Week 1):**
    - Buat Migration & Model untuk `ProductType`, `ProductVariant`, `ProductDependency`.
    - Update relasi model `Product` eksisting.
2. **Setup Custom Dev CRM (Week 2):**
    - Buat Migration & Model untuk `Lead`, `Quotation`, `QuotationItem`, `Project`, dan `ProjectMilestone`.
    - Buat Controller CRUD di Admin Panel (`LeadController`, `ProjectController`).

3. **Integrasi Billing (Week 3):**
    - Buat `InvoiceGenerationService` untuk menghubungkan `ProjectMilestone` yang disetujui agar otomatis membuat `Invoice`.
    - Pastikan webhook Midtrans mengupdate status `ProjectMilestone` menjadi "Paid" ketika Invoice lunas.
