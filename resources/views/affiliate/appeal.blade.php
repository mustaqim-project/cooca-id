<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Suspended - Appeal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in-up border border-gray-200">
        <div class="p-6 bg-red-50 border-b border-red-100 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="alert-triangle" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-bold text-red-800 mb-2">Account Suspended</h1>
            <p class="text-red-600 text-sm">Your affiliator account has been suspended by the administration.</p>
        </div>

        <div class="p-6">
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Suspension Details:</h3>
                <p class="text-sm text-gray-700"><strong>Reason:</strong> {{ $affiliator->suspension_reason_type }}</p>
                @if($affiliator->suspension_reason_notes)
                    <p class="text-sm text-gray-700 mt-1"><strong>Notes:</strong> {{ $affiliator->suspension_reason_notes }}</p>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 border border-green-200 rounded-lg flex items-start">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-semibold">Appeal Submitted</h4>
                        <p class="text-sm mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($affiliator->appealed_at)
                <div class="text-center py-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 mb-3">
                        <i data-lucide="clock" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Appeal Under Review</h3>
                    <p class="text-sm text-gray-500 mt-1">You submitted your appeal on {{ \Carbon\Carbon::parse($affiliator->appealed_at)->format('d M Y, H:i') }}. Please wait for the admin to review it.</p>
                    
                    <form action="{{ route('affiliator.logout') }}" method="POST" class="mt-6">
                        @csrf
                        <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Log out
                        </button>
                    </form>
                </div>
            @else
                <form action="{{ route('affiliator.appeal.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="appeal_reason" class="block text-sm font-medium text-gray-700">Why should your account be reactivated? <span class="text-red-500">*</span></label>
                            <textarea id="appeal_reason" name="appeal_reason" rows="4" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Please provide your reason or defense here..."></textarea>
                            @error('appeal_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Upload Proof (Optional)</label>
                            <p class="text-xs text-gray-500 mb-2">You can attach an image (JPG, PNG) as proof.</p>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition-colors cursor-pointer" onclick="document.getElementById('appeal_proof').click()">
                                <div class="space-y-1 text-center">
                                    <i data-lucide="upload-cloud" class="mx-auto h-12 w-12 text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="appeal_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="appeal_proof" name="appeal_proof" type="file" class="sr-only" accept="image/*" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500" id="file-name">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            @error('appeal_proof')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('affiliator.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-sm text-gray-600 hover:text-gray-900">
                            Log out instead
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Submit Appeal
                        </button>
                    </div>
                </form>

                <form id="logout-form" action="{{ route('affiliator.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endif
        </div>
    </div>
    
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
