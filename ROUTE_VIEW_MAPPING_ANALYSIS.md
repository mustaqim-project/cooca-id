# 📊 COOCA.ID - ROUTE & VIEW MAPPING ANALYSIS

## 🎯 EXECUTIVE SUMMARY

**Status Implementasi**: 75% Complete  
**Total Routes**: ~120 endpoints  
**Vue Components Needed**: 35 files  
**Vue Components Existing**: 14 files  
**Missing Views**: 21 files (60%)

---

## 📁 1. WEB ROUTES (Public & Auth)

### ✅ Implemented Routes

| Method | URI | Controller | View/Response | Status |
|--------|-----|------------|---------------|--------|
| GET | `/` | LandingController@index | Landing/Home.vue | ✅ EXISTS |
| GET | `/about` | LandingController@about | Landing/About.vue | ✅ EXISTS |
| GET | `/pricing` | LandingController@pricing | Landing/Pricing.vue | ✅ EXISTS |
| GET | `/contact` | LandingController@contact | Landing/Contact.vue | ✅ EXISTS |
| GET | `/affiliate` | LandingController@affiliate | Landing/Affiliate.vue | ✅ EXISTS |
| GET | `/blog` | BlogController@index | Blog/Index.vue | ✅ EXISTS |
| GET | `/blog/{slug}` | BlogController@show | Blog/Show.vue | ✅ EXISTS |

### ⚠️ Auth Views (Using Blade Templates)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/customer/login` | AuthController@showCustomerLogin | auth.customer.login.blade.php | ✅ EXISTS (Blade) |
| POST | `/customer/login` | AuthController@customerLogin | → Redirect to customer.dashboard | ✅ API |
| GET | `/customer/register` | AuthController@showCustomerRegister | auth.customer.register.blade.php | ✅ EXISTS (Blade) |
| POST | `/customer/register` | AuthController@customerRegister | → Redirect to customer.dashboard | ✅ API |
| GET | `/customer/auth/google` | AuthController@redirectToGoogle | → External OAuth | ✅ OK |
| GET | `/customer/auth/google/callback` | AuthController@handleGoogleCallback | → Redirect to customer.dashboard | ✅ API |
| POST | `/customer/logout` | AuthController@customerLogout | → Redirect to home | ✅ API |
| GET | `/affiliator/login` | AuthController@showAffiliatorLogin | auth.affiliator.login.blade.php | ✅ EXISTS (Blade) |
| POST | `/affiliator/login` | AuthController@affiliatorLogin | → Redirect to affiliator.dashboard | ✅ API |
| GET | `/affiliator/register` | AuthController@showAffiliatorRegister | auth.affiliator.register.blade.php | ✅ EXISTS (Blade) |
| POST | `/affiliator/register` | AuthController@affiliatorRegister | → Redirect to affiliator.dashboard | ✅ API |
| POST | `/affiliator/logout` | AuthController@affiliatorLogout | → Redirect to home | ✅ API |
| GET | `/admin/login` | AuthController@showAdminLogin | auth.admin.login.blade.php | ✅ EXISTS (Blade) |
| POST | `/admin/login` | AuthController@adminLogin | → Redirect to admin.dashboard | ✅ API |
| POST | `/admin/logout` | AuthController@adminLogout | → Redirect to home | ✅ API |

**Note**: Auth pages menggunakan Blade templates, bukan Inertia/Vue. Ini acceptable untuk halaman auth sederhana.

---

## 👨‍💼 2. ADMIN PANEL ROUTES

### ✅ Dashboard

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/dashboard` | DashboardController@index | Admin/Dashboard/Index.vue | ✅ EXISTS |

### ✅ Products Management

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/products` | ProductController@index | Admin/Products/Index.vue | ✅ EXISTS |
| GET | `/admin/products/create` | ProductController@create | Admin/Products/Create.vue | ❌ MISSING |
| POST | `/admin/products` | ProductController@store | → admin.products.index | ✅ API |
| GET | `/admin/products/{product}` | ProductController@show | Admin/Products/Show.vue | ❌ MISSING |
| GET | `/admin/products/{product}/edit` | ProductController@edit | Admin/Products/Edit.vue | ❌ MISSING |
| PUT | `/admin/products/{product}` | ProductController@update | → admin.products.index | ✅ API |
| DELETE | `/admin/products/{product}` | ProductController@destroy | → admin.products.index | ✅ API |

