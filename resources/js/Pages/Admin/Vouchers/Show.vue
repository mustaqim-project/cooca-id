<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router } from "@inertiajs/vue3";
import Button from "@/Components/ui/Button.vue";

const props = defineProps({ voucher: Object });
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <Button
                @click="() => router.visit(route('admin.vouchers.index'))"
                variant="secondary"
                class="mb-4"
                >← Kembali</Button
            >
            <h1 class="text-2xl font-bold mb-6">
                Detail Voucher: {{ voucher.code }}
            </h1>

            <div class="bg-white rounded-lg shadow p-6 max-w-2xl space-y-4">
                <div>
                    <label class="text-sm text-gray-600">Kode</label>
                    <p class="font-medium">{{ voucher.code }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Tipe</label>
                    <p class="font-medium">
                        {{
                            voucher.type === "percentage"
                                ? "Persentase"
                                : "Nominal"
                        }}
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Nilai</label>
                    <p class="font-medium">
                        {{ voucher.value
                        }}{{ voucher.type === "percentage" ? "%" : " Rp" }}
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-600"
                        >Minimal Pembelian</label
                    >
                    <p class="font-medium">
                        Rp
                        {{ Number(voucher.min_purchase).toLocaleString('id-ID") }}
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Maksimal Diskon</label>
                    <p class="font-medium">
                        Rp
                        {{ Number(voucher.max_discount || 0).toLocaleString('id-ID") }}
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Periode</label>
                    <p class="font-medium">
                        {{ voucher.valid_from }} - {{ voucher.valid_until }}
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Penggunaan</label>
                    <p class="font-medium">
                        {{ voucher.used_count }} /
                        {{ voucher.usage_limit || "∞" }}
                    </p>
                </div>
                <div class="pt-4">
                    <Button
                        @click="
                            () =>
                                router.visit(
                                    route('admin.vouchers.edit', voucher.id),
                                )
                        "
                        >Edit Voucher</Button
                    >
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
