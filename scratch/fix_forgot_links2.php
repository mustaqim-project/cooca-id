<?php
$files = [
    'customer' => 'c:/laragon/www/cooca-id/resources/views/auth/customer/login.blade.php',
    'affiliator' => 'c:/laragon/www/cooca-id/resources/views/auth/affiliator/login.blade.php',
    'admin' => 'c:/laragon/www/cooca-id/resources/views/auth/admin/login.blade.php'
];
foreach($files as $role => $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('<a href="#" style="font-size:.82rem;">Forgot password?</a>', '<a href="{{ route(\'' . $role . '.password.request\') }}" style="font-size:.82rem;">Forgot password?</a>', $content);
        file_put_contents($file, $content);
    }
}
echo "Done fixing links.";
