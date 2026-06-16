<?php
$content = file_get_contents("c:/laragon/www/cooca-id/resources/views/auth/customer/login.blade.php");
$content = str_replace("Customer Login", "Admin Login", $content);
$content = str_replace("customer.login.submit", "admin.login.submit", $content);
$content = str_replace("customer.register", "admin.login", $content);
$content = str_replace("Your Business Runs Better When You <span class=\"text-gradient\">Own the System.</span>", "Master Control <span class=\"text-gradient\">Admin Portal.</span>", $content);
$content = str_replace("Welcome back. Your isolated business infrastructure is ready and waiting.", "Access the core management system to oversee all tenants and affiliators.", $content);
$content = str_replace("Start 30-day free trial ?", "Back to Portal", $content);
$content = str_replace("Log in to your COOCA dashboard.", "Log in to COOCA Administration.", $content);
$content = str_replace("<a href=\"{{ route(\'admin.login\') }}\">No account? Start free ?</a>", "", $content);
file_put_contents("c:/laragon/www/cooca-id/resources/views/auth/admin/login.blade.php", $content);
echo "Done Admin Login.";
