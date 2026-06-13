<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

interface SubscriptionPlan {
    id: number;
    name: string;
    price: number;
    duration_months: number;
    features: string[];
    description: string;
}

interface License {
    id: number;
    license_code: string;
    product_name: string;
    status: string;
}

interface Props {
    plans: SubscriptionPlan[];
    licenses: License[];
}

const props = defineProps<Props>();

const form = useForm({
    subscription_plan_id: '',
    license_id: '',
    started_at: '',
    voucher_code: '',
});

const selectPlan = (planId: number) => {
    form.subscription_plan_id = planId.toString();
};

const submit = () => {
    form.post(route('customer.subscriptions.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Subscribe - Cooca.id" />

    <CustomerLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link
                        :href="route('customer.subscriptions.index')"
                        class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                    >
                        ← Back to Subscriptions
                    </Link>
                    <h2 class="mt-4 text-2xl font-semibold text-gray-900">Choose Your Subscription Plan</h2>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Select License -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">1. Select License</h3>
                        
                        <div v-if="licenses.length === 0" class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a2 2 0 01-2-2v-2.342a6 6 0 01-7.743-5.743L4 7V4a2 2 0 012-2h10a2 2 0 012 2v3z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">Anda belum memiliki lisensi ERP.</p>
                            <p class="mt-1 text-xs text-gray-500">Silakan request trial atau hubungi admin untuk mendapatkan lisensi.</p>
                        </div>

                        <div v-else class="space-y-3">
                            <div
                                v-for="license in licenses"
                                :key="license.id"
                                @click="form.license_id = license.id.toString()"
                                :class="{
                                    'border-indigo-600 ring-2 ring-indigo-600': form.license_id === license.id.toString(),
                                    'border-gray-300 hover:border-indigo-400': form.license_id !== license.id.toString()
                                }"
                                class="cursor-pointer border rounded-lg p-4 transition-all"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ license.product_name }}</h4>
                                        <p class="text-sm text-gray-500">License: {{ license.license_code }}</p>
                                    </div>
                                    <span 
                                        :class="{
                                            'bg-green-100 text-green-800': license.status === 'active',
                                            'bg-yellow-100 text-yellow-800': license.status === 'trial',
                                            'bg-gray-100 text-gray-800': license.status === 'inactive'
                                        }"
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    >
                                        {{ license.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Select Plan -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">2. Choose Plan</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                v-for="plan in plans"
                                :key="plan.id"
                                @click="selectPlan(plan.id)"
                                :class="{
                                    'border-indigo-600 ring-2 ring-indigo-600': form.subscription_plan_id === plan.id.toString(),
                                    'border-gray-300 hover:border-indigo-400': form.subscription_plan_id !== plan.id.toString()
                                }"
                                class="cursor-pointer border rounded-lg p-6 transition-all"
                            >
                                <h4 class="text-xl font-bold text-gray-900">{{ plan.name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ plan.description }}</p>
                                <div class="mt-4">
                                    <span class="text-3xl font-bold text-indigo-600">{{ formatCurrency(plan.price) }}</span>
                                    <span class="text-gray-500"> / {{ plan.duration_months }} bulan</span>
                                </div>
                                <ul class="mt-4 space-y-2">
                                    <li
                                        v-for="(feature, index) in plan.features"
                                        :key="index"
                                        class="flex items-center text-sm text-gray-600"
                                    >
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        {{ feature }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Voucher Code (Optional) -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">3. Voucher Code (Optional)</h3>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <InputLabel for="voucher_code" value="Voucher Code" />
                                <TextInput
                                    id="voucher_code"
                                    type="text"
                                    v-model="form.voucher_code"
                                    placeholder="Enter voucher code"
                                    class="mt-1 block w-full"
                                />
                                <InputError :message="form.errors.voucher_code" class="mt-2" />
                            </div>
                            <button
                                type="button"
                                class="mt-6 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                            >
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end gap-4">
                        <Link
                            :href="route('customer.subscriptions.index')"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                        >
                            Cancel
                        </Link>
                        <PrimaryButton
                            :disabled="form.processing || !form.subscription_plan_id"
                            class="ml-4"
                        >
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Subscribe Now</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </CustomerLayout>
</template>