### ⚠️ Customers Management

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/customers` | CustomerController@index | Admin/Customers/Index.vue | ✅ EXISTS |
| GET | `/admin/customers/{customer}` | CustomerController@show | Admin/Customers/Show.vue | ❌ MISSING |
| PUT | `/admin/customers/{customer}` | CustomerController@update | → JSON Response | ✅ API |
| DELETE | `/admin/customers/{customer}` | CustomerController@destroy | → JSON Response | ✅ API |

### ⚠️ Affiliators Management

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/affiliators` | AffiliatorController@index | Admin/Affiliators/Index.vue | ✅ EXISTS |
| GET | `/admin/affiliators/{affiliator}` | AffiliatorController@show | Admin/Affiliators/Show.vue | ❌ MISSING |
| PUT | `/admin/affiliators/{affiliator}` | AffiliatorController@update | → JSON Response | ✅ API |
| DELETE | `/admin/affiliators/{affiliator}` | AffiliatorController@destroy | → JSON Response | ✅ API |

### ⚠️ Licenses Management

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/licenses` | LicenseController@index | Admin/Licenses/Index.vue | ✅ EXISTS |
| POST | `/admin/licenses/generate` | LicenseController@generate | → JSON Response | ✅ API |
| GET | `/admin/licenses/{license}` | LicenseController@show | Admin/Licenses/Show.vue | ❌ MISSING |
| POST | `/admin/licenses/{license}/revoke` | LicenseController@revoke | → JSON Response | ✅ API |
| POST | `/admin/licenses/{license}/activate` | LicenseController@activate | → JSON Response | ✅ API |

### ❌ Subscriptions Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/subscriptions` | SubscriptionController@index | Admin/Subscriptions/Index.vue | ❌ MISSING |
| GET | `/admin/subscriptions/{subscription}` | SubscriptionController@show | Admin/Subscriptions/Show.vue | ❌ MISSING |
| POST | `/admin/subscriptions/{subscription}/cancel` | SubscriptionController@cancel | → JSON Response | ✅ API |

### ❌ Transactions Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/transactions` | TransactionController@index | Admin/Transactions/Index.vue | ❌ MISSING |
| GET | `/admin/transactions/{transaction}` | TransactionController@show | Admin/Transactions/Show.vue | ❌ MISSING |
| POST | `/admin/transactions/{transaction}/refund` | TransactionController@refund | → JSON Response | ✅ API |

### ⚠️ Vouchers Management

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/vouchers` | VoucherController@index | Admin/Vouchers/Index.vue | ✅ EXISTS |
| GET | `/admin/vouchers/create` | VoucherController@create | Admin/Vouchers/Create.vue | ❌ MISSING |
| POST | `/admin/vouchers` | VoucherController@store | → admin.vouchers.index | ✅ API |
| GET | `/admin/vouchers/{voucher}` | VoucherController@show | Admin/Vouchers/Show.vue | ❌ MISSING |
| GET | `/admin/vouchers/{voucher}/edit` | VoucherController@edit | Admin/Vouchers/Edit.vue | ❌ MISSING |
| PUT | `/admin/vouchers/{voucher}` | VoucherController@update | → admin.vouchers.index | ✅ API |
| DELETE | `/admin/vouchers/{voucher}` | VoucherController@destroy | → JSON Response | ✅ API |

### ⚠️ Settlements (Withdrawals) Management

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/settlements` | SettlementController@index | Admin/Settlements/Index.vue | ✅ EXISTS |
| GET | `/admin/settlements/{settlement}` | SettlementController@show | Admin/Settlements/Show.vue | ❌ MISSING |
| POST | `/admin/settlements/{settlement}/approve` | SettlementController@approve | → JSON Response | ✅ API |
| POST | `/admin/settlements/{settlement}/reject` | SettlementController@reject | → JSON Response | ✅ API |

