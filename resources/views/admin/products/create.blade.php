@extends('layouts.admin')

@section('title', 'Create Product')
@section('subtitle', 'Add a new product to your catalog')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Products
        </a>
    </div>

    @if($errors->any())
        <div class="rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
            <ul class="text-sm text-red-700 dark:text-red-400 list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Main Form --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Basic Information --}}
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Description <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="4" required
                                class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Category</label>
                                <select name="category_id" id="category_id"
                                    class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="">Select Category</option>
                                    @foreach(\App\Models\ProductCategory::where('is_active', true)->orderBy('sort_order')->get() as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="is_active" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Status</label>
                                <select name="is_active" id="is_active"
                                    class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="1" selected>Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive / Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Base Pricing & Model --}}
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Base Pricing</h3>
                        <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">Harga dasar product. Pricing plan per durasi ditambahkan di bagian <strong>Pricing Plans</strong> di bawah.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Base Price (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-surface-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="price" id="price" value="{{ old('price', 0) }}" min="0" step="1000"
                                        class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-4 sm:text-sm border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md py-2">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Pricing Model</label>
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="type" value="subscription" {{ old('type','subscription') == 'subscription' ? 'checked' : '' }}
                                            class="form-radio text-primary-600 h-4 w-4 border-surface-300 dark:border-surface-600">
                                        <span class="ml-2 text-sm">Subscription (Plan-based)</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="type" value="one_time" {{ old('type') == 'one_time' ? 'checked' : '' }}
                                            class="form-radio text-primary-600 h-4 w-4 border-surface-300 dark:border-surface-600">
                                        <span class="ml-2 text-sm">One-time Purchase</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ======= PRICING PLANS (Dynamic) ======= --}}
                <div class="corporate-card" x-data="planManager()" x-init="init()">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-surface-900 dark:text-white flex items-center gap-2">
                                <i data-lucide="layers" class="w-5 h-5 text-primary-500"></i> Pricing Plans
                            </h3>
                            <p class="text-xs text-surface-500 dark:text-surface-400 mt-1">
                                Tambahkan plan berlangganan (mis: 1 Bulan, 1 Tahun, Lifetime). Bisa juga ditambahkan setelah product disimpan.
                            </p>
                        </div>
                        <button type="button" @click="addPlan()"
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors">
                            <i data-lucide="plus" class="w-3 h-3"></i> Add Plan
                        </button>
                    </div>

                    <div class="p-4">
                        {{-- Empty state --}}
                        <div x-show="plans.length === 0" class="py-8 text-center text-surface-400 dark:text-surface-500 text-sm">
                            <i data-lucide="layers" class="w-8 h-8 mx-auto mb-2 text-surface-300 dark:text-surface-600"></i>
                            <p>Belum ada plan. Klik <strong>"Add Plan"</strong> atau gunakan template cepat di kanan.</p>
                        </div>

                        {{-- Plans list --}}
                        <div class="space-y-3">
                            <template x-for="(plan, index) in plans" :key="plan.key">
                                <div class="border border-surface-200 dark:border-surface-700 rounded-lg p-4 bg-surface-50 dark:bg-surface-900/40">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-semibold text-surface-600 dark:text-surface-400" x-text="'Plan #' + (index + 1)"></span>
                                        <button type="button" @click="removePlan(index)"
                                            class="text-red-400 hover:text-red-600 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        <div class="col-span-2 md:col-span-1">
                                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Nama Plan <span class="text-red-500">*</span></label>
                                            <input type="text" :name="'plans['+index+'][name]'" x-model="plan.name" required
                                                placeholder="mis: 1 Bulan"
                                                class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Durasi (bulan) <span class="text-red-500">*</span></label>
                                            <input type="number" :name="'plans['+index+'][duration_months]'" x-model="plan.duration_months" min="1" required
                                                placeholder="1"
                                                class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                                            <input type="number" :name="'plans['+index+'][price]'" x-model="plan.price" min="0" step="1000" required
                                                placeholder="500000"
                                                class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Diskon (%)</label>
                                            <input type="number" :name="'plans['+index+'][discount_percent]'" x-model="plan.discount_percent" min="0" max="100" step="0.01"
                                                placeholder="0"
                                                class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-surface-600 dark:text-surface-400 mb-1">Urutan</label>
                                            <input type="number" :name="'plans['+index+'][sort_order]'" x-model="plan.sort_order" min="0"
                                                class="block w-full py-1.5 px-2.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded text-sm focus:ring-primary-500 focus:border-primary-500">
                                        </div>
                                        <div class="flex items-end">
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="hidden" :name="'plans['+index+'][is_active]'" value="0">
                                                <input type="checkbox" :name="'plans['+index+'][is_active]'" value="1" x-model="plan.is_active"
                                                    class="rounded border-surface-300 dark:border-surface-600 text-primary-600 focus:ring-primary-500">
                                                <span class="text-xs text-surface-700 dark:text-surface-300">Aktif</span>
                                            </label>
                                        </div>
                                    </div>
                                    {{-- Preview --}}
                                    <div class="mt-2 text-xs text-surface-500 dark:text-surface-400" x-show="plan.price > 0">
                                        <span x-text="'Rp ' + Number(plan.price).toLocaleString('id-ID')"></span>
                                        <span x-show="plan.discount_percent > 0" class="text-green-600 dark:text-green-400 ml-1" x-text="'(-' + plan.discount_percent + '%)'"></span>
                                        <span class="mx-1">·</span>
                                        <span x-text="plan.duration_months >= 999 ? 'Lifetime' : plan.duration_months + ' bulan'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Template quick-add --}}
                        <div class="mt-4 pt-4 border-t border-surface-200 dark:border-surface-700" x-show="plans.length < 10">
                            <p class="text-xs text-surface-500 dark:text-surface-400 mb-2">Template cepat:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach([
                                    ['label' => '1 Bln', 'months' => 1, 'discount' => 0, 'order' => 0],
                                    ['label' => '3 Bln', 'months' => 3, 'discount' => 5, 'order' => 1],
                                    ['label' => '1 Thn', 'months' => 12, 'discount' => 15, 'order' => 2],
                                    ['label' => 'Lifetime', 'months' => 999, 'discount' => 30, 'order' => 3],
                                ] as $tpl)
                                <button type="button"
                                    @click="addTemplate('{{ $tpl['label'] }}', {{ $tpl['months'] }}, {{ $tpl['discount'] }}, {{ $tpl['order'] }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs border border-surface-200 dark:border-surface-700 rounded hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors text-surface-600 dark:text-surface-400">
                                    <i data-lucide="plus" class="w-3 h-3"></i> {{ $tpl['label'] }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Integration Details --}}
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Integration Details</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="webhook_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Webhook URL <span class="text-surface-400 font-normal">(Opsional)</span></label>
                            <input type="url" name="webhook_url" id="webhook_url" value="{{ old('webhook_url') }}" placeholder="https://your-app.com/api/webhooks/cooca"
                                class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">URL untuk menerima notifikasi saat customer berhasil purchase.</p>
                        </div>
                        <div>
                            <label for="demo_url" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Demo URL <span class="text-surface-400 font-normal">(Opsional)</span></label>
                            <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url') }}" placeholder="https://demo.cooca.id"
                                class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">URL halaman demo yang bisa diakses calon customer.</p>
                        </div>
                    </div>
                </div>

            </div>{{-- /LEFT --}}

            {{-- RIGHT: Actions --}}
            <div class="space-y-4">
                <div class="corporate-card p-6 sticky top-6">
                    <h3 class="text-sm font-semibold text-surface-900 dark:text-white mb-4">Save Product</h3>
                    <div class="flex flex-col gap-3">
                        <button type="submit" form="createProductForm"
                            class="w-full inline-flex justify-center items-center gap-2 py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
                            <i data-lucide="save" class="w-4 h-4"></i> Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                            class="w-full inline-flex justify-center items-center gap-2 py-2 px-4 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 text-surface-700 dark:text-surface-300 text-sm font-medium rounded-md shadow-sm hover:bg-surface-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                    <div class="mt-4 rounded-md bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-3 text-xs text-blue-700 dark:text-blue-400">
                        <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                        Setelah disimpan, Anda akan diarahkan ke halaman edit untuk mengelola pricing plans lebih lanjut.
                    </div>
                </div>

                {{-- Help Card --}}
                <div class="corporate-card p-5">
                    <h4 class="text-xs font-semibold text-surface-700 dark:text-surface-300 mb-2">💡 Tips Pricing Plan</h4>
                    <ul class="space-y-1.5 text-xs text-surface-500 dark:text-surface-400">
                        <li>• Gunakan <strong>999 bulan</strong> untuk plan Lifetime</li>
                        <li>• Plan dengan <strong>diskon lebih besar</strong> akan menarik customer memilih durasi panjang</li>
                        <li>• Urutan tampilan mengikuti kolom <strong>Sort Order</strong></li>
                        <li>• Plan yang tidak aktif tidak akan muncul di halaman publik</li>
                    </ul>
                </div>
            </div>{{-- /RIGHT --}}

        </div>{{-- /grid --}}
    </form>
