<?php
$content = file_get_contents('c:/laragon/www/cooca-id/resources/js/Components/PrimaryButton.vue');
$content = str_replace('bg-gray-800', 'bg-red-600', $content);
$content = str_replace('hover:bg-gray-700', 'hover:bg-red-500', $content);
$content = str_replace('focus:bg-gray-700', 'focus:bg-red-700', $content);
$content = str_replace('active:bg-gray-900', 'active:bg-red-900', $content);
$content = str_replace('focus:ring-indigo-500', 'focus:ring-red-500', $content);
file_put_contents('c:/laragon/www/cooca-id/resources/js/Components/DangerButton.vue', $content);
echo "DangerButton created.\n";

$pagination = <<<VUE
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
                <Link v-else :href="link.url" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-blue-700 text-white': link.active }" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
VUE;
file_put_contents('c:/laragon/www/cooca-id/resources/js/Components/Pagination.vue', $pagination);
echo "Pagination created.\n";