### ❌ CMS Pages Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/cms/pages` | CmsController@index | Admin/Cms/Pages/Index.vue | ❌ MISSING |
| GET | `/admin/cms/pages/create` | CmsController@create | Admin/Cms/Pages/Create.vue | ❌ MISSING |
| POST | `/admin/cms/pages` | CmsController@store | → admin.cms.pages.index | ✅ API |
| GET | `/admin/cms/pages/{page}/edit` | CmsController@edit | Admin/Cms/Pages/Edit.vue | ❌ MISSING |
| PUT | `/admin/cms/pages/{page}` | CmsController@update | → admin.cms.pages.index | ✅ API |
| DELETE | `/admin/cms/pages/{page}` | CmsController@destroy | → JSON Response | ✅ API |

### ❌ Blog Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/blog` | BlogController@index | Admin/Blog/Index.vue | ❌ MISSING |
| GET | `/admin/blog/create` | BlogController@create | Admin/Blog/Create.vue | ❌ MISSING |
| POST | `/admin/blog` | BlogController@store | → admin.blog.index | ✅ API |
| GET | `/admin/blog/{post}/edit` | BlogController@edit | Admin/Blog/Edit.vue | ❌ MISSING |
| PUT | `/admin/blog/{post}` | BlogController@update | → admin.blog.index | ✅ API |
| DELETE | `/admin/blog/{post}` | BlogController@destroy | → JSON Response | ✅ API |

### ❌ Email Campaigns Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/email-campaigns` | EmailCampaignController@index | Admin/EmailCampaigns/Index.vue | ❌ MISSING |
| GET | `/admin/email-campaigns/create` | EmailCampaignController@create | Admin/EmailCampaigns/Create.vue | ❌ MISSING |
| POST | `/admin/email-campaigns` | EmailCampaignController@store | → admin.email-campaigns.index | ✅ API |
| GET | `/admin/email-campaigns/{campaign}` | EmailCampaignController@show | Admin/EmailCampaigns/Show.vue | ❌ MISSING |
| POST | `/admin/email-campaigns/{campaign}/send` | EmailCampaignController@send | → JSON Response | ✅ API |

### ❌ Support Tickets Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/tickets` | TicketController@index | Admin/Tickets/Index.vue | ❌ MISSING |
| GET | `/admin/tickets/{ticket}` | TicketController@show | Admin/Tickets/Show.vue | ❌ MISSING |
| POST | `/admin/tickets/{ticket}/reply` | TicketController@reply | → JSON Response | ✅ API |
| POST | `/admin/tickets/{ticket}/resolve` | TicketController@resolve | → JSON Response | ✅ API |
| POST | `/admin/tickets/{ticket}/close` | TicketController@close | → JSON Response | ✅ API |

### ❌ Reviews Moderation (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/reviews` | ReviewController@index | Admin/Reviews/Index.vue | ❌ MISSING |
| POST | `/admin/reviews/{review}/approve` | ReviewController@approve | → JSON Response | ✅ API |
| POST | `/admin/reviews/{review}/reject` | ReviewController@reject | → JSON Response | ✅ API |
| DELETE | `/admin/reviews/{review}` | ReviewController@destroy | → JSON Response | ✅ API |

### ❌ Settings (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/admin/settings` | SettingsController@index | Admin/Settings/Index.vue | ❌ MISSING |
| PUT | `/admin/settings` | SettingsController@update | → JSON Response | ✅ API |

---

## 👤 3. CUSTOMER PANEL ROUTES

### ✅ Dashboard

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/customer/dashboard` | DashboardController@index | Customer/Dashboard/Index.vue | ✅ EXISTS |

### ✅ Products Catalog

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/customer/products` | ProductController@index | Customer/Products/Index.vue | ✅ EXISTS |
| GET | `/customer/products/{slug}` | ProductController@show | Customer/Products/Show.vue | ❌ MISSING |

### ❌ Subscriptions Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/customer/subscriptions` | SubscriptionController@index | Customer/Subscriptions/Index.vue | ❌ MISSING |
| GET | `/customer/subscriptions/create` | SubscriptionController@create | Customer/Subscriptions/Create.vue | ❌ MISSING |
| POST | `/customer/subscriptions` | SubscriptionController@store | → customer.payments.store | ✅ API |
| GET | `/customer/subscriptions/{subscription}` | SubscriptionController@show | Customer/Subscriptions/Show.vue | ❌ MISSING |
| POST | `/customer/subscriptions/{subscription}/cancel` | SubscriptionController@cancel | → JSON Response | ✅ API |
| POST | `/customer/subscriptions/{subscription}/renew` | SubscriptionController@renew | → JSON Response | ✅ API |

