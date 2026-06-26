@extends('layouts.admin')

@section('title', 'Edit Product')
@section('subtitle', 'Update details for ' . $product->name)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Products
        </a>
        <a href="{{ route('products.show', $product->slug) }}" target="_blank"
           class="inline-flex items-center gap-1 text-xs text-primary-600 hover:underline">
            <i data-lucide="external-link" class="w-3 h-3"></i> View on site
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4 text-sm text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 text-sm text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- TABS --}}
    <div x-data="{ activeTab: window.location.hash === '#plans' ? 'plans' : 'details' }" x-init="window.addEventListener('hashchange', () => { if(window.location.hash === '#plans') activeTab = 'plans'; })">

        <div class="border-b border-surface-200 dark:border-surface-700 mb-6">
            <nav class="-mb-px flex gap-6">
                <button @click="activeTab = 'details'"
                    :class="activeTab === 'details' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200'"
                    class="pb-3 px-1 border-b-2 text-sm font-medium transition-colors flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i> Product Details
                </button>
                <button @click="activeTab = 'plans'"
                    :class="activeTab === 'plans' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200'"
                    class="pb-3 px-1 border-b-2 text-sm font-medium transition-colors flex items-center gap-2">
                    <i data-lucide="layers" class="w-4 h-4"></i> Pricing Plans
                    <span class="ml-1 inline-flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 text-xs px-2 py-0.5 font-semibold">
                        {{ $plans->count() }}
                    </span>
                </button>
            </nav>
        </div>

        {{-- ===================== TAB: PRODUCT DETAILS ===================== --}}
        <div x-show="activeTab === 'details'" x-cloak>
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="form-confirm-submit">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- LEFT: Main Form --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div class="corporate-card">
                            <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Basic Information</h3>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Product Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Description <span class="text-red-500">*</span></label>
                                    <textarea name="description" id="description" rows="4" required
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('description', $product->description) }}</textarea>
                                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="category_id" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Category</label>
                                        <select name="category_id" id="category_id"
                                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                            <option value="">Select Category</option>
                                            @foreach(\App\Models\ProductCategory::where('is_active', true)->orderBy('sort_order')->get() as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="is_active" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Status</label>
                                        <select name="is_active" id="is_active"
                                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                            <option value="1" {{ old('is_active', $product->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $product->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive / Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pricing Info --}}
                        <div class="corporate-card">
                            <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Base Pricing</h3>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">Harga dasar (base price) digunakan jika tidak ada pricing plan aktif. Pricing plan per durasi diatur di tab <strong>Pricing Plans</strong>.</p>
                            </div>
                            <div class="p-6 space-y-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="price" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Base Price (Rp) <span class="text-red-500">*</span></label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-surface-500 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" name="price" id="price" value="{{ old('price', $product->base_price ?? $product->price ?? 0) }}" required min="0" step="1"
                                                class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-4 sm:text-sm border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md py-2">
                                        </div>
                                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Pricing Model</label>
                                        <div class="mt-2 space-y-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="type" value="one_time" {{ old('type', $product->type ?? 'subscription') == 'one_time' ? 'checked' : '' }}
                                                    class="form-radio text-primary-600 h-4 w-4 border-surface-300 dark:border-surface-600">
                                                <span class="ml-2 text-sm">One-time Purchase</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="type" value="subscription" {{ old('type', $product->type ?? 'subscription') == 'subscription' ? 'checked' : '' }}
                                                    class="form-radio text-primary-600 h-4 w-4 border-surface-300 dark:border-surface-600">
                                                <span class="ml-2 text-sm">Subscription (Plan-based)</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Integration --}}
                        <div class="corporate-card">
                            <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Integration Details</h3>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label for="webhook_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Webhook URL (Optional)</label>
                                    <input type="url" name="webhook_url" id="webhook_url" value="{{ old('webhook_url', $product->webhook_url) }}" placeholder="https://your-app.com/api/webhooks/cooca"
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">URL untuk menerima notifikasi saat customer berhasil melakukan pembelian.</p>
                                </div>
                                <div>
                                    <label for="demo_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Demo URL (Optional)</label>
                                    <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://demo.cooca.id"
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">URL untuk halaman demo produk yang bisa diakses oleh calon customer.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Actions --}}
                    <div class="space-y-4">
                        <div class="corporate-card p-6">
                            <h3 class="text-sm font-semibold text-surface-900 dark:text-white mb-4">Save Changes</h3>
                            <div class="flex flex-col gap-3">
                                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
                                    <i data-lucide="save" class="w-4 h-4"></i> Update Product
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="w-full inline-flex justify-center items-center gap-2 py-2 px-4 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 text-surface-700 dark:text-surface-300 text-sm font-medium rounded-md shadow-sm hover:bg-surface-50 transition-colors">
                                    Cancel
                                </a>
                            </div>
                        </div>

                        {{-- Plan summary preview --}}
                        <div class="corporate-card p-6">
                            <h3 class="text-sm font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
                                <i data-lucide="layers" class="w-4 h-4 text-primary-500"></i> Active Pricing Plans
                            </h3>
                            @forelse($plans->where('is_active', true) as $plan)
                                <div class="flex justify-between items-center py-2 border-b border-surface-100 dark:border-surface-800 last:border-0">
                                    <span class="text-xs text-surface-700 dark:text-surface-300">{{ $plan->name }}</span>
                                    <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-surface-400 dark:text-surface-500">Belum ada pricing plan. <button type="button" @click="activeTab = 'plans'" class="text-primary-500 hover:underline">Tambah sekarang →</button></p>
                            @endforelse
                            @if($plans->count() > 0)
                                <button type="button" @click="activeTab = 'plans'" class="mt-3 text-xs text-primary-600 hover:underline">Kelola semua plan →</button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- ===================== TAB: PRICING PLANS ===================== --}}
        <div x-show="activeTab === 'plans'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: Plans List --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="corporate-card">
                        <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Pricing Plans — {{ $product->name }}</h3>
                                <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">
                                    Setiap plan mewakili satu pilihan durasi berlangganan (mis: 1 Bulan, 1 Tahun). Customer memilih plan saat checkout.
                                </p>
                            </div>
                        </div>

                        @if($plans->isEmpty())
                            <div class="p-12 text-center">
                                <i data-lucide="layers" class="w-12 h-12 text-surface-300 dark:text-surface-600 mx-auto mb-4"></i>
                                <p class="text-surface-500 dark:text-surface-400 text-sm">Belum ada pricing plan untuk produk ini.</p>
                                <p class="text-surface-400 dark:text-surface-500 text-xs mt-1">Gunakan form di kanan untuk menambahkan plan pertama.</p>
                            </div>
                        @else
                            <div class="divide-y divide-surface-200 dark:divide-surface-700">
                                @foreach($plans->sortBy('sort_order') as $plan)
                                <div x-data="{ editing: false }" class="p-5">
                                    {{-- VIEW MODE --}}
                                    <div x-show="!editing" class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            {{-- Status badge --}}
                                            <span class="flex-shrink-0 w-2 h-2 rounded-full {{ $plan->is_active ? 'bg-green-500' : 'bg-surface-300' }}"></span>
                                            <div>
                                                <div class="font-medium text-surface-900 dark:text-white text-sm flex items-center gap-2">
                                                    {{ $plan->name }}
                                                    @if(!$plan->is_active)
                                                        <span class="text-xs bg-surface-100 dark:bg-surface-800 text-surface-500 px-2 py-0.5 rounded">Inactive</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">
                                                    {{ $plan->duration_months }} bulan
                                                    @if($plan->discount_percent > 0)
                                                        · Diskon <span class="text-green-600 dark:text-green-400 font-medium">{{ $plan->discount_percent }}%</span>
                                                    @endif
                                                    · Urutan: {{ $plan->sort_order }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-base font-bold text-primary-600 dark:text-primary-400 whitespace-nowrap">
                                                Rp {{ number_format($plan->price, 0, ',', '.') }}
                                            </span>
                                            <div class="flex items-center gap-1">
                                                <button @click="editing = true" type="button"
                                                    class="p-1.5 text-surface-400 hover:text-primary-600 dark:hover:text-primary-400 rounded transition-colors" title="Edit">
                                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                                </button>
                                                {{-- Toggle active --}}
                                                <form action="{{ route('admin.products.plans.toggle', [$product, $plan]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 text-surface-400 hover:text-yellow-500 rounded transition-colors"
                                                        title="{{ $plan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i data-lucide="{{ $plan->is_active ? 'toggle-right' : 'toggle-left' }}" class="w-4 h-4 {{ $plan->is_active ? 'text-green-500' : '' }}"></i>
                                                    </button>
                                                </form>
                                                {{-- Delete --}}
                                                <form action="{{ route('admin.products.plans.destroy', [$product, $plan]) }}" method="POST"
                                                    onsubmit="return confirm('Hapus plan {{ addslashes($plan->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-surface-400 hover:text-red-500 rounded transition-colors" title="Hapus">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- EDIT MODE (inline) --}}
                                    <div x-show="editing" x-cloak>
                                        <form action="{{ route('admin.products.plans.update', [$product, $plan]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-3">
                                                <div class="col-span-2 md:col-span-1">
                                                    <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Nama Plan</label>
                                                    <input type="text" name="name" value="{{ $plan->name }}" required
                                                        class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Durasi (bulan)</label>
                                                    <input type="number" name="duration_months" value="{{ $plan->duration_months }}" min="1" required
                                                        class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Harga (Rp)</label>
                                                    <input type="number" name="price" value="{{ $plan->price }}" min="0" step="1000" required
                                                        class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Diskon (%)</label>
                                                    <input type="number" name="discount_percent" value="{{ $plan->discount_percent }}" min="0" max="100" step="0.01"
                                                        class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Urutan</label>
                                                    <input type="number" name="sort_order" value="{{ $plan->sort_order }}" min="0"
                                                        class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                                </div>
                                                <div class="flex items-end">
                                                    <label class="inline-flex items-center gap-2 text-sm cursor-pointer">
                                                        <input type="hidden" name="is_active" value="0">
                                                        <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}
                                                            class="rounded border-surface-300 dark:border-surface-600 text-primary-600 focus:ring-primary-500">
                                                        <span class="text-xs text-surface-700 dark:text-surface-300">Aktif</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium rounded transition-colors">
                                                    <i data-lucide="save" class="w-3 h-3"></i> Simpan
                                                </button>
                                                <button @click="editing = false" type="button"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 text-surface-700 dark:text-surface-300 text-xs font-medium rounded hover:bg-surface-50 transition-colors">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Payment Flow Info Card --}}
                    <div class="corporate-card p-5 border-l-4 border-primary-500">
                        <h4 class="text-sm font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
                            <i data-lucide="git-branch" class="w-4 h-4 text-primary-500"></i> Alur Payment Subscribe
                        </h4>
                        <ol class="space-y-2 text-xs text-surface-600 dark:text-surface-400">
                            <li class="flex gap-2"><span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xs">1</span>Customer memilih <strong>Plan</strong> di slider harga halaman produk.</li>
                            <li class="flex gap-2"><span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xs">2</span>Klik <em>"Mulai Berlangganan"</em> → diarahkan ke halaman <strong>Checkout</strong> (bisa input voucher).</li>
                            <li class="flex gap-2"><span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xs">3</span>System membuat <code class="bg-surface-100 dark:bg-surface-800 px-1 rounded">Transaction</code> (status: <em>pending</em>) + redirect ke Midtrans.</li>
                            <li class="flex gap-2"><span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xs">4</span>Midtrans callback → <code class="bg-surface-100 dark:bg-surface-800 px-1 rounded">Transaction</code> update ke <em>paid</em>.</li>
                            <li class="flex gap-2"><span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xs">5</span>System otomatis buat <code class="bg-surface-100 dark:bg-surface-800 px-1 rounded">Subscription</code> + <code class="bg-surface-100 dark:bg-surface-800 px-1 rounded">License</code> dengan masa aktif sesuai <em>duration_months</em>.</li>
                            <li class="flex gap-2"><span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xs">6</span>Customer wajib download <strong>Kontrak PDF</strong> sebelum dapat mengakses layanan.</li>
                            <li class="flex gap-2"><span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 flex items-center justify-center font-bold text-xs">7</span>Jika plan <strong>Lifetime (999 bulan)</strong>, field <code class="bg-surface-100 dark:bg-surface-800 px-1 rounded">expires_at</code> di-set <em>null</em> → tidak pernah expired.</li>
                        </ol>
                    </div>
                </div>

                {{-- RIGHT: Add New Plan Form --}}
                <div class="space-y-4">
                    <div class="corporate-card">
                        <div class="p-5 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                            <h3 class="text-sm font-semibold text-surface-900 dark:text-white">Tambah Plan Baru</h3>
                        </div>
                        <div class="p-5">
                            <form action="{{ route('admin.products.plans.store', $product) }}" method="POST" class="space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-xs font-medium text-surface-700 dark:text-surface-300 mb-1">Nama Plan <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" placeholder="mis: 1 Bulan, 1 Tahun, Lifetime" required
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-surface-700 dark:text-surface-300 mb-1">Durasi (bulan) <span class="text-red-500">*</span></label>
                                    <input type="number" name="duration_months" placeholder="1, 3, 12, 999" min="1" required
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500">
                                    <p class="text-xs text-surface-400 dark:text-surface-500 mt-1">Gunakan <strong>999</strong> untuk Lifetime.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-surface-700 dark:text-surface-300 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" name="price" placeholder="500000" min="0" step="1000" required
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-surface-700 dark:text-surface-300 mb-1">Diskon (%)</label>
                                    <input type="number" name="discount_percent" placeholder="0" min="0" max="100" step="0.01" value="0"
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500">
                                    <p class="text-xs text-surface-400 dark:text-surface-500 mt-1">Opsional, untuk label "Hemat X%" di slider.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-surface-700 dark:text-surface-300 mb-1">Urutan Tampil</label>
                                    <input type="number" name="sort_order" placeholder="0" min="0" value="{{ $plans->count() }}"
                                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500">
                                </div>

                                <div>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1" checked
                                            class="rounded border-surface-300 dark:border-surface-600 text-primary-600 focus:ring-primary-500">
                                        <span class="text-sm text-surface-700 dark:text-surface-300">Aktifkan plan ini</span>
                                    </label>
                                </div>

                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
                                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Plan
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Quick Templates --}}
                    <div class="corporate-card p-5">
                        <h4 class="text-xs font-semibold text-surface-700 dark:text-surface-300 mb-3">Template Cepat</h4>
                        <p class="text-xs text-surface-500 dark:text-surface-400 mb-3">Klik untuk mengisi form otomatis:</p>
                        <div class="space-y-2">
                            @foreach([
                                ['label' => '1 Bulan', 'months' => 1, 'discount' => 0, 'order' => 0],
                                ['label' => '3 Bulan', 'months' => 3, 'discount' => 5, 'order' => 1],
                                ['label' => '1 Tahun', 'months' => 12, 'discount' => 15, 'order' => 2],
                                ['label' => 'Lifetime', 'months' => 999, 'discount' => 30, 'order' => 3],
                            ] as $tpl)
                            <button type="button" onclick="fillTemplate('{{ $tpl['label'] }}', {{ $tpl['months'] }}, {{ $tpl['discount'] }}, {{ $tpl['order'] }})"
                                class="w-full text-left px-3 py-2 text-xs border border-surface-200 dark:border-surface-700 rounded-md hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors text-surface-700 dark:text-surface-300">
                                <span class="font-medium">{{ $tpl['label'] }}</span>
                                <span class="float-right text-surface-400">{{ $tpl['months'] }} bln · {{ $tpl['discount'] }}% off</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>{{-- /TAB Plans --}}

    </div>{{-- /x-data tabs --}}
</div>

@push('scripts')
<script>
function fillTemplate(name, months, discount, order) {
    const form = document.querySelector('[action="{{ route("admin.products.plans.store", $product) }}"]');
    form.querySelector('[name="name"]').value = name;
    form.querySelector('[name="duration_months"]').value = months;
    form.querySelector('[name="discount_percent"]').value = discount;
    form.querySelector('[name="sort_order"]').value = order;
    form.querySelector('[name="price"]').focus();
}
</script>
@endpush

@endsection
