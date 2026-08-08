cd /home/u218101292/domains/cooca.id/public_html
/usr/bin/php artisan schedule:run > /dev/null 2>&1
/usr/bin/php artisan queue:work --stop-when-empty > /dev/null 2>&1