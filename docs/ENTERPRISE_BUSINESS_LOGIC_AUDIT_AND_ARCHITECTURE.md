# Mega Audit & Architecture Blueprint: Enterprise Single Business Management Platform (cooca-id)

**Document Version:** 1.0.0  
**Role / Persona:** Enterprise Business Analyst, Product Manager, Solution Architect, and ERP Consultant  
**Target System:** `cooca-id` (Single Business Management Platform)  
**Scope:** Core Business Logic Audit, Gap Analysis (Missing Logic/Entities/Workflows/Financials), Domain Modeling, Modular Laravel Architecture, and End-to-End Transaction Flows.

---

## Executive Summary

Saat ini, sistem `cooca-id` telah memiliki fondasi awal untuk model bisnis B2B/B2C Digital Product & SaaS melalui entitas seperti `Product`, `Subscription`, `SubscriptionPlan`, `License`, `AffiliateCommission`, `ErpRequest`, `Invoice`, `Transaction`, dan `MidtransTransaction`. Namun, hasil **Business Logic Audit** menunjukkan bahwa arsitektur eksisting masih bersifat _fragmented_ dan belum mampu mengelola 10 model bisnis inti secara terpadu dalam **Satu Admin Panel (Single Business Management Platform)**.

Secara khusus, terdapat kelemahan signifikan pada:

1. **Modul Custom Development & Project-Based Development:** Belum memiliki arsitektur _Project Management_ (Lead/CRM → Requirement Gathering → Quotation → Contract → Milestone/Sprint/Task → Invoicing → UAT → Warranty → Maintenance Contract). Entitas eksisting (`ErpRequest`) hanya sekadar _lead/inquiry form_ statis tanpa _project engine_.
2. **Pricing & Product Management:** Belum mendukung arsitektur multi-dimensi seperti _Product Variant_, _Product Version/Release/Roadmap_, _Product Bundle & Dependency_, serta skema harga kompleks (Usage-Based Pricing, Volume Discount, Enterprise Negotiation, Multi-Currency).
3. **Subscription & License Lifecycle:** Kurangnya _state machine_ yang ketat untuk transisi siklus langganan (Proration, Grace Period, Pause/Resume, Auto vs Manual Renewal) dan _domain-based licensing limits_ yang terintegrasi dengan pemotongan kuota secara _real-time_.
4. **Financial & Accounting Integration:** Belum ada mekanisme Rekonsiliasi Pembayaran terpusat (_Credit Note_, _Debit Note_, _Installment/Milestone Payment_, _Over/Partial Payment_, dan pencatatan Jurnal/General Ledger B2B).
5. **Affiliate Commission Rule Engine:** Belum mendukung komisi berjenjang (_Tier/Multi-Level_), komisi berulang (_Recurring Renewal Commission_), maupun komisi dari jasa _Custom Development / Project Milestone_.

Dokumen ini menyajikan **Audit Lengkap, Identifikasi Missing Logic, Rekomendasi Domain Model (ERD), Struktur Modul Laravel Enterprise (Modular DDD), dan End-to-End Workflow** untuk merombak `cooca-id` menjadi platform enterprise berskala besar yang _scalable_, _extensible_, dan _multi-tenant ready_.

---

## Bagian 1: Audit Main Core Business & Existing State Assessment

### 1. Evaluasi Entitas Eksisting vs 10 Model Bisnis Perusahaan

| #   | Model Bisnis Core                  | Status Eksisting (`cooca-id`) | Gap Analysis & Keterbatasan Eksisting                                                                                                                                                                  |
| --- | ---------------------------------- | ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 1   | **Penjualan Software (SaaS)**      | 🟡 Terbatas                   | Didukung oleh `Product` + `SubscriptionPlan` + `Subscription` + `ProvisioningJob`. Namun kekurangan multi-tenant tier limit enforcement yang real-time dan isolasi resource.                           |
| 2   | **Penjualan Software Lifetime**    | 🟡 Terbatas                   | Hanya mengandalkan `Product` tipe standar/statis tanpa pemisahan hak akses _Core Software Lifetime_ vs _Support/Update Entitlement_ (yang biasanya hanya 1 tahun dan harus diperpanjang).              |
| 3   | **Penjualan Lisensi (Licensing)**  | 🟡 Terbatas                   | Ada `License`, `LicenseActivation`, `LicenseLog`. Namun kurang _Audit Enforcement_ untuk Domain Limit, Server IP Binding, dan Blacklist Engine.                                                        |
| 4   | **Subscription / Berlangganan**    | 🟡 Terbatas                   | Terdapat `Subscription` dan `SubscriptionPlan`. Belum ada Proration Engine saat upgrade/downgrade pertengahan siklus, Grace Period Enforcement, Pause/Resume, atau Invoice Reminders.                  |
| 5   | **Penjualan Add-on**               | 🔴 Missing                    | Tidak ada struktur _Parent-Child Product Relationship_ atau _Compatible Add-ons_. Add-on tidak dapat dikaitkan dengan kuota tambahan pada Subscription yang sedang berjalan.                           |
| 6   | **Penjualan Product Bundle**       | 🔴 Missing                    | Tidak ada entitas `ProductBundle` atau `ProductBundleItem`. Penjualan produk tidak bisa dikemas dalam 1 harga diskon dengan propagasi lisensi/langganan modular sekaligus.                             |
| 7   | **Custom Development (Services)**  | 🔴 Missing                    | Hanya ada `ErpRequest` (formulir survei/konsultasi awal). Tidak ada kalkulasi _Custom Pricing_ (Fixed vs Hourly), _Scope Change Management_, maupun integrasi ke CRM/Quotation/Milestone Invoice.      |
| 8   | **Maintenance & Support Contract** | 🔴 Missing                    | Tidak ada entitas SLA Contract, Renewal Maintenance Tahunan, atau sistem kuota tiket support premium pasca-go-live software custom/SaaS.                                                               |
| 9   | **Affiliate & Referral Program**   | 🟡 Terbatas                   | Ada `Affiliator`, `AffiliateCommission`, `AffiliateWithdrawal`, dan `AffiliateWallet`. Belum mendukung Tiered Rate, Multi-level Referral, Recurring Renewal Commission, dan Custom Project Commission. |
| 10  | **Project Based Development**      | 🔴 Missing                    | Tidak ada _Project Management Engine_ (`Project`, `Sprint`, `Task`, `Milestone`, `Timesheet`, `UAT`, `ContractApproval`).                                                                              |

