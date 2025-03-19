<template>
    <Layout title="Edit Blog">
        <div
            class="flex flex-col gap-2 justify-center items-center h-full w-full"
        >
            <EditContent />
            <DeleteBlog />
        </div>
    </Layout>
</template>
<script setup>
import Layout from "@/Layouts/Layout.vue";
import EditContent from "./EditPartials/EditContent.vue";
import DeleteBlog from "./EditPartials/DeleteBlog.vue";
import { usePage } from "@inertiajs/vue3";
console.log(usePage().props.auth);
</script>