### ❌ Payments Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/customer/payments` | PaymentController@index | Customer/Payments/Index.vue | ❌ MISSING |
| POST | `/customer/payments` | PaymentController@store | → Midtrans Redirect | ✅ API |
| GET | `/customer/payments/{payment}` | PaymentController@show | Customer/Payments/Show.vue | ❌ MISSING |

### ❌ Invoices Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/customer/invoices` | InvoiceController@index | Customer/Invoices/Index.vue | ❌ MISSING |
| GET | `/customer/invoices/{invoice}` | InvoiceController@show | Customer/Invoices/Show.vue | ❌ MISSING |
| GET | `/customer/invoices/{invoice}/download` | InvoiceController@download | → PDF Download | ✅ API |

### ⚠️ Licenses Management

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/customer/licenses` | LicenseController@index | Customer/Licenses/Index.vue | ✅ EXISTS |
| GET | `/customer/licenses/{license}` | LicenseController@show | Customer/Licenses/Show.vue | ❌ MISSING |
| POST | `/customer/licenses/{license}/activate` | LicenseController@activate | → JSON Response | ✅ API |
| GET | `/customer/licenses/{license}/credentials` | LicenseController@credentials | Customer/Licenses/Credentials.vue | ❌ MISSING |

### ❌ Reviews Management (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| POST | `/customer/reviews` | ReviewController@store | → JSON Response | ✅ API |
| GET | `/customer/my-reviews` | ReviewController@index | Customer/Reviews/Index.vue | ❌ MISSING |
| PUT | `/customer/reviews/{review}` | ReviewController@update | → JSON Response | ✅ API |
| DELETE | `/customer/reviews/{review}` | ReviewController@destroy | → JSON Response | ✅ API |

### ❌ Profile Settings (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/customer/profile` | ProfileController@edit | Customer/Profile/Edit.vue | ❌ MISSING |
| PUT | `/customer/profile` | ProfileController@update | → JSON Response | ✅ API |
| PUT | `/customer/profile/password` | ProfileController@updatePassword | → JSON Response | ✅ API |

---

## 🤝 4. AFFILIATOR PANEL ROUTES

### ✅ Dashboard

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/affiliator/dashboard` | DashboardController@index | Affiliator/Dashboard/Index.vue | ✅ EXISTS |

### ⚠️ Referrals Management

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/affiliator/referrals` | ReferralController@index | Affiliator/Referrals/Index.vue | ✅ EXISTS |
| GET | `/affiliator/referrals/stats` | ReferralController@stats | → JSON Response | ✅ API |
| GET | `/affiliator/referrals/{customer}` | ReferralController@show | Affiliator/Referrals/Show.vue | ❌ MISSING |

### ⚠️ Commissions Tracking

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/affiliator/commissions` | CommissionController@index | Affiliator/Commissions/Index.vue | ✅ EXISTS |
| GET | `/affiliator/commissions/stats` | CommissionController@stats | → JSON Response | ✅ API |
| GET | `/affiliator/commissions/{commission}` | CommissionController@show | Affiliator/Commissions/Show.vue | ❌ MISSING |
| GET | `/affiliator/commissions/export` | CommissionController@export | → CSV/Excel Download | ✅ API |

### ❌ Downline Network (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/affiliator/downlines` | DownlineController@index | Affiliator/Downlines/Index.vue | ❌ MISSING |
| GET | `/affiliator/downlines/tree` | DownlineController@tree | Affiliator/Downlines/Tree.vue | ❌ MISSING |
| GET | `/affiliator/downlines/{affiliator}` | DownlineController@show | Affiliator/Downlines/Show.vue | ❌ MISSING |
| GET | `/affiliator/downlines/stats` | DownlineController@stats | → JSON Response | ✅ API |

