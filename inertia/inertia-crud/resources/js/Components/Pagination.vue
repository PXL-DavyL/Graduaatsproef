<template>
    <div class="w-full flex gap-2 justify-center items-center">
        <div
            v-for="link in props.pagination.links"
            class="bg-gray-50 border border-gray-200 rounded hover:bg-gray-100 flex justify-center items-center"
            :class="{ 'hover:cursor-pointer': link.url !== null }, {'hover:cursor-default': link.url === null },
            {'bg-zinc-600 border-zinc-700 hover:bg-zinc-500': props.darkmode === true}"
        >
            <span
                v-if="link.url === null"
                class="p-2 bg-zinc-100 text-gray-400 rounded"
                :class="{'bg-zinc-600 border-zinc-700': props.darkmode === true}"
                v-html="link.label"

            ></span>
            <Link
                v-else
                :href="link.url"
                class="p-2 px-3"
                :class="{ 'bg-sky-100 hover:bg-sky-200 border-sky-200 text-sky-700 rounded': link.active && props.darkmode === false}, 
                {'bg-indigo-500 hover:bg-indigo-400 border-sky-700 text-sky-200 rounded': link.active && props.darkmode === true }"
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
    darkmode: {
        type: Boolean,
        default: false,
    }
});
</script>
