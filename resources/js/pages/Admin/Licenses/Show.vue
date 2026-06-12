<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import Button from '@/components/ui/Button.vue';
import Alert from '@/components/ui/Alert.vue';

const props = defineProps({
    license: Object,
    customer: Object
});

const revokeLicense = () => {
    if (confirm('Revoke license ini?')) {
        router.post(route('admin.licenses.revoke', props.license.id));
    }
};

const activateLicense = () => {
    router.post(route('admin.licenses.activate', props.license.id));
};
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <Button @click="() => router.visit(route('admin.licenses.index'))" variant="secondary" class="mb-4">← Kembali</Button>
            <h1 class="text-2xl font-bold mb-6">Detail License</h1>
            
            <Alert v-if="$page.props.flash?.success" type="success" class="mb-4">{{ $page.props.flash.success }}</Alert>

            <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600">License Code</label>
                        <p class="font-mono text-lg font-bold">{{ license.license_code }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Token Code</label>
                        <p class="font-mono text-lg">{{ license.token_code }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Customer</label>
                        <p class="font-medium">{{ customer?.business_name || '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Domain</label>
                        <p class="font-medium">{{ license.domain || 'Not bound' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status</label>
                        <span :class="license.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 rounded-full text-xs font-medium">
                            {{ license.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex space-x-2 pt-4">
                        <Button v-if="!license.is_active" @click="activateLicense" variant="success">Activate</Button>
                        <Button v-if="license.is_active" @click="revokeLicense" variant="danger">Revoke</Button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
