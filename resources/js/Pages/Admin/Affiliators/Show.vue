<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import Button from '@/Components/ui/Button.vue';
import Alert from '@/Components/ui/Alert.vue';

const props = defineProps({
    affiliator: Object,
    downlines: Array,
    commissions: Array
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
};
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <Button @click="() => router.visit(route('admin.affiliators.index'))" variant="secondary" class="mb-4">
                ← Kembali
            </Button>
            <h1 class="text-2xl font-bold mb-6">Detail Affiliator: {{ affiliator.name }}</h1>
            
            <Alert v-if="$page.props.flash?.success" type="success" class="mb-4">
                {{ $page.props.flash.success }}
            </Alert>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Informasi Affiliator</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600">Nama</label>
                                <p class="font-medium">{{ affiliator.name }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Email</label>
                                <p class="font-medium">{{ affiliator.email }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Referral Code</label>
                                <p class="font-mono font-medium">{{ affiliator.referral_code }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Total Komisi</label>
                                <p class="font-medium text-green-600">{{ formatCurrency(affiliator.total_commission) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Downline Network</h2>
                        <div v-if="downlines.length === 0" class="text-gray-500">Belum ada downline.</div>
                        <div v-else class="space-y-2">
                            <div v-for="dl in downlines" :key="dl.id" class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <span>{{ dl.name }}</span>
                                <span class="text-sm text-gray-600">Level {{ dl.level }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Statistik</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Total Referrals</span>
                                <span class="font-bold">{{ affiliator.total_referrals }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Active Customers</span>
                                <span class="font-bold">{{ affiliator.active_customers }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pending Withdrawal</span>
                                <span class="font-bold">{{ formatCurrency(affiliator.pending_withdrawal) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
