# COOCA.ID — UI/UX Specification
## Bagian 9 dari Rangkaian Dokumentasi

Setiap halaman kunci dijelaskan dengan struktur: Layout, Components, Form, Validation, Responsive Behaviour, Loading State, Empty State, Error State, Success State.

---

# 1. Halaman: Landing Page (Guest)

**Layout:** Hero section full-width dengan CTA ganda ("Coba Gratis" & "Lihat Marketplace"), diikuti section Fitur Unggulan (grid 3 kolom), section Kategori ERP, Testimoni (carousel), Pricing Preview, Footer dengan link CMS.

**Components:** NavbarSticky, HeroBanner, FeatureCard, CategoryCard, TestimonialCarousel, PricingCard, CTASection, Footer.

**Responsive Behaviour:** Grid 3 kolom → 2 kolom (tablet) → 1 kolom (mobile). Navbar berubah menjadi hamburger menu di bawah 768px.

**Loading State:** Skeleton loader pada TestimonialCarousel dan CategoryCard saat data dimuat dari CMS API.

**Empty State:** Jika belum ada testimoni dipublikasikan, section testimoni disembunyikan otomatis (bukan menampilkan pesan kosong yang mengganggu estetika marketing).

---

# 2. Halaman: ERP Marketplace (Guest)

**Layout:** Sidebar filter (kategori, rentang harga) di kiri (desktop) / collapsible drawer (mobile), grid produk di kanan, search bar di atas.

**Components:** FilterSidebar, SearchBar, ProductCard (thumbnail, nama, kategori, harga mulai dari, rating), Pagination.

**Form:** Search input dengan debounce 400ms sebelum trigger API call.

**Validation:** Tidak ada validasi form kompleks; hanya sanitasi input pencarian.

**Loading State:** Skeleton grid (6 placeholder card) saat fetch data.

**Empty State:** Ilustrasi "Tidak ada produk ditemukan" dengan tombol "Reset Filter" ketika hasil pencarian kosong.

**Error State:** Toast notification "Gagal memuat data, coba lagi" dengan tombol retry jika API gagal.

---

# 3. Halaman: Register & Login (Guest)

**Layout:** Split-screen — kiri ilustrasi brand, kanan form. Mobile: form full-width, ilustrasi disembunyikan.

**Form Register:** Nama, Email, Password, Konfirmasi Password, Nomor Telepon, Kode Affiliate (opsional, collapsible "Punya kode referral?").

**Validation:** Real-time inline validation per field (on blur), indikator kekuatan password (lemah/sedang/kuat) di bawah field password.

**Loading State:** Tombol submit berubah menjadi spinner + teks "Memproses..." dan disabled selama request berlangsung.

**Success State:** Redirect ke halaman "Cek Email Anda" dengan ilustrasi amplop dan tombol "Kirim Ulang Email" (cooldown timer 60 detik ditampilkan sebagai countdown).

**Error State:** Alert merah di atas form dengan pesan spesifik (contoh: "Email sudah terdaftar, silakan login").

---

# 4. Halaman: Customer Dashboard

**Layout:** Sidebar navigasi kiri (Dashboard, Trial, Subscription, Domain, License, Ticket, dst.), topbar dengan notifikasi bell dan profil dropdown, area konten utama menampilkan widget ringkasan.

**Components:** StatWidget (jumlah tenant aktif, invoice belum dibayar, ticket terbuka), TrialProgressCard (jika ada trial dalam proses), RecentActivityFeed.

**Responsive Behaviour:** Sidebar collapse menjadi bottom navigation bar pada mobile (< 768px) dengan 4-5 ikon utama.

**Loading State:** Skeleton widget saat data awal dimuat.

**Empty State:** Jika belum ada tenant/trial, tampilkan CTA besar "Mulai Trial ERP Pertama Anda" mengarah ke Marketplace.

---

# 5. Halaman: Ajukan Trial (Customer)

**Layout:** Wizard 3 langkah — (1) Pilih ERP & Paket, (2) Pilih Subdomain, (3) Konfirmasi & Kode Affiliate — dengan progress stepper di atas.

**Form:**
- Step 1: Card selectable untuk setiap Subscription Plan (radio behavior).
- Step 2: Input subdomain dengan live preview (`{input}.cooca.id`) dan pengecekan ketersediaan real-time (debounce 500ms, indikator centang hijau/silang merah).
- Step 3: Ringkasan pilihan, input kode affiliate opsional, checkbox persetujuan Syarat & Ketentuan.

**Validation:** Subdomain: lowercase, alphanumeric-dash, 3–63 karakter, tidak boleh diawali/diakhiri dash.

**Loading State:** Setelah submit, tampilkan halaman transisi "Trial Anda Sedang Diproses" dengan animasi step indicator yang merefleksikan status real (`Submitted` → `Provisioning` → `ActiveTrial`), di-polling setiap 5 detik.

**Error State:** Jika `TRIAL_QUOTA_EXCEEDED`, tampilkan modal informatif "Anda sudah pernah mencoba produk ini" dengan CTA "Lihat Subscription" bila ingin langsung berlangganan.

