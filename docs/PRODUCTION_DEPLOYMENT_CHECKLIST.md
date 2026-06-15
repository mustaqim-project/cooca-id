# COOCA.ID Production Deployment Checklist

## Pre-Deployment Requirements

### 1. Environment Configuration
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Generate new APP_KEY (never use development key)
- [ ] Configure production database credentials
- [ ] Set production Midtrans server key (not sandbox)
- [ ] Configure WhatsApp service credentials
- [ ] Set up email SMTP configuration
- [ ] Configure queue driver (redis/database)
- [ ] Set session driver to database or redis

### 2. Database Setup
- [ ] Run all migrations: php artisan migrate --force
- [ ] Seed initial settings: php artisan db:seed --class=SettingsSeeder
- [ ] Verify all foreign keys are created
- [ ] Create database indexes (included in migrations)
- [ ] Test database connection from application server

### 3. Queue and Job Processing
- [ ] Install and configure Redis (recommended) or database queue
- [ ] Set up Supervisor for queue workers
- [ ] Configure failed jobs table: php artisan queue:failed-table
- [ ] Test job processing with test notification

### 4. Scheduler Setup
- [ ] Add cron entry for Laravel scheduler
- [ ] Verify scheduled tasks run correctly

### 5. Storage and Assets
- [ ] Create storage link: php artisan storage:link
- [ ] Build frontend assets: npm run build
- [ ] Cache config: php artisan config:cache
- [ ] Cache routes: php artisan route:cache
- [ ] Clear old caches: php artisan optimize:clear

### 6. Security Hardening
- [ ] Enable HTTPS/SSL certificate
- [ ] Configure security headers in web server
- [ ] Set up rate limiting for API endpoints
- [ ] Enable CSRF protection
- [ ] Restrict admin panel access by IP (optional)
- [ ] Review file permissions (755 for directories, 644 for files)
- [ ] Ensure .env file is not web-accessible

### 7. Backup Strategy
- [ ] Configure automated daily database backups
- [ ] Set up offsite backup storage (S3, Google Cloud Storage)
- [ ] Test backup restoration procedure
- [ ] Document backup retention policy (minimum 30 days)
- [ ] Set up backup monitoring alerts

### 8. Monitoring and Alerting
- [ ] Set up application error tracking (Sentry, Bugsnag)
- [ ] Configure uptime monitoring
- [ ] Set up server resource monitoring (CPU, Memory, Disk)
- [ ] Configure log aggregation (ELK Stack, Papertrail)
- [ ] Create alert rules for critical errors
- [ ] Set up payment failure alerts

### 9. Performance Optimization
- [ ] Enable OPcache for PHP
- [ ] Configure database query caching
- [ ] Set up CDN for static assets
- [ ] Enable HTTP/2
- [ ] Configure browser caching headers
- [ ] Run performance tests (load testing)

### 10. Testing Before Go-Live
- [ ] End-to-end test: Customer registration to Trial request to Payment to Subscription activation
- [ ] Test webhook handling with Midtrans sandbox
- [ ] Verify email notifications are sent
- [ ] Verify WhatsApp notifications are sent
- [ ] Test affiliate commission calculation
- [ ] Test admin approval workflow
- [ ] Test license generation and activation
- [ ] Verify audit logs are recorded
- [ ] Test failed payment scenarios
- [ ] Test subscription expiration flow

## Deployment Steps

### Step 1: Code Deployment
cd /var/www/cooca
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build

### Step 2: Database Migration
php artisan migrate --force

### Step 3: Cache Optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

### Step 4: Queue Worker Restart
php artisan queue:restart

### Step 5: Supervisor Configuration
Create /etc/supervisor/conf.d/cooca-worker.conf with proper worker settings

### Step 6: Cron Setup
Add cron entry for Laravel scheduler

### Step 7: Storage Permissions
Set proper ownership and permissions for storage and cache directories

## Post-Deployment Verification

### Immediate Checks (First 30 minutes)
- [ ] Homepage loads correctly
- [ ] Login/Register pages work
- [ ] Admin panel accessible
- [ ] No errors in application logs
- [ ] Queue worker is running
- [ ] Scheduler is executing

### First Day Checks
- [ ] Monitor error rate (should be less than 0.1 percent)
- [ ] Check queue processing time
- [ ] Verify backup completed successfully
- [ ] Review slow query logs
- [ ] Check disk space usage

### First Week Checks
- [ ] Analyze user feedback
- [ ] Review performance metrics
- [ ] Check conversion funnel (registration to payment)
- [ ] Monitor affiliate commission calculations
- [ ] Verify all notifications are delivered

## Rollback Procedure

If critical issues occur:

1. Stop queue workers
2. Rollback code to previous commit
3. Rollback database if needed
4. Clear caches
5. Restart services

---

Last Updated: 2026-06-15
Version: 1.0.0
