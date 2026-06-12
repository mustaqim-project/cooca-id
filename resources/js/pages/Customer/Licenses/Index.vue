<template>
    <CustomerLayout title="My Licenses">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">My Licenses</h2>

                        <!-- Licenses Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="license in licenses" :key="license.id" class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-900">{{ license.product?.name || 'ERP System' }}</h3>
                                        <p class="text-sm text-gray-500">{{ license.domain || license.subdomain + '.cooca.id' }}</p>
                                    </div>
                                    <span :class="{
                                        'px-2 py-1 text-xs font-semibold rounded-full': true,
                                        'bg-green-100 text-green-800': license.status === 'active',
                                        'bg-yellow-100 text-yellow-800': license.status === 'inactive',
                                        'bg-red-100 text-red-800': license.status === 'revoked',
                                        'bg-gray-100 text-gray-800': license.status === 'expired'
                                    }">
                                        {{ license.status }}
                                    </span>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">License Code:</span>
                                        <span class="font-mono text-gray-900">{{ license.license_code }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Token Code:</span>
                                        <span class="font-mono text-gray-900">{{ license.token_code }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Plan:</span>
                                        <span class="text-gray-900 capitalize">{{ license.plan }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Expires:</span>
                                        <span class="text-gray-900">{{ formatDate(license.expires_at) }}</span>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button @click="showCredentials(license)" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                                        View Credentials
                                    </button>
                                    <a v-if="license.status === 'active'" :href="getErpUrl(license)" target="_blank" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm text-center">
                                        Open ERP
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="licenses.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No licenses yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by subscribing to an ERP product.</p>
                            <div class="mt-6">
                                <a :href="route('customer.products.index')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Browse Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credentials Modal -->
        <div v-if="selectedLicense" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-xl font-bold mb-4">License Credentials</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Code</label>
                        <div class="flex gap-2">
                            <input readonly :value="selectedLicense.license_code" class="flex-1 rounded-md border-gray-300 bg-gray-50 font-mono text-sm">
                            <button @click="copyToClipboard(selectedLicense.license_code)" class="px-3 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Copy</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Token Code</label>
                        <div class="flex gap-2">
                            <input readonly :value="selectedLicense.token_code" class="flex-1 rounded-md border-gray-300 bg-gray-50 font-mono text-sm">
                            <button @click="copyToClipboard(selectedLicense.token_code)" class="px-3 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Copy</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                        <input readonly :value="selectedLicense.domain || selectedLicense.subdomain + '.cooca.id'" class="w-full rounded-md border-gray-300 bg-gray-50 text-sm">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button @click="selectedLicense = null" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Close</button>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/layouts/CustomerLayout.vue';

const licenses = ref([]);
const selectedLicense = ref(null);

const fetchLicenses = () => {
    router.get(route('customer.licenses.index'), {}, {
        preserveState: true,
        onSuccess: (response) => {
            licenses.value = response.props.licenses?.data || [];
        }
    });
};

const showCredentials = (license) => {
    selectedLicense.value = license;
};

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    alert('Copied to clipboard!');
};

const getErpUrl = (license) => {
    return `https://${license.domain || license.subdomain + '.cooca.id'}`;
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

onMounted(() => {
    fetchLicenses();
});
</script>
