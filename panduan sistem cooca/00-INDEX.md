# COOCA.ID — Dokumentasi Enterprise Lengkap
## Master Index

**Platform:** SaaS ERP Marketplace Multi-Tenant
**Stack:** Laravel 12, PHP 8.4, MySQL 8
**Infrastruktur:** Shared Hosting (Tanpa Redis, Tanpa Nginx, Tanpa Supervisor — Queue/Cache/Session via Database Driver, Scheduler via Cron)
**Notifikasi:** Email SMTP (wajib, seluruh event) + WhatsApp Fonnte (opsional tambahan)

---

## Daftar File & Cakupan 18 Dokumen

| File | Dokumen yang Dicakup |
|---|---|
| `01-executive-strategic-brd-prd.md` | 1. Executive Summary · 2. Vision & Mission · 3. Business Overview · 4. Stakeholder Analysis · 5. BRD · 6. PRD |
| `02-functional-spec-fsd.md` | 7. Functional Specification Document (12 modul: Auth, Profile, Catalog, Trial, Provisioning, Subscription, Invoice/Payment, Affiliate, Domain/License, Notification, Ticketing, CMS) |
| `03-srs.md` | 8. Software Requirement Specification (IEEE 830/29148) |
| `04-ddd.md` | 9. Domain Driven Design (Bounded Context, Aggregate, Entity, Value Object, Domain Event, Repository, Service, Factory, Specification, Ubiquitous Language) |
| `05-architecture.md` | 10. System Architecture Document (Context, Container, Component, Deployment, Sequence, Infrastructure Diagram) |
| `06-database.md` | 11. Database Design (ERD, Logical/Physical Model, Data Dictionary, Index/UUID/Soft Delete/Audit Strategy) |
| `07-api-spec.md` | 12. API Specification (seluruh endpoint per modul) |
| `08-security-rbac.md` | 13. Role & Permission Matrix · 14. Security Design |
| `09-uiux.md` | 15. UI/UX Specification (per halaman kunci) |
| `10-testing.md` | 16. Testing Documentation (Unit, Feature, Integration, API, UAT, Security, Performance, Load, Regression) |
| `11-devops.md` | 17. DevOps Documentation (CI/CD, arsitektur tanpa Docker, queue tanpa Supervisor, backup, DR, monitoring) |
| `12-golive-checklist.md` | 18. Production Readiness Checklist + Sign-Off |

---

## Catatan Adaptasi Infrastruktur (Berlaku di Seluruh Dokumen)

Spesifikasi awal mengasumsikan Redis dan Nginx. Karena COOCA.ID dioperasikan di **shared hosting**, seluruh dokumen telah disesuaikan:

| Komponen | Asumsi Awal | Diganti Menjadi |
|---|---|---|
| Queue Driver | Redis | `database` (tabel `jobs`, `failed_jobs`), diproses via Cron per menit |
| Cache Driver | Redis | `database` (tabel `cache`) |
| Session Driver | Redis | `database` (tabel `sessions`) |
| Web Server | Nginx | Apache/LiteSpeed bawaan hosting + `.htaccess` |
| Process Manager | Supervisor | Cron Job (`schedule:run` tiap menit, `queue:work --stop-when-empty --max-time=50`) |
| Container | Docker | Deployment berbasis Git/SFTP dengan struktur symlink release |
| SSL | Manual Certbot/Nginx config | AutoSSL/Let's Encrypt bawaan cPanel |

**Notifikasi:** Seluruh 18+ event transaksional lintas modul (lihat FSD Modul 10.6) WAJIB mengirim Email SMTP sebagai kanal utama; WhatsApp bersifat tambahan opsional dan kegagalannya tidak memengaruhi status pengiriman email.

---

## Urutan Baca yang Direkomendasikan

1. **Business & Product Owner:** File 01 → 09 (skip teknis mendalam di 04, 06, 07)
2. **Solution/Software Architect:** File 01 → 05 → 04 → 06 → 07
3. **Backend Developer:** File 02 → 04 → 06 → 07 → 03
4. **Frontend Developer:** File 02 → 09 → 07
5. **QA Engineer:** File 02 → 10
6. **DevOps Engineer:** File 05 → 11 → 12
7. **Security Reviewer:** File 08 → 12