### ⚠️ Withdrawals Requests

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/affiliator/withdrawals` | WithdrawalController@index | Affiliator/Withdrawals/Index.vue | ✅ EXISTS |
| POST | `/affiliator/withdrawals` | WithdrawalController@store | → affiliator.withdrawals.index | ✅ API |
| GET | `/affiliator/withdrawals/create` | WithdrawalController@create | Affiliator/Withdrawals/Create.vue | ❌ MISSING |
| GET | `/affiliator/withdrawals/{withdrawal}` | WithdrawalController@show | Affiliator/Withdrawals/Show.vue | ❌ MISSING |
| GET | `/affiliator/withdrawals/history` | WithdrawalController@history | Affiliator/Withdrawals/History.vue | ❌ MISSING |

### ❌ Reviews (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/affiliator/reviews` | ReviewController@index | Affiliator/Reviews/Index.vue | ❌ MISSING |
| POST | `/affiliator/reviews` | ReviewController@store | → JSON Response | ✅ API |
| GET | `/affiliator/reviews/my-reviews` | ReviewController@myReviews | Affiliator/Reviews/MyReviews.vue | ❌ MISSING |

### ❌ Profile Settings (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/affiliator/profile` | ProfileController@edit | Affiliator/Profile/Edit.vue | ❌ MISSING |
| PUT | `/affiliator/profile` | ProfileController@update | → JSON Response | ✅ API |
| PUT | `/affiliator/profile/bank-account` | ProfileController@updateBankAccount | → JSON Response | ✅ API |
| PUT | `/affiliator/profile/password` | ProfileController@updatePassword | → JSON Response | ✅ API |

### ❌ Marketing Materials (ALL VIEWS MISSING)

| Method | URI | Controller | View | Status |
|--------|-----|------------|------|--------|
| GET | `/affiliator/marketing-materials` | MarketingController@index | Affiliator/MarketingMaterials/Index.vue | ❌ MISSING |
| GET | `/affiliator/marketing-materials/banners` | MarketingController@banners | Affiliator/MarketingMaterials/Banners.vue | ❌ MISSING |
| GET | `/affiliator/marketing-materials/links` | MarketingController@links | Affiliator/MarketingMaterials/Links.vue | ❌ MISSING |

---

## 🔌 5. API ROUTES (License Server & Webhooks)

### ✅ License Validation API

| Method | URI | Controller | Response | Status |
|--------|-----|------------|----------|--------|
| POST | `/api/v1/license/validate` | LicenseValidationController@validate | JSON | ✅ EXISTS |
| POST | `/api/v1/license/heartbeat` | LicenseValidationController@heartbeat | JSON | ✅ EXISTS |

### ✅ Payment Webhooks

| Method | URI | Controller | Response | Status |
|--------|-----|------------|----------|--------|
| POST | `/api/v1/webhook/midtrans` | MidtransWebhookController@handle | JSON | ✅ EXISTS |
| POST | `/api/v1/webhook/midtrans/notification` | MidtransWebhookController@notification | JSON | ✅ EXISTS |

### ✅ Health Check

| Method | URI | Controller | Response | Status |
|--------|-----|------------|----------|--------|
| GET | `/health` | Closure | JSON | ✅ EXISTS |

---

## 📋 6. SUMMARY - FILES YANG PERLU DIGENERATE

### 🎯 Priority 1: Core Business Flow (CRITICAL)

#### Admin Panel (8 files)
1. `Admin/Products/Create.vue` - Form tambah produk
2. `Admin/Products/Show.vue` - Detail produk
3. `Admin/Products/Edit.vue` - Form edit produk
4. `Admin/Customers/Show.vue` - Detail customer + aktivasi lisensi
5. `Admin/Affiliators/Show.vue` - Detail affiliator + downline
6. `Admin/Licenses/Show.vue` - Detail lisensi + generate token
7. `Admin/Vouchers/Create.vue` - Form buat voucher
8. `Admin/Vouchers/Edit.vue` - Form edit voucher

#### Customer Panel (10 files)
9. `Customer/Products/Show.vue` - Detail produk + order
10. `Customer/Subscriptions/Index.vue` - List subscription aktif
11. `Customer/Subscriptions/Create.vue` - Form pilih paket subscription
12. `Customer/Subscriptions/Show.vue` - Detail subscription
13. `Customer/Payments/Index.vue` - Riwayat pembayaran
14. `Customer/Payments/Show.vue` - Detail pembayaran + status
15. `Customer/Invoices/Index.vue` - List invoice
16. `Customer/Invoices/Show.vue` - Detail invoice + download
17. `Customer/Licenses/Show.vue` - Detail lisensi + activate button
18. `Customer/Licenses/Credentials.vue` - Modal license code + token

