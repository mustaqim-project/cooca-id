<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Button from "@/Components/ui/Button.vue";
import TextInput from "@/Components/forms/TextInput.vue";
import SelectInput from "@/Components/forms/SelectInput.vue";
import Alert from "@/Components/ui/Alert.vue";

const props = defineProps({
    customer: {
        type: Object,
        required: true,
    },
    licenses: {
        type: Array,
        default: () => [],
    },
    subscriptions: {
        type: Array,
        default: () => [],
    },
});

const editing = ref(false);
const formData = ref({
    name: props.customer.name || "",
    email: props.customer.email || "",
    business_name: props.customer.business_name || "",
    domain: props.customer.domain || "",
    status: props.customer.status || "pending",
    notes: props.customer.notes || "",
});

const updateCustomer = () => {
    router.put(
        route("admin.customers.update", props.customer.id),
        formData.value,
        {
            onSuccess: () => {
                editing.value = false;
            },
        },
    );
};

const generateLicense = () => {
    router.post(route("admin.licenses.generate"), {
        customer_id: props.customer.id,
    });
};

const activateLicense = (license) => {
    router.post(route("admin.licenses.activate", license.id));
};

const revokeLicense = (license) => {
    if (confirm("Apakah Anda yakin ingin revoke license ini?")) {
        router.post(route("admin.licenses.revoke", license.id));
    }
};

