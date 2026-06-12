<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliator Register - Cooca.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Affiliator</h1>
            <p class="text-gray-600 mt-2">Dapatkan komisi 25% dari setiap referral</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('affiliator.register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       value="{{ old('name') }}" required autofocus>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                <input type="text" name="phone" id="phone" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       required minlength="8">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       required minlength="8">
            </div>

            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" name="agree_terms" required 
                           class="mt-1 mr-2">
                    <span class="text-sm text-gray-600">
                        Saya menyetujui <a href="#" class="text-green-600 hover:text-green-800">Syarat & Ketentuan</a> 
                        dan <a href="#" class="text-green-600 hover:text-green-800">Kebijakan Privasi</a> sebagai Affiliator
                    </span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('affiliator.login') }}" class="text-green-600 hover:text-green-800 text-sm">
                Sudah punya akun? Login
            </a>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