#### Affiliator Panel (7 files)
19. `Affiliator/Referrals/Show.vue` - Detail referral customer
20. `Affiliator/Commissions/Show.vue` - Detail komisi
21. `Affiliator/Withdrawals/Create.vue` - Form request withdrawal
22. `Affiliator/Withdrawals/Show.vue` - Detail withdrawal status
23. `Affiliator/Withdrawals/History.vue` - History semua withdrawal
24. `Affiliator/Downlines/Index.vue` - List downline level 1
25. `Affiliator/Downlines/Tree.vue` - Visual tree downline

### 🎯 Priority 2: Admin Management Tools (HIGH)

26. `Admin/Subscriptions/Index.vue` - Monitor semua subscription
27. `Admin/Subscriptions/Show.vue` - Detail subscription customer
28. `Admin/Transactions/Index.vue` - List semua transaksi
29. `Admin/Transactions/Show.vue` - Detail transaksi + refund
30. `Admin/Vouchers/Show.vue` - Detail voucher usage
31. `Admin/Settlements/Show.vue` - Detail withdrawal request + approve/reject

### 🎯 Priority 3: Content & Support (MEDIUM)

32. `Admin/Blog/Index.vue` - List blog posts
33. `Admin/Blog/Create.vue` - Form tulis blog
34. `Admin/Blog/Edit.vue` - Edit blog post
35. `Admin/Cms/Pages/Index.vue` - List landing pages
36. `Admin/Cms/Pages/Create.vue` - Form buat page
37. `Admin/Cms/Pages/Edit.vue` - Edit page
38. `Admin/EmailCampaigns/Index.vue` - List email campaigns
39. `Admin/EmailCampaigns/Create.vue` - Form buat campaign
40. `Admin/EmailCampaigns/Show.vue` - Detail campaign stats
41. `Admin/Tickets/Index.vue` - List support tickets
42. `Admin/Tickets/Show.vue` - Detail ticket + reply
43. `Admin/Reviews/Index.vue` - Moderasi reviews
44. `Admin/Settings/Index.vue` - System settings

### 🎯 Priority 4: Customer & Affiliator Experience (LOW)

45. `Customer/Reviews/Index.vue` - My reviews
46. `Customer/Profile/Edit.vue` - Edit profil customer
47. `Affiliator/Reviews/Index.vue` - Reviews dari referral
48. `Affiliator/Reviews/MyReviews.vue` - My reviews
49. `Affiliator/Profile/Edit.vue` - Edit profil + bank account
50. `Affiliator/MarketingMaterials/Index.vue` - Marketing dashboard
51. `Affiliator/MarketingMaterials/Banners.vue` - Banner gallery
52. `Affiliator/MarketingMaterials/Links.vue` - Referral links

---

## 🔄 7. REDIRECT FLOW DIAGRAM

### Customer Registration & Activation Flow
```
GET /customer/register 
  → showCustomerRegister() 
  → [auth.customer.register.blade.php]
  
POST /customer/register 
  → customerRegister() 
  → JSON {success} 
  → [Frontend redirect] → GET /customer/dashboard
  
GET /customer/dashboard 
  → DashboardController@index() 
  → [Customer/Dashboard/Index.vue]
  
[Select Product] 
  → GET /customer/products/{slug} 
  → [Customer/Products/Show.vue] (MISSING)
  
[Choose Subscription] 
  → POST /customer/subscriptions 
  → SubscriptionController@store() 
  → Create pending subscription
  
[Payment] 
  → POST /customer/payments 
  → PaymentController@store() 
  → Redirect to Midtrans
  
[Payment Success] 
  → Webhook: POST /api/v1/webhook/midtrans 
  → MidtransWebhookController@handle() 
  → Update subscription status = active 
  → Generate License (16 digit) + Token (16 digit) 
  → Send Email: license-ready.blade.php 
  → Send WA via Fonnte
  
[Customer Receives] 
  → GET /customer/licenses 
  → [Customer/Licenses/Index.vue] 
  → Click "View Credentials" 
  → GET /customer/licenses/{license}/credentials 
  → [Customer/Licenses/Credentials.vue] (MISSING) 
  → Show License Code + Token Code
```