**Success State:** Halaman "ERP Anda Siap!" menampilkan URL akses, tombol "Buka ERP", dan ringkasan license key (masked).

---

# 6. Halaman: Subscription & Invoice (Customer)

**Layout:** Tab "Subscription Aktif" dan "Riwayat Invoice". Card subscription menampilkan badge status berwarna (hijau=Active, kuning=Grace Period, merah=Suspended, abu=Cancelled).

**Components:** SubscriptionCard, InvoiceTable (kolom: No. Invoice, Tanggal, Jumlah, Status, Aksi), UpgradeModal, DowngradeModal, CancelConfirmationModal.

**Form (Upgrade):** Pilihan plan dalam bentuk comparison table dengan highlight fitur tambahan, menampilkan estimasi biaya prorata secara real-time sebelum konfirmasi.

**Loading State:** Skeleton table saat memuat riwayat invoice.

**Empty State:** "Belum ada riwayat invoice" dengan ilustrasi minimalis untuk akun baru.

**Error State:** Jika pembayaran Midtrans gagal dimuat (Snap token error), tampilkan pesan "Gagal memuat halaman pembayaran, coba lagi" dengan tombol retry.

**Success State:** Setelah pembayaran sukses (dideteksi via polling status invoice setelah Snap popup ditutup), tampilkan toast hijau "Pembayaran Berhasil! Subscription Anda kini aktif."

---

# 7. Halaman: Affiliator Dashboard

**Layout:** Header dengan ringkasan Total Komisi (angka besar, prominent), tiga kartu breakdown (Pending, Available, Sudah Dicairkan), grafik tren referral bulanan (line chart), tabel referral terbaru.

**Components:** CommissionSummaryCard, ReferralTrendChart, ReferralTable, ReferralLinkBox (dengan tombol copy-to-clipboard dan generate QR).

**Loading State:** Skeleton pada chart dan summary card.

**Empty State:** "Belum ada referral" dengan panduan singkat cara membagikan link.

**Success State:** Toast "Link berhasil disalin!" saat tombol copy diklik.

---

# 8. Halaman: Withdrawal (Affiliator)

**Form:** Input Jumlah (dengan validasi minimal Rp 100.000 dan tidak melebihi Available Balance, ditampilkan sebagai helper text), Dropdown Bank, Input Nomor Rekening, Nama Pemilik Rekening.

**Validation:** Real-time — tombol submit disabled hingga seluruh field valid dan jumlah dalam rentang yang diizinkan.

**Loading State:** Spinner pada tombol submit.

**Error State:** `AFF_INSUFFICIENT_AVAILABLE_BALANCE` ditampilkan sebagai inline error di bawah field jumlah.

**Success State:** Modal konfirmasi "Permintaan Pencairan Diterima, akan diproses maksimal 3 hari kerja."

---

# 9. Halaman: Admin — Provisioning Monitoring

**Layout:** Tabel/Kanban board provisioning job dengan kolom sesuai status (Queued, Running, Completed, Failed), setiap card dapat diklik untuk membuka detail timeline step.

**Components:** ProvisioningJobCard, StepTimeline (vertical timeline dengan indikator warna per step), RetryButton, RollbackButton.

**Responsive Behaviour:** Kanban board menjadi accordion list pada mobile/tablet (Admin Panel diasumsikan primer digunakan di desktop, namun tetap harus dapat diakses darurat via mobile).

**Loading State:** Auto-refresh polling setiap 10 detik untuk job yang masih `Running`.

**Empty State:** "Tidak ada job aktif saat ini" pada kolom Queued/Running jika kosong.

**Error State:** Card berwarna merah dengan ikon peringatan pada job `Failed`, menampilkan `error_message` singkat dengan opsi "Lihat Detail Lengkap".

---

# 10. Halaman: Admin — Notification Log

**Layout:** Tabel filterable (Channel: Email/WhatsApp, Status, Event Code, Rentang Tanggal), setiap baris dapat di-expand untuk melihat payload lengkap.

**Components:** NotificationFilterBar, NotificationTable, PayloadPreviewModal.

**Empty State:** "Tidak ada notifikasi sesuai filter" dengan tombol "Reset Filter".

**Error State:** Baris dengan status `Failed` ditandai merah dengan tooltip alasan kegagalan (contoh: "SMTP connection timeout").

---

# 11. Design System Ringkas

| Elemen | Spesifikasi |
|---|---|
| Warna Primer | Biru korporat (trust, teknologi) sebagai warna utama CTA |
| Warna Sukses/Error/Warning | Hijau / Merah / Kuning, konsisten di seluruh badge status |
| Tipografi | Sans-serif modern untuk keterbacaan tinggi pada dashboard data-dense |
| Spacing System | Skala 4px/8px konsisten (Tailwind CSS spacing scale) |
| Komponen Interaktif | Skeleton loading di seluruh halaman data-heavy, toast notification untuk feedback aksi singkat, modal untuk konfirmasi aksi destruktif |

---

*Lanjut ke Bagian 10: Testing Documentation pada file `10-testing.md`.*