</div>

@push('scripts')
<script>
function planManager() {
    return {
        plans: [],
        counter: 0,

        init() {
            // Restore old input on validation error
            @if(old('plans'))
                this.plans = @json(old('plans', [])).map((p, i) => ({
                    key: i,
                    name: p.name || '',
                    duration_months: p.duration_months || 1,
                    price: p.price || 0,
                    discount_percent: p.discount_percent || 0,
                    sort_order: p.sort_order || i,
                    is_active: p.is_active !== '0',
                }));
                this.counter = this.plans.length;
            @endif
        },

        addPlan() {
            this.plans.push({
                key: this.counter++,
                name: '',
                duration_months: 1,
                price: 0,
                discount_percent: 0,
                sort_order: this.plans.length,
                is_active: true,
            });
            this.$nextTick(() => lucide.createIcons());
        },

        addTemplate(name, months, discount, order) {
            this.plans.push({
                key: this.counter++,
                name: name,
                duration_months: months,
                price: 0,
                discount_percent: discount,
                sort_order: order,
                is_active: true,
            });
            this.$nextTick(() => lucide.createIcons());
        },

        removePlan(index) {
            this.plans.splice(index, 1);
            // Re-order
            this.plans.forEach((p, i) => p.sort_order = i);
        },
    };
}
</script>
@endpush

@endsection