---

## Bagian 2: Audit Per-Modul & Identifikasi Missing Logic (1-10)

### 1. Product Management Audit & Missing Logic

- **Existing State:** Tabel `products` menyimpan nama, deskripsi, harga statis, dan `product_categories_id`. Tabel `product_features` menyimpan daftar fitur sederhana.
- **Missing Database Entities:**
    - `product_types` (SaaS, Lifetime Software, Add-on, License Only, Service, Bundle, Template).
    - `product_variants` (SKU unik per varian spek, misal: Self-Hosted vs Cloud, or Windows vs Mac vs Linux).
    - `product_bundles` & `product_bundle_items` (Relasi M:N antar produk yang dikemas).
    - `product_versions` & `product_releases` (Changelog, file binary path, checksum MD5/SHA256, tanggal rilis, status deprecation).
    - `product_roadmaps` & `product_requirements` (Status: Planned, In Progress, Released, Backlog, Voting Score dari customer).
    - `product_modules` (Sub-komponen modular yang bisa diaktifkan/non-aktifkan per lisensi/plan, misal: Modul POS, Modul HRIS, Modul Accounting).
    - `product_documentation` (Panduan integrasi, API doc, User Manual terikat ke produk).
    - `product_dependencies` (Validasi prasyarat: Modul B hanya bisa dibeli jika customer sudah memiliki Modul A atau Core SaaS v2.0+).
- **Missing Business Rules:**
    - **Dependency Validation Rule:** Saat checkout, sistem wajib memvalidasi apakah produk yang ada di keranjang memenuhi `product_dependencies`. Jika membeli _Add-on WhatsApp Notifier_, customer harus sudah memiliki _Core CRM Product_ aktif.
    - **Bundle Price Split Engine:** Saat `ProductBundle` dibeli, sistem harus mengalokasikan persentase pendapatan ke masing-masing item produk untuk akurasi laporan _Revenue Recognition_ per divisi/produk.

---

### 2. Pricing Management Audit & Missing Logic

- **Existing State:** Harga hanya berupa kolom `price` di `products` dan `price` di `subscription_plans`. Voucher/diskon ditangani terpisah oleh `Voucher`.
- **Missing Database Entities & Price Matrix:**
    - `price_books` / `pricing_tiers` (Sistem harga multi-koleksi berdasarkan tipe customer, currency, atau negara).
    - `usage_pricing_rules` (Kalkulasi harga berbasis pemakaian: `per_api_call`, `per_gb_storage`, `per_active_user`, `per_transaction`).
    - `enterprise_quotes` & `custom_negotiations` (Pengajuan penawaran harga khusus dari customer enterprise, approval level management, expiry date quote, PDF quotation generator).
    - `volume_discount_tiers` (Diskon otomatis berdasarkan volume pembelian unit/lisensi, misal: 1-10 lisensi @$100, 11-50 @$85, 51+ @$70).
    - `promotional_campaigns` (Flash sale timer, seasonal discount, referral stacking discount).
- **Missing Business Rules:**
    - **Dynamic Usage Billing:** Sistem cron/job harus mengakumulasi data dari `ApiIntegration` atau `Tenant` metric setiap malam, lalu membuat _Invoice Line Item_ otomatis di akhir periode tagihan.
    - **Enterprise Override Rule:** Jika customer memiliki `enterprise_quote` yang statusnya `APPROVED`, harga di checkout tidak mengambil dari tabel `products`, melainkan dari harga yang disepakati di dalam penawaran negosiasi.

---

### 3. Subscription Management Audit & Missing Logic

- **Existing State:** Tabel `subscriptions` memiliki `start_date`, `end_date`, `status` (`active`, `expired`, `cancelled`), dan `subscription_plan_id`.
- **Missing State Machine & Lifecycle Transitions:**
    - **States:** `TRIAL` → `ACTIVE` → `PAST_DUE` → `GRACE_PERIOD` → `SUSPENDED` → `EXPIRED` / `CANCELLED`.
