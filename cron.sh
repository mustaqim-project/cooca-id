cd /home/u218101292/domains/cooca.id/public_html
/opt/alt/php83/usr/bin/php artisan schedule:run > /dev/null 2>&1
/opt/alt/php83/usr/bin/php artisan queue:work --stop-when-empty > /dev/null 2>&1
/opt/alt/php83/usr/bin/php artisan payments:expire-pending > /dev/null 2>&1