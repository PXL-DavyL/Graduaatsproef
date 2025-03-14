<template>

<div class="flex flex-col gap-2">
    <span>{{ usePage().props.user.name }}</span>
    <span>{{ usePage().props.user.email }}</span>
    <span>{{ usePage().props.user.created_at }}</span>
    <span>{{ usePage().props.user.updated_at }}</span>
</div>
</template>
<script setup>
    import { usePage } from "@inertiajs/vue3";
    console.log(usePage().props.user);
</script>