<?php
$roles = ['customer', 'affiliator', 'admin'];

foreach($roles as $role) {
    $loginContent = file_get_contents("c:/laragon/www/cooca-id/resources/views/auth/{$role}/login.blade.php");

    // === 1. FORGOT PASSWORD ===
    $forgotHtml = $loginContent;
    // Replace Route
    $forgotHtml = preg_replace('/action="\{\{ route\(\'.*?\.login\.submit\'\) \}\}"/', 'action="{{ route(\''.$role.'.password.email\') }}"', $forgotHtml);
    // Remove CSRF -> add CSRF + hidden token? No, forgot password only needs csrf.
    
    // Replace titles
    $forgotHtml = preg_replace('/<div class="form-title">.*?<\/div>/', '<div class="form-title">Reset Password</div>', $forgotHtml);
    $forgotHtml = preg_replace('/<p class="form-subtitle">.*?<\/p>/s', '<p class="form-subtitle">Enter your email and we will send you a reset link.</p>', $forgotHtml);
    
    // Remove password input wrap
    $forgotHtml = preg_replace('/<div class="input-wrap">\s*<div class="d-flex justify-content-between align-items-center">.*?<label class="input-label">Password<\/label>.*?<\/div>\s*<div class="position-relative">.*?<i class="bi bi-lock input-icon">.*?<\/div>\s*<\/div>/s', '', $forgotHtml);
    
    // Remove remember me
    $forgotHtml = preg_replace('/<div class="check-wrap">.*?<\/div>/s', '', $forgotHtml);
    
    // Change Button
    $forgotHtml = preg_replace('/<button type="submit" class="btn-submit">.*?<\/button>/s', '<button type="submit" class="btn-submit">Send Reset Link <i class="bi bi-arrow-right"></i></button>', $forgotHtml);

    // Remove bottom link if any
    $forgotHtml = preg_replace('/<p class="text-center mt-4">.*?<\/p>/s', '<p class="text-center mt-4"><a href="{{ route(\''.$role.'.login\') }}" class="fw-bold">← Back to Login</a></p>', $forgotHtml);

    file_put_contents("c:/laragon/www/cooca-id/resources/views/auth/{$role}/forgot-password.blade.php", $forgotHtml);

    // === 2. RESET PASSWORD ===
    $resetHtml = $loginContent;
    $resetHtml = preg_replace('/action="\{\{ route\(\'.*?\.login\.submit\'\) \}\}"/', 'action="{{ route(\''.$role.'.password.update\') }}"', $resetHtml);
    
    // Titles
    $resetHtml = preg_replace('/<div class="form-title">.*?<\/div>/', '<div class="form-title">Set New Password</div>', $resetHtml);
    $resetHtml = preg_replace('/<p class="form-subtitle">.*?<\/p>/s', '<p class="form-subtitle">Please enter your new password below.</p>', $resetHtml);
    
    // Add token
    $tokenHtml = '<input type="hidden" name="token" value="{{ $request->route(\'token\') }}">';
    $resetHtml = str_replace('@csrf', '@csrf' . "\n" . $tokenHtml, $resetHtml);
    
    // We already have email and password in $resetHtml. We need to add password_confirmation.
    $confirmHtml = <<<HTML
                    <div class="input-wrap mt-3">
                        <label class="input-label">Confirm Password</label>
                        <div class="position-relative">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" name="password_confirmation" class="input-field" placeholder="Confirm new password" required>
                        </div>
                    </div>
HTML;
    $resetHtml = preg_replace('/(<div class="position-relative">\s*<i class="bi bi-lock input-icon">.*?<\/div>\s*<\/div>)/s', "$1\n$confirmHtml", $resetHtml);
    
    // Remove forgot password link from label
    $resetHtml = preg_replace('/<div class="d-flex justify-content-between align-items-center">(\s*)<label class="input-label">Password<\/label>.*?<\/div>/s', '<label class="input-label">New Password</label>', $resetHtml);

    // Remove remember me
    $resetHtml = preg_replace('/<div class="check-wrap">.*?<\/div>/s', '', $resetHtml);
    
    // Change Button
    $resetHtml = preg_replace('/<button type="submit" class="btn-submit">.*?<\/button>/s', '<button type="submit" class="btn-submit">Save Password <i class="bi bi-arrow-right"></i></button>', $resetHtml);
    
    // Remove bottom link if any
    $resetHtml = preg_replace('/<p class="text-center mt-4">.*?<\/p>/s', '', $resetHtml);

    // Make email read-only or just prefilled? prefilled with request email
    $resetHtml = str_replace("value=\"{{ old('email') }}\"", "value=\"{{ old('email', \$request->email) }}\"", $resetHtml);

    file_put_contents("c:/laragon/www/cooca-id/resources/views/auth/{$role}/reset-password.blade.php", $resetHtml);
}

echo "Done all.";
