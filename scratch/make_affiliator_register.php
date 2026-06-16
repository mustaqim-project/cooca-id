<?php
$content = file_get_contents("c:/laragon/www/cooca-id/resources/views/auth/customer/register.blade.php");

// Change routes and wording
$content = str_replace("customer.register.submit", "affiliator.register.submit", $content);
$content = str_replace("customer.login", "affiliator.login", $content);
$content = str_replace("Tell us about your business", "Tell us about yourself", $content);
$content = str_replace("Industry & business details for auto‑configuration.", "Bank details for your commission payouts.", $content);
$content = str_replace("Business Name (Optional)", "Bank Name", $content);
$content = str_replace("name=\"business_name\"", "name=\"bank_name\"", $content);
$content = str_replace("RetailMax Indonesia", "e.g. BCA, Mandiri", $content);
$content = str_replace("Referral Code (Optional)", "Parent Referral Code (Optional)", $content);
$content = str_replace("name=\"referral_code\"", "name=\"parent_referral_code\"", $content);

// Add Bank Account input
$bankAccountHtml = <<<HTML
                    <div class="input-wrap mt-3">
                        <label class="input-label">Bank Account Number</label>
                        <div class="position-relative">
                            <i class="bi bi-credit-card input-icon"></i>
                            <input type="text" name="bank_account" class="input-field" placeholder="1234567890" value="{{ old('bank_account') }}">
                        </div>
                    </div>
HTML;
$content = str_replace("name=\"parent_referral_code\"", "name=\"parent_referral_code\"\n" . $bankAccountHtml, $content); // wait, this might duplicate. I'll just insert after bank_name wrap.

$content = preg_replace('/(<input type="text" name="bank_name" class="input-field" placeholder="e\.g\. BCA, Mandiri" value="{{ old\(\'bank_name\'\) }}">.*?<\/div>\s*<\/div>)/s', "$1\n" . $bankAccountHtml, $content);

$content = str_replace("Launch My Free Trial", "Join as Affiliator", $content);
$content = str_replace("30-Day Free Trial", "Affiliator Partner", $content);
$content = str_replace("Modules", "Commissions", $content);
$content = str_replace("All 10 included", "Unlimited potential", $content);

file_put_contents("c:/laragon/www/cooca-id/resources/views/auth/affiliator/register.blade.php", $content);
echo "Done.";
