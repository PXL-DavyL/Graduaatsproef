<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex max-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0"
    >
        <div class="h-20 w-20">
            <Link href="/">
                <ApplicationLogo class="fill-current text-red-600" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded"
        >
            <slot />
        </div>
    </div>
</template>
