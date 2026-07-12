# COOCA.ID — System Architecture Document
## Bagian 5 dari Rangkaian Dokumentasi
### Adaptasi Infrastruktur: Shared Hosting (Tanpa Redis, Tanpa Nginx, Tanpa Supervisor)

---

# 1. Context Diagram

```mermaid
graph TB
    Guest((Guest/Calon Customer))
    Customer((Customer))
    Affiliator((Affiliator))
    Admin((Admin Internal))

    Guest -->|Browse Marketplace, Register| COOCA[Platform COOCA.ID]
    Customer -->|Trial, Subscription, Billing| COOCA
    Affiliator -->|Referral, Commission| COOCA
    Admin -->|Kelola Operasional| COOCA

    COOCA -->|Payment| Midtrans[Midtrans Payment Gateway]
    COOCA -->|Email| SMTP[SMTP Provider]
    COOCA -->|WhatsApp Opsional| Fonnte[Fonnte API]
    COOCA -->|OAuth| Google[Google Identity]
    COOCA -->|Provisioning API Call| TenantERP[Instance ERP Tenant]
    COOCA -->|DNS Verification| DNS[DNS Provider]
```

---

# 2. Container Diagram

```mermaid
graph TB
    subgraph "Shared Hosting Server - COOCA.ID"
        WEB[Apache/LiteSpeed + PHP-FPM 8.4]
        APP[Laravel 12 Application]
        DBQ[(MySQL 8 - Application Data + Queue Table + Cache Table + Session Table)]
        CRON[Cron Job Scheduler]
        STORAGE[Local File Storage - public/storage]
    end

    subgraph "Shared Hosting Server - Tenant ERP misal Bagema"
        TAPP[Laravel 11 - ERP Application]
        TDB[(MySQL 8 - Tenant Database Terisolasi)]
    end

    Browser[Web Browser Customer/Admin/Affiliator] -->|HTTPS| WEB
    WEB --> APP
    APP -->|Eloquent ORM| DBQ
    APP -->|Queue Jobs table: jobs, failed_jobs| DBQ
    CRON -->|setiap 1 menit: schedule:run| APP
    APP -->|Provisioning API Call HTTPS| TAPP
    TAPP --> TDB
    APP -->|Read/Write Files| STORAGE
```

**Keterangan Adaptasi:**
- Tidak ada container Redis — cache, session, dan queue seluruhnya menggunakan tabel MySQL (`cache`, `sessions`, `jobs`, `failed_jobs`).
- Tidak ada container Nginx sebagai reverse proxy terpisah — web server bawaan shared hosting (Apache/LiteSpeed) langsung melayani permintaan PHP-FPM.
- Tidak ada Supervisor untuk menjaga queue worker tetap hidup — proses queue dijalankan secara **batch per menit** oleh Cron Job yang memanggil `php artisan queue:work --stop-when-empty --max-time=50` (dibatasi 50 detik agar tidak tumpang tindih dengan eksekusi cron berikutnya).

---

# 3. Component Diagram (Application Layer — Laravel 12)

```mermaid
graph TB
    subgraph Presentation Layer
        HC[HTTP Controllers]
        FR[Form Requests - Validation]
        RES[API Resources]
    end
    subgraph Application Layer
        SVC[Application Services]
        JOB[Queued Jobs]
        LIST[Event Listeners]
    end
    subgraph Domain Layer
        AGG[Aggregates/Entities]
        VO[Value Objects]
        DS[Domain Services]
        SPEC[Specifications]
        REPOI[Repository Interfaces]
    end
    subgraph Infrastructure Layer
        REPO[Eloquent Repositories]
        MODEL[Eloquent Models]
        MAIL[Mail - SMTP Transport]
        WA[Fonnte HTTP Client]
        MT[Midtrans SDK/HTTP Client]

    end

    HC --> FR
    HC --> SVC
    SVC --> DS
    SVC --> REPOI
    DS --> AGG
    DS --> VO
    DS --> SPEC
    REPOI -.implemented by.-> REPO
    REPO --> MODEL
    SVC --> JOB
    JOB --> MAIL
    JOB --> WA
    SVC --> MT
    LIST --> SVC
    HC --> RES
```

