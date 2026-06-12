<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import Button from '@/components/ui/Button.vue';
import Alert from '@/components/ui/Alert.vue';

const props = defineProps({ settlement: Object });

const approve = () => {
    if (confirm('Approve withdrawal ini?')) {
        router.post(route('admin.settlements.approve', props.settlement.id));
    }
};

const reject = () => {
    if (confirm('Reject withdrawal ini?')) {
        router.post(route('admin.settlements.reject', props.settlement.id));
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
};
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <Button @click="() => router.visit(route('admin.settlements.index'))" variant="secondary" class="mb-4">← Kembali</Button>
            <h1 class="text-2xl font-bold mb-6">Detail Withdrawal Request</h1>
            
            <Alert v-if="$page.props.flash?.success" type="success" class="mb-4">{{ $page.props.flash.success }}</Alert>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Informasi Affiliator</h2>
                    <div class="space-y-3">
                        <div><label class="text-sm text-gray-600">Nama</label><p class="font-medium">{{ settlement.affiliator.name }}</p></div>
                        <div><label class="text-sm text-gray-600">Email</label><p class="font-medium">{{ settlement.affiliator.email }}</p></div>
                        <div><label class="text-sm text-gray-600">Referral Code</label><p class="font-mono">{{ settlement.affiliator.referral_code }}</p></div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Detail Withdrawal</h2>
                    <div class="space-y-3">
                        <div><label class="text-sm text-gray-600">Amount</label><p class="font-bold text-lg">{{ formatCurrency(settlement.amount) }}</p></div>
                        <div><label class="text-sm text-gray-600">Method</label><p class="font-medium">{{ settlement.payment_method }}</p></div>
                        <div><label class="text-sm text-gray-600">Account</label><p class="font-medium">{{ settlement.account_number }}</p></div>
                        <div><label class="text-sm text-gray-600">Bank/E-wallet</label><p class="font-medium">{{ settlement.bank_name }}</p></div>
                        <div><label class="text-sm text-gray-600">Status</label>
                            <span :class="{
                                'bg-yellow-100 text-yellow-800': settlement.status === 'pending',
                                'bg-green-100 text-green-800': settlement.status === 'approved',
                                'bg-red-100 text-red-800': settlement.status === 'rejected'
                            }" class="px-2 py-1 rounded-full text-xs font-medium">{{ settlement.status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="settlement.status === 'pending'" class="mt-6 flex space-x-4">
                <Button @click="approve" variant="success">Approve</Button>
                <Button @click="reject" variant="danger">Reject</Button>
            </div>
        </div>
    </AdminLayout>
</template>