- **Missing Database Entities:**
    - `subscription_items` (Untuk menangani multi-plan/add-ons dalam 1 ID subscription utama).
    - `subscription_prorations` (Pencatatan selisih tagihan/kredit saat user melakukan Upgrade atau Downgrade plan di pertengahan siklus).
    - `subscription_pauses` (Catatan tanggal mulai & selesai penundaan sementara atas permintaan customer tanpa kehilangan masa aktif).
    - `subscription_billing_schedules` (Jadwal tagihan bulanan, kuartalan, semi-annual, annual, atau multi-year).
- **Missing Business Rules & Automation:**
    - **Proration Calculator:** `(Days Remaining / Total Days in Cycle) × (New Plan Price - Old Plan Price)`. Jika hasil positif, buat invoice baru; jika negatif, masukkan ke `CustomerBalance` / `CreditNote`.
    - **Grace Period Engine:** Jika pembayaran gagal saat jatuh tempo (`end_date`), status berubah menjadi `GRACE_PERIOD` selama $N$ hari (misal 7 hari). Layanan SaaS tetap berjalan dengan banner peringatan di client. Pasca $N$ hari tanpa pembayaran, otomatis berubah menjadi `SUSPENDED` (akses API dan dashboard client diblokir).

---

### 4. License Management Audit & Missing Logic

- **Existing State:** Tabel `licenses` menyimpan `license_key`, `type`, `status`. Ada `license_activations` dan `license_logs`.
- **Missing Advanced Licensing Capabilities:**
    - `license_domain_limits`, `license_user_limits`.
    - `license_blacklists` (Daftar license key atau Server IP/Domain yang diblokir akibat refund/chargeback/pelanggaran).
    - `license_transfers` (Log perpindahan kepemilikan atau pergantian domain server yang telah disetujui melalui `license_appeals`).
- **Missing Business Rules:**
    - **Domain & IP Enforcement Check:** Middleware API `verify-license` harus mencocokkan `domain`/`server_ip` dengan `license_activations`. Jika `count(active_domains) >= max_allowed_domains`, request aktivasi ditolak dengan HTTP 403.
    - **Automatic Revocation on Refund:** Jika transaksi pembayaran/invoice diubah menjadi `REFUNDED` atau `CHARGEBACK`, `License` terkait otomatis diubah statusnya menjadi `REVOKED` dan ditambahkan ke `license_blacklists`.

---

### 5. Payment Management Audit & Missing Logic

- **Existing State:** Ada `PaymentService`, `MidtransTransaction`, `Transaction`, dan `Invoice`.
- **Missing Enterprise Accounting & Reconciliation Features:**
    - `payment_methods` (Pengaturan channel: Midtrans, Manual Bank Transfer, VA, QRIS, Credit Card, E-Wallet, Over-the-Counter).
    - `payment_installments` / `project_payment_schedules` (Rencana cicilan pembayaran/DP/Termin 1/Termin 2 untuk project custom development atau pembelian enterprise value tinggi).
    - `credit_notes` & `debit_notes` (Dokumen koreksi fiskal resmi atas kelebihan bayar, pembatalan sebagian, atau refund agar sesuai standar akuntansi perpajakan).
    - `payment_reconciliations` (Proses pencocokan mutasi bank harian dengan tagihan yang terbayar).
    - `tax_rates` & `invoice_taxes` (Dukungan PPN 11%, PPh 23, withholding tax, dan kalkulasi otomatis berdasarkan lokasi customer atau NPWP perusahaan).
- **Missing Business Rules:**
    - **Installment Threshold Enforcement:** Sistem tidak boleh mengizinkan pengiriman file final software custom atau pembukaan akses lisensi _Production_ sebelum akumulasi `Payment` pada `Invoice` mencapai 100% dari kesepakatan kontrak.
    - **Idempotent Webhook Processing:** Webhook notification dari Midtrans harus memvalidasi `signature_key` (SHA512 dari `order_id + status_code + gross_amount + server_key`). Jika valid, gunakan `DB::transaction()` dengan _row-level locking_ (`lockForUpdate()`) pada tabel `transactions` dan `invoices` untuk mencegah _race condition_ double-activation.

---

### 6. Product Plan & Limit Enforcement Audit

- **Existing State:** `SubscriptionPlan` memiliki batasan sederhana: `max_users`, `storage_limit_mb`, `is_unlimited_users`.
- **Missing Limit Entities & Rules:**
    - `plan_limit_definitions` (Master limit parameter: `API_DAILY_LIMIT`, `BRANCH_LIMIT`, `MAX_PROJECTS`, `SUPPORT_SLA_HOURS`, `CUSTOM_DOMAIN_ALLOWED`).
    - `plan_limit_allocations` (Nilai alokasi spesifik per plan).
    - `tenant_resource_usages` (Pencatatan konsumsi real-time per Tenant/Customer).
    - `support_sla_tiers` (Definisi level support: Free = Best Effort 72j, Basic = 24j, Professional = 8j, Enterprise = Dedicated TAM 2j).
- **Missing Business Rules:**
    - **Real-Time Limit Enforcer:** Service `QuotaEnforcementService` dicek pada setiap operasi kritis (misal saat menambah cabang/user di tenant). Jika `current_usage >= limit_allocation`, lemparkan `QuotaExceededException` dan arahkan customer ke workflow Upgrade Plan.

---

### 7. Product Category & Catalog Architecture Audit

