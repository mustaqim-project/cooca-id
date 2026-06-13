<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Button from '@/Components/ui/Button.vue';
import TextInput from '@/Components/forms/TextInput.vue';
import SelectInput from '@/Components/forms/SelectInput.vue';

const props = defineProps({ voucher: Object });

const form = ref({
    code: props.voucher.code,
    type: props.voucher.type,
    value: props.voucher.value,
    min_purchase: props.voucher.min_purchase,
    max_discount: props.voucher.max_discount,
    valid_from: props.voucher.valid_from,
    valid_until: props.voucher.valid_until,
    usage_limit: props.voucher.usage_limit,
    description: props.voucher.description
});

const submit = () => {
    router.put(route('admin.vouchers.update', props.voucher.id), form.value);
};
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <Button @click="() => router.visit(route('admin.vouchers.index'))" variant="secondary" class="mb-4">← Kembali</Button>
            <h1 class="text-2xl font-bold mb-6">Edit Voucher</h1>
            
            <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
                <form @submit.prevent="submit" class="space-y-4">
                    <TextInput v-model="form.code" label="Kode Voucher" required />
                    <SelectInput v-model="form.type" label="Tipe Diskon" :options="[
                        { value: 'percentage', label: 'Persentase (%)' },
                        { value: 'nominal', label: 'Nominal (Rp)' }
                    ]" required />
                    <TextInput v-model="form.value" label="Nilai Diskon" type="number" required />
                    <TextInput v-model="form.min_purchase" label="Minimal Pembelian" type="number" />
                    <TextInput v-model="form.max_discount" label="Maksimal Diskon (Rp)" type="number" />
                    <div class="grid grid-cols-2 gap-4">
                        <TextInput v-model="form.valid_from" label="Berlaku Dari" type="date" />
                        <TextInput v-model="form.valid_until" label="Berlaku Hingga" type="date" />
                    </div>
                    <TextInput v-model="form.usage_limit" label="Batas Penggunaan" type="number" />
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea v-model="form.description" rows="3" class="w-full px-3 py-2 border rounded-md"></textarea>
                    </div>
                    <Button type="submit">Update Voucher</Button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