### Admin License Approval Flow
```
GET /admin/customers 
  → [Admin/Customers/Index.vue]
  
Click Customer 
  → GET /admin/customers/{customer} 
  → [Admin/Customers/Show.vue] (MISSING)
  
[Manual Setup ERP] 
  → Connect domain/subdomain 
  → Generate License Code (16 digit) 
  → Generate Token Code (16 digit)
  
[Activate in System] 
  → POST /admin/licenses/{license}/activate 
  → LicenseController@activate() 
  → Update license status = active 
  → Send notifications
  
[Confirm Ready] 
  → Email: info@cooca.id → customer 
  → WA: Fonnte API → customer
```

### Affiliate Commission Flow
```
[Affiliator shares link] 
  → https://cooca.id/customer/register?ref=AFF_CODE
  
[Customer registers with ref code] 
  → Store: customer.affiliator_id = AFF_ID
  
[Customer pays subscription] 
  → Payment success webhook 
  → Calculate commission: 25% of gross 
  → Create Commission record 
  → Email: commission-received.blade.php
  
[Affiliator views commission] 
  → GET /affiliator/commissions 
  → [Affiliator/Commissions/Index.vue]
  
[Request Withdrawal] 
  → GET /affiliator/withdrawals/create 
  → [Affiliator/Withdrawals/Create.vue] (MISSING) 
  → POST /affiliator/withdrawals 
  → WithdrawalController@store() 
  → Create withdrawal request
  
[Admin approves] 
  → GET /admin/settlements 
  → [Admin/Settlements/Index.vue] 
  → POST /admin/settlements/{settlement}/approve 
  → Deduct balance 
  → Transfer via Bank/E-wallet 
  → Email: withdrawal-approved.blade.php
```

---

## 📊 8. COMPLETION STATUS BY MODULE

| Module | Total Views | Existing | Missing | % Complete |
|--------|-------------|----------|---------|------------|
| **Public Pages** | 7 | 7 | 0 | 100% ✅ |
| **Auth Pages (Blade)** | 5 | 5 | 0 | 100% ✅ |
| **Admin Dashboard** | 1 | 1 | 0 | 100% ✅ |
| **Admin Products** | 4 | 1 | 3 | 25% ⚠️ |
| **Admin Customers** | 2 | 1 | 1 | 50% ⚠️ |
| **Admin Affiliators** | 2 | 1 | 1 | 50% ⚠️ |
| **Admin Licenses** | 2 | 1 | 1 | 50% ⚠️ |
| **Admin Subscriptions** | 2 | 0 | 2 | 0% ❌ |
| **Admin Transactions** | 2 | 0 | 2 | 0% ❌ |
| **Admin Vouchers** | 4 | 1 | 3 | 25% ⚠️ |
| **Admin Settlements** | 2 | 1 | 1 | 50% ⚠️ |
| **Admin CMS** | 3 | 0 | 3 | 0% ❌ |
| **Admin Blog** | 3 | 0 | 3 | 0% ❌ |
| **Admin Email Campaigns** | 3 | 0 | 3 | 0% ❌ |
| **Admin Tickets** | 2 | 0 | 2 | 0% ❌ |
| **Admin Reviews** | 1 | 0 | 1 | 0% ❌ |
| **Admin Settings** | 1 | 0 | 1 | 0% ❌ |
| **Customer Dashboard** | 1 | 1 | 0 | 100% ✅ |
| **Customer Products** | 2 | 1 | 1 | 50% ⚠️ |
| **Customer Subscriptions** | 3 | 0 | 3 | 0% ❌ |
| **Customer Payments** | 2 | 0 | 2 | 0% ❌ |
| **Customer Invoices** | 2 | 0 | 2 | 0% ❌ |
| **Customer Licenses** | 3 | 1 | 2 | 33% ⚠️ |
| **Customer Reviews** | 1 | 0 | 1 | 0% ❌ |
| **Customer Profile** | 1 | 0 | 1 | 0% ❌ |
| **Affiliator Dashboard** | 1 | 1 | 0 | 100% ✅ |
| **Affiliator Referrals** | 2 | 1 | 1 | 50% ⚠️ |
| **Affiliator Commissions** | 2 | 1 | 1 | 50% ⚠️ |
| **Affiliator Downlines** | 3 | 0 | 3 | 0% ❌ |
| **Affiliator Withdrawals** | 3 | 1 | 2 | 33% ⚠️ |
| **Affiliator Reviews** | 2 | 0 | 2 | 0% ❌ |
| **Affiliator Profile** | 1 | 0 | 1 | 0% ❌ |
| **Affiliator Marketing** | 3 | 0 | 3 | 0% ❌ |
| **API Endpoints** | 5 | 5 | 0 | 100% ✅ |
| **TOTAL** | **75** | **27** | **48** | **36%** |