- **Existing State:** `ProductCategory` hanya berupa taksonomi 1 level (nama, slug, icon).
- **Missing Capabilities:**
    - Taksonomi berjenjang (`parent_id` untuk sub-kategori: misal _Software_ → _CRM_ → _Enterprise CRM_).
    - `category_attributes` (Spesifikasi dinamis per kategori, misal Kategori _API_ butuh field `Rate Limit per Second` & `Swagger URL`; Kategori _Theme_ butuh field `Supported CMS & Version`).

---

### 8. Custom Development & Project-Based Business Audit (Major Gap)

- **Existing State:** Hanya terdapat `ErpRequest` (`company_name`, `needs_description`, `budget_range`, `status`). Tidak ada alur bisnis untuk menangani fase eksekusi project pasca-konsultasi.
- **Missing Complete Domain Architecture for Custom Dev:**
    - **CRM & Pre-Sales Phase:** `leads`, `client_meetings`, `requirement_gatherings`, `quotations`, `quotation_items`, `contracts`.
    - **Project Management Phase:** `projects`, `project_sprints`, `project_tasks`, `project_milestones`, `timesheets`, `project_members`.
    - **Financial & Milestone Billing Phase:** `project_billing_schedules` (DP 30%, UAT 50%, Go-Live 20%), `scope_change_requests` (CR/Change Request yang berdampak pada penambahan biaya/waktu), `revision_logs`.
    - **Post-Project Phase:** `user_acceptance_tests` (UAT Sign-off digital), `project_warranties` (Masa garansi bug-fix pasca deploy), `maintenance_contracts` (SLA bulanan/tahunan pasca garansi).
- **Workflow Custom Development End-to-End:**
    1. **Lead Generation:** Inquiry dari formulir website (`leads` / `ErpRequest`) masuk ke pipeline admin.
    2. **Requirement Gathering & Meeting:** Solution Architect mencatat dokumen kebutuhan (`requirement_gatherings`) dan jadwal meeting (`client_meetings`).
    3. **Quotation & Negotiation:** Admin membuat `Quotation` berisi rincian scope, estimasi jam/durasi, item breakdown (UI/UX, Backend, QA, Infra), dan skema termin pembayaran (Milestone-based).
    4. **Approval & Contract Sign-off:** Customer menyetujui Quotation → otomatis di-generate menjadi `Contract`. Kedua pihak melakukan tanda tangan digital/klausul hukum.
    5. **Down Payment (DP) Invoice:** Sistem menerbitkan `Invoice` DP (misal 30%). Setelah `Payment` lunas, status `Contract` berubah `ACTIVE` dan `Project` resmi di-create.
    6. **Project Execution (Iterative/Sprint):** Project Manager membuat `Sprint`, alokasi `Task` ke developer/tester, dan memonitor _burn-down/timeline_. Developer menginput `Timesheet`.
    7. **Milestone Completion & Progressive Billing:** Saat `Milestone` tercapai (misal: _Sprint 1-3 Completed - Core Module_), admin memvalidasi progress dan sistem otomatis menerbitkan `Invoice` Termin 2.
    8. **Revision & Scope Change (CR):** Jika customer meminta fitur di luar scope awal, admin membuat `ScopeChangeRequest` (`scope_change_requests`). Jika disetujui, CR ini menambahkan biaya pada invoice termin berikutnya dan menggeser timeline project.
    9. **UAT Sign-off & Go-Live:** Dokumen `UserAcceptanceTest` ditandatangani digital oleh customer setelah testing selesai.
    10. **Final Invoice & Deployment:** Setelah UAT sign-off, invoice pelunasan (Termin Akhir) diterbitkan. Setelah lunas, tim dev melakukan deployment ke server production customer.
    11. **Warranty Period:** Project beralih ke status `WARRANTY` (misal 3 bulan free bug-fix). Customer dapat membuka `Ticket` support garansi tanpa biaya.
    12. **Maintenance & Support Contract Transition:** 30 hari sebelum garansi berakhir, sistem otomatis menghasilkan penawaran `MaintenanceContract` (Tahunan/Bulanan) untuk pemeliharaan server, keamanan, dan minor update.

---

### 9. Affiliate Commission & Referral Rule Engine Audit

- **Existing State:** `Affiliator` terhubung dengan `AffiliateCommission` berdasarkan `Transaction` tunggal (`amount`, `status: pending/approved/paid`).
- **Missing Advanced Affiliate Capabilities:**
    - `affiliate_commission_rules` (Rule engine fleksibel yang mengatur komisi berdasarkan: Kategori Produk, Tipe Transaksi [New Purchase vs Renewal vs Add-on vs Custom Dev Project], dan Skema Tipe [Flat vs Percentage]).
    - `affiliate_tiers` (Bronze, Silver, Gold, Platinum — berdasarkan akumulasi penjualan/referral dengan persentase komisi yang meningkat).
    - `affiliate_mlm_network` / `referral_hierarchy` (Komisi multi-level: Level 1 = 20%, Level 2 = 5%, Level 3 = 2% untuk mitra agensi/reseller besar).
    - `affiliate_campaigns` & `custom_referral_links` (Pelacakan conversion rate per link kampanye/landing page khusus).