---

# 4. Deployment Diagram

```mermaid
graph TB
    subgraph "cPanel Shared Hosting Account - COOCA.ID"
        direction TB
        A1[public_html -> symlink ke /app/public]
        A2[Laravel Application Root /app]
        A3[MySQL Database Instance]
        A4[Cron Job Configuration]
        A5[SSL - AutoSSL/Let's Encrypt bawaan cPanel]
    end

    Internet((Internet)) -->|HTTPS 443| A5
    A5 --> A1
    A1 --> A2
    A2 --> A3
    A4 -->|memicu setiap menit| A2
```

**Catatan Deployment:**
- SSL menggunakan AutoSSL (Let's Encrypt) yang umum tersedia gratis di cPanel — tidak memerlukan konfigurasi Nginx/Certbot manual.
- Deployment kode dilakukan via Git deployment (cPanel Git Version Control) atau upload manual melalui SSH/SFTP jika tersedia, diikuti `composer install --no-dev --optimize-autoloader` dan `php artisan optimize`.

---

# 5. Sequence Diagram — Alur Provisioning Lintas Server (End-to-End)

```mermaid
sequenceDiagram
    participant CRON as Cron (COOCA Server)
    participant APP as COOCA App
    participant DB as COOCA MySQL
    participant TERP as Tenant ERP Server (Terpisah)
    participant SMTP as SMTP Provider

    CRON->>APP: php artisan schedule:run
    APP->>DB: Ambil ProvisioningJob berstatus Queued
    APP->>TERP: HTTP POST /provisioning/create-database (kredensial terenkripsi)
    TERP-->>APP: 200 OK (database siap)
    APP->>TERP: HTTP POST /provisioning/migrate
    TERP-->>APP: 200 OK (migration selesai)
    APP->>TERP: HTTP POST /provisioning/seed
    TERP-->>APP: 200 OK (seeder selesai)
    APP->>TERP: HTTP GET /api/health
    TERP-->>APP: 200 OK {status: ok}
    APP->>DB: Update status ActiveTrial
    APP->>SMTP: Kirim Email "ERP Siap Digunakan"
    SMTP-->>APP: 250 Message Accepted
```

---

# 6. Infrastructure Diagram (Topologi Keseluruhan)

```mermaid
graph LR
    subgraph "Provider Shared Hosting A"
        COOCA_SRV[Server COOCA.ID Platform]
    end
    subgraph "Provider Shared Hosting B"
        BAGEMA_SRV[Server ERP Bagema]
    end
    subgraph "Provider Shared Hosting C"
        MANUFAKTUR_SRV[Server ERP Manufaktur]
    end
    subgraph "Third Party Services"
        MIDTRANS_SVC[Midtrans]
        SMTP_SVC[SMTP Provider]
        FONNTE_SVC[Fonnte]
        DNS_SVC[DNS/Registrar]
    end

    COOCA_SRV <-->|HTTPS Provisioning + Health Check| BAGEMA_SRV
    COOCA_SRV <-->|HTTPS Provisioning + Health Check| MANUFAKTUR_SRV
    COOCA_SRV -->|HTTPS| MIDTRANS_SVC
    COOCA_SRV -->|SMTP TLS| SMTP_SVC
    COOCA_SRV -->|HTTPS| FONNTE_SVC
    COOCA_SRV -->|DNS Lookup/API| DNS_SVC
```

**Prinsip Arsitektur Kunci — Independent Hosting per Produk ERP:** Setiap produk ERP (Bagema, Manufaktur, Retail, dst.) dihosting sepenuhnya independen (server/akun hosting terpisah), sesuai konteks bisnis existing COOCA yang berperan sebagai hub sentral. COOCA.ID hanya berkomunikasi dengan masing-masing instance ERP melalui REST API terstandar (endpoint provisioning dan health check) yang wajib diimplementasikan oleh setiap produk ERP mitra sesuai kontrak API pada Bagian 12 (API Specification).

---

*Lanjut ke Bagian 6: Database Design pada file `06-database.md`.*
