<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    settings: Object,
});
</script>

<template>
    <Head title="System Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">System Settings</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="space-y-8">
                            <!-- General Settings -->
                            <section>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">General Settings</h3>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Site Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.site_name || 'Cooca.id' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Site URL</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.site_url || 'https://cooca.id' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Default Currency</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.currency || 'IDR' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.timezone || 'Asia/Jakarta' }}</dd>
                                    </div>
                                </dl>
                            </section>

                            <!-- Email Settings -->
                            <section>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Email Configuration</h3>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Mail Driver</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.mail_driver || 'smtp' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">From Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.mail_from_address || 'noreply@cooca.id' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">SMTP Host</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.mail_host || '***' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">SMTP Port</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.mail_port || '587' }}</dd>
                                    </div>
                                </dl>
                            </section>

                            <!-- Payment Settings -->
                            <section>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Gateway (Midtrans)</h3>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Merchant ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.midtrans_merchant_id || '***' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Environment</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.midtrans_environment || 'sandbox' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Auto Capture</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.midtrans_auto_capture ? 'Yes' : 'No' }}</dd>
                                    </div>
                                </dl>
                            </section>

                            <!-- WhatsApp Settings -->
                            <section>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">WhatsApp API (Fonnte)</h3>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">API Token</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.fonnte_token ? '***' + settings.fonnte_token.slice(-4) : 'Not configured' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            <span :class="settings.fonnte_token ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ settings.fonnte_token ? 'Active' : 'Inactive' }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </section>

                            <!-- System Info -->
                            <section>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.laravel_version || '11.x' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.php_version || '8.3' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Last Backup</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ settings.last_backup ? formatDate(settings.last_backup) : 'Never' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(settings.updated_at) }}</dd>
                                    </div>
                                </dl>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleString('id-ID');
        }
    }
};
</script>
