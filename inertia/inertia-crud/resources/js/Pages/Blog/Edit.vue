<template>
    <Layout title="Edit Blog">
        <div
            class="flex flex-col gap-2 justify-center items-center h-full w-full"
        >
            <EditPoster />
            <EditContent />
            <DeleteBlog />
        </div>
    </Layout>
</template>
<script setup>
import Layout from "@/Layouts/Layout.vue";
import EditPoster from "./Partials/EditPoster.vue";
import EditContent from "./Partials/EditContent.vue";
import DeleteBlog from "./Partials/DeleteBlog.vue";
</script>
