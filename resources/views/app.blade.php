<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>@yield('title', 'Cooca.id - SaaS ERP Platform')</title>
    <meta name="description" content="@yield('meta_description', 'Platform SaaS ERP multi-tenant untuk berbagai industri dengan sistem lisensi dan affiliate')">
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="bg-white antialiased">
    @inertia
</body>
</html>
