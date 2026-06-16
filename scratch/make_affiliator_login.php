<?php
$content = file_get_contents("c:/laragon/www/cooca-id/resources/views/auth/admin/login.blade.php");
$content = preg_replace("/<a href=.*?No account\?.*?<\/a>/", "", $content);
$content = preg_replace("/Don't have an account\?.*?→<\/a>/", "", $content);
file_put_contents("c:/laragon/www/cooca-id/resources/views/auth/admin/login.blade.php", $content);

// Now for Affiliator Login
$content = file_get_contents("c:/laragon/www/cooca-id/resources/views/auth/customer/login.blade.php");
$content = str_replace("Customer Login", "Affiliator Login", $content);
$content = str_replace("customer.login.submit", "affiliator.login.submit", $content);
$content = str_replace("customer.register", "affiliator.register", $content);
$content = str_replace("Your Business Runs Better When You <span class=\"text-gradient\">Own the System.</span>", "Partner with Us and <span class=\"text-gradient\">Earn Passive Income.</span>", $content);
$content = str_replace("Welcome back. Your isolated business infrastructure is ready and waiting.", "Log in to track your referrals and commissions.", $content);
file_put_contents("c:/laragon/www/cooca-id/resources/views/auth/affiliator/login.blade.php", $content);

echo "Done Affiliator Login.";