---

## 🚀 9. RECOMMENDED GENERATION ORDER

### Phase 1: Critical Path (Week 1-2)
**Goal**: Enable complete customer journey from registration to license activation

1. Customer/Products/Show.vue
2. Customer/Subscriptions/Create.vue
3. Customer/Payments/Index.vue
4. Customer/Licenses/Credentials.vue
5. Admin/Customers/Show.vue
6. Admin/Licenses/Show.vue
7. Admin/Settlements/Show.vue

### Phase 2: Admin Operations (Week 3-4)
**Goal**: Enable full admin management capabilities

8. Admin/Products/Create.vue
9. Admin/Products/Edit.vue
10. Admin/Vouchers/Create.vue
11. Admin/Vouchers/Edit.vue
12. Admin/Subscriptions/Index.vue
13. Admin/Transactions/Index.vue
14. Admin/Reviews/Index.vue

### Phase 3: Affiliator Experience (Week 5)
**Goal**: Enable affiliate marketing and withdrawal flow

15. Affiliator/Withdrawals/Create.vue
16. Affiliator/Downlines/Index.vue
17. Affiliator/MarketingMaterials/Index.vue
18. Affiliator/Profile/Edit.vue

### Phase 4: Content & Support (Week 6)
**Goal**: Enable content management and customer support

19. Admin/Blog/Index.vue
20. Admin/Cms/Pages/Index.vue
21. Admin/Tickets/Index.vue
22. Admin/EmailCampaigns/Index.vue

### Phase 5: Polish & Optimization (Week 7-8)
**Goal**: Complete remaining views and optimize UX

23-48. All remaining views

---

## 📝 10. TECHNICAL NOTES

### Authentication Strategy
- **Admin**: Session-based + Blade templates for login
- **Customer**: JWT Token + Inertia.js (Vue 3)
- **Affiliator**: JWT Token + Inertia.js (Vue 3)
- **Google OAuth**: Laravel Socialite with custom guards

### Middleware Stack
```php
// web.php
'web' => [
    \Illuminate\\Cookie\\Middleware\\EncryptCookies::class,
    \Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse::class,
    \Illuminate\\Session\\Middleware\\StartSession::class,
    \Illuminate\\View\\Middleware\\ShareErrorsFromSession::class,
    \Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken::class,
    \Illuminate\\Routing\\Middleware\\SubstituteBindings::class,
]

// admin.php
'auth:admin' => \App\\Http\\Middleware\\AuthenticateAdmin::class

// customer.php
'auth:customer' => \App\\Http\\Middleware\\AuthenticateCustomer::class

// affiliator.php
'auth:affiliator' => \App\\Http\\Middleware\\AuthenticateAffiliator::class

// api.php
'throttle:api' => \Illuminate\\Routing\\Middleware\\ThrottleRequests::class.':api'
```

### Inertia.js Shared Data
```javascript
{
  auth: { user, guard },
  flash: { success, error, warning },
  config: { app_name, currency, timezone }
}
```

### API Response Format
```json
{
  "success": true,
  "message": "Operation completed",
  "data": {},
  "meta": {
    "timestamp": "2026-06-12T16:00:00Z",
    "version": "1.0.0"
  }
}
```

---

## ✅ CONCLUSION

Platform Cooca.id memiliki **arsitektur backend yang solid** dengan:
- ✅ Multi-guard authentication system
- ✅ Complete RESTful API structure
- ✅ Proper route organization by role
- ✅ Middleware protection di semua endpoint

**Yang perlu diselesaikan**:
- ❌ 48 Vue components untuk frontend (60% dari total)
- ⚠️ Prioritas pada customer flow dan admin approval process
- 📅 Estimasi 6-8 minggu untuk completion dengan 1-2 developers

**Rekomendasi**: Fokus pada **Phase 1 (Critical Path)** terlebih dahulu untuk memungkinkan end-to-end testing dari customer registration hingga license activation.
