<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Cooca.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="relative inline-block">
            <div class="text-9xl font-extrabold tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-amber-500 via-orange-500 to-yellow-400 opacity-80">
                403
            </div>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-bold text-white">Akses Ditolak</h1>
            <p class="text-slate-400 text-sm">
                Anda tidak memiliki izin atau hak akses untuk membuka halaman ini.
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-medium text-sm transition-all shadow-lg shadow-blue-500/25">
                Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-sm border border-slate-700 transition-all">
                Kembali
            </a>
        </div>

        <div class="pt-8 border-t border-slate-800 text-slate-500 text-xs">
            &copy; {{ date('Y') }} Cooca.id — All rights reserved.
        </div>
    </div>
</body>
</html>