- **Missing Business Rules:**
    - **Recurring Subscription Commission:** Jika customer yang direferensikan memperpanjang langganan (Renewal Subscription), affiliator tetap berhak menerima komisi perpanjangan (bisa berdurasi lifetime atau dibatasi $N$ tahun sesuai `commission_rules.recurring_duration`).
    - **Custom Project Referral Commission:** Jika affiliator membawa klien _Custom Development_ senilai Rp 500 Juta, komisi dicairkan secara bertahap seiring pembeli membayar termin milestone (misal: saat DP lunas, affiliator cair 30% dari total komisinya).

---

### 10. Holistic Missing Business Logic & Systemic Capabilities

- **Multi-Company / Multi-Tenant Isolation:** Perlunya pemisahan data tingkat database (`tenant_id` pada setiap tabel operasional) atau multi-database schema untuk melayani klien enterprise besar secara SaaS murni.
- **Multi-Currency & Multi-Tax Engine:** Tabel `currencies` dengan _exchange rate_ dinamis, serta integrasi pajak lokal (PPN/VAT, PPh, Withholding Tax) di dalam item invoice.
- **Approval Flow Workflow Engine:** Tabel `approval_workflows`, `approval_steps`, dan `approval_logs` untuk menangani persetujuan bertingkat (misal: Diskon di atas 20% wajib disetujui Sales Director; Quotation Custom Dev > Rp 100 Juta wajib disetujui Solution Architect Head & CFO).
- **General Ledger & Accounting Integration Pipeline:** Setiap _Financial Event_ (Payment Lunas, Refund, Invoice Issued, Commission Paid) memicu `Event` Laravel yang dicatat ke tabel `accounting_journal_entries` (Debit/Kredit Cash, Account Receivable, Deferred Revenue, Affiliate Payable).

---

## Bagian 3: Rekomendasi Desain Domain Model & ERD (Comprehensive Database Schema)

Berikut adalah **Entity-Relationship Model (ERD)** komprehensif yang dirancang dalam bentuk spesifikasi skema relasional terpadu (Satu Database Platform Enterprise) yang mencakup 10 Core Business Model.

```
[CUSTOMER / CLIENT DOMAIN]
  Customer (1) ───< (M) CustomerContact
  Customer (1) ───< (M) Tenant (SaaS Workspace Isolation)
  Customer (1) ───< (M) EnterpriseQuote

[CATALOG & PRODUCT DOMAIN]
  ProductCategory (1) ───< (M) ProductCategory (Self-Reference Subcategories)
  ProductCategory (1) ───< (M) Product
  Product (1) ───< (M) ProductVariant (SKU, Spec, Self-Hosted/Cloud)
  Product (1) ───< (M) ProductVersion (Release Notes, Checksum, File Path)
  Product (1) ───< (M) ProductFeature
  Product (1) ───< (M) ProductModule (Optional/Add-on Modules)
  Product (1) ───< (M) ProductDependency (Requires Product B before Product A)
  ProductBundle (1) ───< (M) ProductBundleItem >─── (1) Product / ProductVariant

[PRICING & PROMOTION DOMAIN]
  Product (1) ───< (M) PriceTier (Currency, Billing Frequency, Min/Max Units)
  Product (1) ───< (M) UsagePricingRule (Per API call, Per GB, Per Transaction)
  PromotionalCampaign (1) ───< (M) Voucher >───< (M) VoucherUsage

[SUBSCRIPTION & LICENSING DOMAIN]
  SubscriptionPlan (1) ───< (M) PlanLimitAllocation >─── (1) PlanLimitDefinition
  Customer (1) ───< (M) Subscription (State: Trial, Active, Grace_Period, Suspended, Expired)
  Subscription (1) ───< (M) SubscriptionItem >─── (1) ProductVariant / SubscriptionPlan
  Subscription (1) ───< (M) SubscriptionProration (History of upgrades/downgrades proration)
  Subscription (1) ───< (M) TenantResourceUsage (Real-time consumption logs)

  ProductVariant (1) ───< (M) License (Key, Domain/User Limit)
  License (1) ───< (M) LicenseActivation (Server IP, Domain)
  License (1) ───< (M) LicenseLog / LicenseAppeal

[CUSTOM DEVELOPMENT & PROJECT DOMAIN (NEW CORE ENGINE)]
  Lead / ErpRequest (1) ───< (M) RequirementGathering
  Lead (1) ───< (M) ClientMeeting
  RequirementGathering (1) ───< (M) Quotation
  Quotation (1) ───< (M) QuotationItem (Milestone/Feature Breakdown)
  Quotation (1) ─── (1) Contract (Legal Sign-off, Fixed/Hourly Pricing, Terms)
  Contract (1) ─── (1) Project
  Project (1) ───< (M) ProjectMilestone (Linked to Invoice Schedule)
  Project (1) ───< (M) ProjectSprint
  ProjectSprint (1) ───< (M) ProjectTask >───< (M) Timesheet (Developer/Tester hours)
  Project (1) ───< (M) ScopeChangeRequest (CR Impact on cost & timeline)
  Project (1) ─── (1) UserAcceptanceTest (UAT Digital Sign-off)
  Project (1) ─── (1) ProjectWarranty (Bug-fix SLA period)
  ProjectWarranty (1) ─── (1) MaintenanceContract (SLA, Annual Support Renewal)

[FINANCIAL, INVOICING & PAYMENT DOMAIN]
  Customer (1) ───< (M) Invoice (Linked to Subscription / Order / ProjectMilestone)
  Invoice (1) ───< (M) InvoiceItem (PPN/Tax applied per item)
  Invoice (1) ───< (M) Payment (Midtrans, VA, CC, Manual Bank Transfer)
  Invoice (1) ───< (M) CreditNote / DebitNote
  Payment (1) ─── (1) Transaction (Payment Gateway Log & Reconciliation)

[AFFILIATE & PARTNER DOMAIN]
  Affiliator (1) ───< (M) AffiliateCampaign
  AffiliateTier (1) ───< (M) Affiliator
  AffiliateCommissionRule (1) ───< (M) AffiliateCommission
  Affiliator (1) ───< (M) AffiliateCommission (Linked to Invoice & Customer Purchase)
  Affiliator (1) ─── (1) AffiliateWallet ───< (M) AffiliateWithdrawal
```

