<script setup>
import ApplicationLogo from '@/components/ApplicationLogo.vue';
</script>

<template>
    <div
        class="flex max-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0"
    >
        <div class="h-20 w-20">
            <router-link :to="{ name: 'home' }">
                <ApplicationLogo class="fill-current text-red-600" />
            </router-link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded"
        >
            <slot />
        </div>
    </div>
</template>
