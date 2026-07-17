<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Affiliator Dashboard') - Cooca.id</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-surface-50 text-surface-900 dark:bg-surface-900 dark:text-surface-100 font-sans antialiased flex h-screen overflow-hidden transition-colors duration-200">

    <!-- Sidebar Backdrop -->
    <div x-show="sidebarOpen" style="display: none;" class="fixed inset-0 z-40 bg-surface-900/50 lg:hidden"
        @click="sidebarOpen = false" x-transition.opacity></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 corporate-sidebar transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:inset-auto flex flex-col h-screen shrink-0">

        <!-- Brand -->
        <div class="h-16 flex items-center justify-center border-b border-surface-200 dark:border-surface-700 shrink-0">
            <a href="{{ route('affiliator.dashboard') }}"
                class="text-xl font-bold tracking-tight text-surface-900 dark:text-white">
                Cooca<span class="text-primary-600 dark:text-primary-400">.id</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
            @php
                $navigation = [
                    [
                        'name' => 'Dashboard',
                        'href' => route('affiliator.dashboard'),
                        'icon' => 'bi-house-door',
                        'route_name' => 'affiliator.dashboard',
                    ],
                    [
                        'name' => 'Referrals',
                        'href' => route('affiliator.referrals.index'),
                        'icon' => 'bi-person-plus',
                        'route_name' => 'affiliator.referrals.*',
                    ],
                    [
                        'name' => 'Commissions',
                        'href' => route('affiliator.commissions.index'),
                        'icon' => 'bi-currency-dollar',
                        'route_name' => 'affiliator.commissions.*',
                    ],
                    [
                        'name' => 'Downlines',
                        'href' => route('affiliator.downlines.index'),
                        'icon' => 'bi-people',
                        'route_name' => 'affiliator.downlines.*',
                    ],
                    [
                        'name' => 'Withdrawals',
                        'href' => route('affiliator.withdrawals.index'),
                        'icon' => 'bi-cash-stack',
                        'route_name' => 'affiliator.withdrawals.*',
                    ],
                ];
            @endphp

            <ul class="space-y-1">
                @foreach ($navigation as $item)
                    <li>
                        <a href="{{ $item['href'] }}"
                            class="{{ request()->routeIs($item['route_name']) ? 'corporate-nav-item-active' : 'corporate-nav-item' }}">
                            <i
                                class="bi {{ $item['icon'] }} text-lg mr-3 {{ request()->routeIs($item['route_name']) ? 'text-white' : 'text-surface-400 dark:text-surface-500' }}"></i>
                            {{ $item['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <!-- Bottom Action -->
        <div class="p-4 border-t border-surface-200 dark:border-surface-700">
            <form class="form-confirm-submit" method="POST" action="{{ route('affiliator.logout') }}">
                @csrf
                <button type="submit"
                    class="flex w-full items-center px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5 icon-3d"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden min-w-0">

        <!-- Top Header -->
        <header class="h-16 corporate-header sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-md text-surface-400 hover:text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <i data-lucide="menu" class="w-5 h-5 icon-3d"></i>
                </button>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode"
                    class="p-2 text-surface-400 hover:text-surface-500 dark:hover:text-surface-300 transition-colors">
                    <i class="bi" :class="darkMode ? 'bi-sun' : 'bi-moon'"></i>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center space-x-3 focus:outline-none">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                            {{ substr(auth('affiliator')->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-surface-700 dark:text-surface-200">
                                {{ auth('affiliator')->user()->name ?? 'Affiliator' }}</p>
                            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Rp
                                {{ number_format(auth('affiliator')->user()->balance ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-xs text-surface-500 icon-3d"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-surface-800 rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none"
                        style="display: none;">
                        <a href="{{ route('affiliator.profile.edit') }}"
                            class="block px-4 py-2 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700">Profile</a>
                        <div class="border-t border-surface-100 dark:border-surface-700"></div>
                        <form class="form-confirm-submit" method="POST" action="{{ route('affiliator.logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">Sign
                                out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-surface-50 dark:bg-surface-900 p-4 sm:p-6 lg:p-8">
            <!-- Header section -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-surface-900 dark:text-white">@yield('title')</h1>
                    <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">@yield('subtitle')</p>
                </div>
                @hasSection('actions')
                    <div class="mt-4 sm:mt-0 flex space-x-3">
                        @yield('actions')
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

    @include('components.swal-alert')

    <!-- TinyMCE Initialization for Textareas -->
    <script src="https://cdn.tiny.cloud/1/24k0c573h901jvrr06jiqwo75byzannzhejp9zlo610wriru/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media',
                'searchreplace', 'table', 'visualblocks', 'wordcount',
                'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker',
                'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate',
                'tinymceai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes',
                'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword',
                'exportpdf'
            ],
            toolbar: 'undo redo | tinymceai-chat tinymceai-quickactions tinymceai-review | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
            tinymceai_token_provider: async () => {
                await fetch(
                    `https://demo.api.tiny.cloud/1/24k0c573h901jvrr06jiqwo75byzannzhejp9zlo610wriru/auth/random`, {
                        method: "POST",
                        credentials: "include"
                    });
                return {
                    token: await fetch(
                        `https://demo.api.tiny.cloud/1/24k0c573h901jvrr06jiqwo75byzannzhejp9zlo610wriru/jwt/tinymceai`, {
                            credentials: "include"
                        }).then(r => r.text())
                };
            },
            uploadcare_public_key: '21be699352b39be17fbf',
        });
    </script>

    <script>
        function copyReferralCode(code) {
            navigator.clipboard.writeText(code).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Referral code copied to clipboard',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