---

## Bagian 4: Arsitektur Modular Laravel (Enterprise DDD & Clean Architecture)

To support this massive single management platform without turning into an unmaintainable monolith, the `cooca-id` codebase must follow **Modular Domain-Driven Design (DDD)** inside `app/Modules/` or dedicated domain folders.

### 1. Struktur Folder & Modul Enterprise

```
app/
├── Modules/
│   ├── ProductCatalog/
│   │   ├── Models/ (Product, ProductVariant, ProductBundle, ProductVersion, ProductModule)
│   │   ├── Repositories/ (ProductRepository, BundleRepository)
│   │   ├── Services/ (ProductCatalogService, DependencyValidationService)
│   │   ├── DTOs/ (ProductCreationDTO, BundleItemDTO)
│   │   └── Controllers/Admin/ProductManagementController.php
│   │
│   ├── Pricing/
│   │   ├── Models/ (PriceTier, UsagePricingRule, VolumeDiscountTier, EnterpriseQuote)
│   │   ├── Services/ (PricingEngineService, ProrationCalculatorService, TaxCalculatorService)
│   │   ├── Policies/ (EnterpriseQuotePolicy)
│   │   └── Events/ (QuoteApprovedEvent, DiscountRuleCreatedEvent)
│   │
│   ├── Subscription/
│   │   ├── Models/ (Subscription, SubscriptionItem, SubscriptionProration, TenantResourceUsage)
│   │   ├── StateMachines/ (SubscriptionStateMachine: Trial -> Active -> GracePeriod -> Suspended)
│   │   ├── Services/ (SubscriptionLifecycleService, RenewalSchedulerService, QuotaEnforcementService)
│   │   ├── Jobs/ (ProcessAutoRenewalJob, SendGracePeriodRemindersJob, SuspendExpiredSubscriptionsJob)
│   │   └── Events/ (SubscriptionCreated, SubscriptionUpgraded, GracePeriodEntered, SubscriptionSuspended)
│   │
│   ├── Licensing/
│   │   ├── Models/ (License, LicenseActivation, LicenseBlacklist)
│   │   ├── Services/ (LicenseManagerService, DomainActivationService, LicenseValidatorService)
│   │   └── Middleware/ (VerifyProductLicenseToken.php)
│   │
│   ├── CustomDevelopment/  <-- [CORE MISSING BUSINESS ENGINE]
│   │   ├── Models/ (Lead, RequirementGathering, Quotation, Contract, Project, Sprint, Task, Milestone, ScopeChangeRequest, UAT, Warranty, MaintenanceContract)
│   │   ├── Repositories/ (ProjectRepository, MilestoneBillingRepository)
│   │   ├── Services/ (ProjectManagementService, QuotationGeneratorService, ScopeChangeImpactService, MaintenanceRenewalService)
│   │   ├── Policies/ (ProjectMilestonePolicy, QuotationApprovalPolicy)
│   │   ├── Jobs/ (AlertApproachingMilestoneDueJob, TransitionWarrantyToMaintenanceJob)
│   │   └── Controllers/Admin/ProjectManagementController.php
│   │
│   ├── Financial/
│   │   ├── Models/ (Invoice, InvoiceItem, Payment, CreditNote, DebitNote, TaxRate, JournalEntry)
│   │   ├── Services/ (InvoiceService, PaymentGatewayService, ReconciliationService, AccountingPostingService)
│   │   ├── Webhooks/ (MidtransWebhookProcessor.php)
│   │   └── Jobs/ (GenerateRecurringInvoicesJob, RetryFailedPaymentsJob)
│   │
│   └── Affiliate/
│       ├── Models/ (Affiliator, AffiliateTier, AffiliateCommissionRule, AffiliateCommission, AffiliateWallet, AffiliateWithdrawal)
│       ├── Services/ (CommissionCalculationService, ReferralTrackingService, TierUpgradeEvaluationService)
│       ├── Jobs/ (CalculateRecurringRenewalCommissionJob, ProcessProjectMilestoneCommissionJob)
│       └── Events/ (CommissionGeneratedEvent, WithdrawalPaidEvent)
│
├── Shared/
│   ├── Contracts/ (TenantScopedInterface, LoggableAuditInterface)
│   ├── Exceptions/ (QuotaExceededException, InvalidLicenseFingerprintException, StateMachineTransitionException)
│   └── Helpers/ (MoneyFormatter, FingerprintHasher)
```

