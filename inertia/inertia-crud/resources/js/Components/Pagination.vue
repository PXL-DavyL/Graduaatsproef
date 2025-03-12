<template>
    <div class="w-full flex gap-2 justify-center items-center">
        <div
            v-for="link in props.pagination.links"
            class="bg-gray-50 border border-gray-200 rounded hover:bg-gray-100 flex justify-center items-center"
            :class="{ 'hover:cursor-pointer': link.url !== null }, {'hover:cursor-default': link.url === null }"
        >
            <span
                v-if="link.url === null"
                class="p-2 bg-zinc-100 text-gray-400"
                v-html="link.label"

            ></span>
            <Link
                v-else
                :href="link.url"
                class="p-2 px-3"
                :class="{ 'bg-sky-100 border-sky-200 text-sky-700': link.active }"
            >
                <span v-html="link.label"></span>
            </Link>
        </div>
    </div>
</template>
<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});
</script>