const formatStatus = (status) => {
    const statusMap = {
        trial: { class: "bg-yellow-100 text-yellow-800", label: "Trial" },
        active: { class: "bg-green-100 text-green-800", label: "Active" },
        expired: { class: "bg-red-100 text-red-800", label: "Expired" },
        pending: { class: "bg-gray-100 text-gray-800", label: "Pending" },
    };
    return (
        statusMap[status] || {
            class: "bg-gray-100 text-gray-800",
            label: status,
        }
    );
};
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6">
                <Button
                    @click="() => router.visit(route('admin.customers.index'))"
                    variant="secondary"
                    class="mb-4"
                >
                    ← Kembali
                </Button>
                <h1 class="text-2xl font-bold text-gray-900">
                    Detail Customer: {{ customer.business_name }}
                </h1>
            </div>

            <Alert
                v-if="$page.props.flash?.success"
                type="success"
                class="mb-4"
            >
                {{ $page.props.flash.success }}
            </Alert>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">
                                Informasi Customer
                            </h2>
                            <Button
                                @click="editing = !editing"
                                size="sm"
                                variant="secondary"
                            >
                                {{ editing ? "Batal" : "Edit" }}
                            </Button>
                        </div>

                        <div v-if="!editing" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-600"
                                        >Nama Bisnis</label
                                    >
                                    <p class="font-medium">
                                        {{ customer.business_name }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600"
                                        >Email</label
                                    >
                                    <p class="font-medium">
                                        {{ customer.email }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600"
                                        >Domain</label
                                    >
                                    <p class="font-medium">
                                        {{ customer.domain || "-" }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600"
                                        >Status</label
                                    >
                                    <span
                                        :class="[
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            formatStatus(customer.status).class,
                                        ]"
                                    >
                                        {{
                                            formatStatus(customer.status).label
                                        }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600"
                                        >Registered</label
                                    >
                                    <p class="font-medium">
                                        {{ new Date(customer.created_at).toLocaleDateString('id-ID") }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600"
                                        >Affiliator Code</label
                                    >
                                    <p class="font-medium">
                                        {{ customer.affiliator_code || "-" }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form
                            v-else
                            @submit.prevent="updateCustomer"
                            class="space-y-4"
                        >
                            <div class="grid grid-cols-2 gap-4">
                                <TextInput
                                    v-model="formData.name"
                                    label="Nama Lengkap"
                                    required
                                />
                                <TextInput
                                    v-model="formData.email"
                                    label="Email"
                                    type="email"
                                    required
                                />
                                <TextInput
                                    v-model="formData.business_name"
                                    label="Nama Bisnis"
                                    required
                                />
                                <TextInput
                                    v-model="formData.domain"
                                    label="Domain"
                                    placeholder="nama.cooca.id"
                                />
                                <SelectInput
                                    v-model="formData.status"
                                    label="Status"
                                    :options="[
                                        { value: 'pending', label: 'Pending' },
                                        { value: 'trial', label: 'Trial' },
                                        { value: 'active', label: 'Active' },
                                        { value: 'expired', label: 'Expired' },
                                    ]"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Notes</label
                                >
                                <textarea
                                    v-model="formData.notes"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                ></textarea>
                            </div>
                            <div class="flex space-x-2">
                                <Button type="submit">Simpan Perubahan</Button>
                                <Button
                                    type="button"
                                    variant="secondary"
                                    @click="editing = false"
                                    >Batal</Button
                                >
                            </div>
                        </form>
                    </div>

                    <!-- Licenses Section -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Licenses</h2>
                            <Button @click="generateLicense" size="sm"
                                >Generate License</Button
                            >
                        </div>

                        <div
                            v-if="licenses.length === 0"
                            class="text-gray-500 text-sm"
                        >
                            Belum ada license yang di-generate untuk customer
                            ini.
                        </div>

                        <div v-else class="space-y-3">
                            <div
                                v-for="license in licenses"
                                :key="license.id"
                                class="border rounded-lg p-4 bg-gray-50"
                            >
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-mono text-sm font-bold">
                                            License: {{ license.license_code }}
                                        </p>
                                        <p
                                            class="font-mono text-sm text-gray-600"
                                        >
                                            Token: {{ license.token_code }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Status:
                                            <span
                                                :class="
                                                    license.is_active
                                                        ? 'text-green-600'
                                                        : 'text-red-600'
                                                "
                                            >
                                                {{
                                                    license.is_active
                                                        ? "Active"
                                                        : "Inactive"
                                                }}
                                            </span>
                                            | Domain:
                                            {{ license.domain || "Not bound" }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <Button
                                            v-if="!license.is_active"
                                            @click="activateLicense(license)"
                                            size="sm"
                                            variant="success"
                                        >
                                            Activate
                                        </Button>
                                        <Button
                                            v-if="license.is_active"
                                            @click="revokeLicense(license)"
                                            size="sm"
                                            variant="danger"
                                        >
                                            Revoke
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Subscriptions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">
                            Subscriptions
                        </h2>
                        <div
                            v-if="subscriptions.length === 0"
                            class="text-gray-500 text-sm"
                        >
                            Belum ada subscription aktif.
                        </div>
                        <div v-else class="space-y-3">
                            <div
                                v-for="sub in subscriptions"
                                :key="sub.id"
                                class="border rounded p-3"
                            >
                                <p class="font-medium">{{ sub.plan_name }}</p>
                                <p class="text-sm text-gray-600">
                                    Rp
                                    {{ Number(sub.price).toLocaleString('id-ID") }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ new Date(sub.start_date).toLocaleDateString('id-ID") }}
                                    -
                                    {{ new Date(sub.end_date).toLocaleDateString('id-ID") }}
                                </p>
                                <span
                                    :class="[
                                        'px-2 py-1 rounded-full text-xs font-medium',
                                        sub.status === 'active'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{ sub.status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">
                            Quick Actions
                        </h2>
                        <div class="space-y-2">
                            <Button
                                @click="generateLicense"
                                variant="secondary"
                                class="w-full justify-start"
                            >
                                🔑 Generate New License
                            </Button>
                            <Button
                                variant="secondary"
                                class="w-full justify-start"
                            >
                                📧 Send Email Notification
                            </Button>
                            <Button
                                variant="secondary"
                                class="w-full justify-start"
                            >
                                💬 Send WhatsApp
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