---

## Bagian 5: End-to-End Transaction Flows & Workflow Automation

### Flow 1: Software SaaS Purchase + Subscription + Add-on + Real-Time Quota Limit

```
[Customer Client]              [cooca-id Engine]                [Midtrans PG]            [Provisioning Service]
       │                              │                               │                             │
       ├── 1. Checkout SaaS Plan ────>│                               │                             │
       │    (+ Add-on Storage 50GB)   │                               │                             │
       │                              ├── 2. PricingEngine & Tax ────>│                             │
       │                              │    (Generate Invoice #INV-1)  │                             │
       │                              │── 3. Request Payment Token ──>│                             │
       │<── 4. Redirect Midtrans URL ─│                               │                             │
       ├── 5. Pay via Virtual Account ───────────────────────────────>│                             │
       │                              │<── 6. Webhook Notification ───│                             │
       │                              │    (verify signature & lock)  │                             │
       │                              ├── 7. Invoice & Payment Paid   │                             │
       │                              │── 8. Emit Event:              │                             │
       │                              │    SubscriptionCreated        │                             │
       │                              │── 9. StateMachine: ACTIVE     │                             │
       │                              │── 10. Allocations created:    │                             │
       │                              │     Base Plan + 50GB Add-on   │── 11. Dispatch Provisioning ─>│
       │                              │                               │      Job (Create Tenant DB) │
       │<── 12. Send Access Credentials & Welcome Email ──────────────│<── 13. Tenant Provisioned ──┤
```

---

### Flow 2: Web-Based Lifetime Software Purchase + Domain API Activation

```
[Customer Client]              [cooca-id Engine]             [Client Web Server (Self-Hosted)]
       │                              │                                  │
       ├── 1. Buy Web Script/SaaS ───>│                                  │
       │      (With 1-Yr Support)     │                                  │
       ├── 2. Payment Completed ─────>│                                  │
       │                              ├── 3. Create License (Key, Max 1) │
       │<── 4. Email License Key ─────│                                  │
       │                              │                                  │
       │                              │   5. User Installs Web App ─────>│
       │                              │      (Inputs License Key)        │
       │                              │<── 6. App Calls /api/verify ─────┤
       │                              │      (Sends Key + Domain + IP)   │
       │                              ├── 7. Validate Domain Limits      │
       │                              ├── 8. Create LicenseActivation    │
       │                              │── 9. Return Token/Success ──────>│
       │                              │                                  ├── 10. Web App Activated
```

---

### Flow 3: Custom Development End-to-End Workflow (Lead → Quotation → Milestone Billing → UAT → Warranty → Maintenance)

```
[Client / Prospect]        [Solution Architect / Admin]           [cooca-id Project Engine]              [Developer Team]
         │                              │                                     │                                 │
         ├── 1. Submit Custom Inquiry ─>│                                     │                                 │
         │                              ├── 2. Meeting & Req. Gathering ─────>│ (Create RequirementGathering)   │
         │                              ├── 3. Build Quotation & Milestones ─>│ (DP 30%, Termin 1 40%, UAT 30%) │
         │<── 4. Send PDF Quotation ────│                                     │                                 │
         ├── 5. Client Digital Sign-off>│                                     │                                 │
         │                              ├── 6. Convert to Contract & Project >│ (Project State: INIT)           │
         │                              │                                     ├── 7. Auto-Generate Invoice DP  │
         ├── 8. Pay DP Invoice 30% ──────────────────────────────────────────>│                                 │
         │                              │                                     ├── 9. Project State: ACTIVE     │
         │                              │                                     ├── 10. Create Sprints & Tasks ──>│
         │                              │                                     │                                 ├── 11. Execute Tasks &
         │                              │                                     │<── 12. Submit Timesheets ───────┤       Log Hours
         │                              ├── 13. Mark Milestone 1 Complete ───>│                                 │
         │                              │                                     ├── 14. Auto-Generate Termin 1    │
         ├── 15. Pay Termin 1 Invoice ───────────────────────────────────────>│     (Invoice 40%)               │
         │                              │                                     ├── 16. Client Scope Change Req?  │
         ├── 17. Request Extra AI CR ──>│                                     ├── 18. Create ScopeChangeRequest │
         │                              ├── 19. Approve CR Cost (+Rp 20 Juta)>│     (Timeline & Budget Updated) │
         │                              ├── 20. Deploy to Staging & UAT ─────>│                                 │
         ├── 21. Sign UAT Document ────>│                                     ├── 22. UAT Approved Sign-off     │
         │                              │                                     ├── 23. Auto-Generate Final Inv   │
         ├── 24. Pay Final Invoice ──────────────────────────────────────────>│     (Termin 2 30% + CR Cost)    │
         │                              ├── 25. Production Go-Live ──────────>│ (Project State: COMPLETED)      │
         │                              │                                     ├── 26. Create ProjectWarranty    │
         │                              │                                     │     (3 Months Free SLA Support) │
         │                              │                                     ├── [3 Months Later Cron Job]     │
         │                              │                                     ├── 27. Auto-Generate Maintenance │
         │<── 28. Send Maintenance Contract Offer ────────────────────────────│     Contract Renewal Offer      │
```

---

### Flow 4: Affiliate Tier & Recurring Renewal Commission (with Milestone Split)

```
[Affiliator Partner]        [Referred Client]               [cooca-id Commission Engine]         [Admin Audit / Finance]
         │                         │                                     │                                  │
         ├── 1. Share Referral URL │                                     │                                  │
         │                         ├── 2. Buy SaaS Annual Plan ($1,000) ─>│                                  │
         │                         ├── 3. Payment Paid ─────────────────>│                                  │
         │                         │                                     ├── 4. Check Affiliator Tier       │
         │                         │                                     │    (Gold Tier = 25% Rate)        │
         │                         │                                     ├── 5. Create AffiliateCommission  │
         │                         │                                     │    (Status: PENDING, $250)       │
         │                         │                                     │                                  │
         │                         ├── [1 Year Later: Annual Renewal]    │                                  │
         │                         ├── 6. Pay Renewal Invoice ($1,000) ─>│                                  │
         │                         │                                     ├── 7. Check Recurring Rule        │
         │                         │                                     │    (Valid for 3 Years Renewal)   │
         │                         │                                     ├── 8. Create Renewal Commission   │
         │                         │                                     │    (Status: PENDING, $250)       │
         │                         │                                     │                                  │
         │                         ├── [Scenario B: Custom Dev Project]  │                                  │
         │                         ├── 9. Pay Project DP Invoice (30%) ─>│                                  │
         │                         │                                     ├── 10. Calculate Split Commission │
         │                         │                                     │    (30% of total $10,000 comm =  │
         │                         │                                     │     $3,000 Commission Released)  │
         │                         │                                     │                                  │
         │<── 11. Request Withdrawal ($3,500 total in Wallet) ───────────│                                  │
         │                                                               │── 12. Submit Withdrawal Request >│
         │                                                               │                                  ├── 13. Admin Approve
         │<── 14. Bank Transfer Executed & Wallet Balance Deducted ─────────────────────────────────────────┤       & PPh Tax Withheld
```

---

## Bagian 6: Rekomendasi Implementasi & Roadmap Eksekusi Engineering

To systematically transform `cooca-id` from its existing state into this Enterprise Single Business Management Platform, the engineering team should execute the following **4-Phase Implementation Roadmap**:

### Phase 1: Foundation, Pricing & Catalog Refactoring (Weeks 1-3)

1. **Migrate Product Entities:** Create migrations for `product_types`, `product_variants`, `product_bundles`, `product_bundle_items`, `product_versions`, `product_modules`, and `product_dependencies`.
2. **Advanced Pricing Engine:** Build `PriceBook`, `UsagePricingRule`, and `VolumeDiscountTier` models. Refactor `ProductService` to support multi-currency, tax calculation (`tax_rates`), and dynamic price evaluation during checkout.
3. **Upgrade Subscription State Machine:** Implement the `SubscriptionStateMachine` service to rigidly handle transitions (`TRIAL` → `ACTIVE` → `GRACE_PERIOD` → `SUSPENDED`). Add scheduled cron jobs (`SendGracePeriodRemindersJob`, `SuspendExpiredSubscriptionsJob`).

### Phase 2: Custom Development & Project Management Module (Weeks 4-7)

1. **Pre-Sales CRM Entities:** Create models and CRUD admin controllers for `RequirementGathering`, `ClientMeeting`, `Quotation`, `QuotationItem`, and `Contract`.
2. **Project & Sprint Engine:** Implement `Project`, `ProjectSprint`, `ProjectTask`, `ProjectMilestone`, `Timesheet`, `ScopeChangeRequest`, `UserAcceptanceTest`, `ProjectWarranty`, and `MaintenanceContract`.
3. **Milestone Billing Bridge:** Create `MilestoneBillingService` that links `ProjectMilestone` directly to `Invoice` generation upon approval. Build the scope change request (CR) impact calculator.

### Phase 3: Advanced Licensing & Enterprise Financials (Weeks 8-10)

1. **API Domain Licensing:** Implement `DomainActivationService` dan API endpoint `/api/v1/licenses/verify` untuk memvalidasi aktivasi lisensi dari server web klien berdasarkan batas jumlah domain/IP.
2. **Enforce Limits:** Build middleware `VerifyProductLicenseToken` dan service `QuotaEnforcementService` (`tenant_resource_usages`).
3. **Reconciliation & Accounting Hooks:** Add `CreditNote`, `DebitNote`, `PaymentSchedule` (Installments), and `JournalEntry` event listeners triggered by every Midtrans webhook payment or manual transfer reconciliation.

### Phase 4: Affiliate Rule Engine & Single Admin Portal Integration (Weeks 11-12)

1. **Multi-Tier Affiliate Rules:** Upgrade `AffiliateCommissionRule` to support recurring subscription renewals and custom project milestone payment splits.
2. **Single Admin Dashboard UI:** Create unified Blade / Alpine.js / Vue management views across all 10 core business streams under `app/Http/Controllers/Admin/` and `resources/views/admin/`.
3. **System Audit & Load Testing:** Perform comprehensive stress testing on webhook idempotency, proration calculations, concurrency license checking, and multi-tenant resource limits.

---

_End of Enterprise Business Logic Audit & Architecture Blueprint for cooca-id._
