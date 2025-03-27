<template>
    <Layout title="Edit Blog">
        <div
            class="flex flex-col gap-2 justify-center items-center h-full w-full"
        >
            <EditContent />
            <DeleteBlog :blog="blog_id"/>
        </div>
    </Layout>
</template>
<script setup>
import { useRoute } from "vue-router";

import Layout from "@/layout/Layout.vue";
import EditContent from "./EditPartials/EditContent.vue";
import DeleteBlog from "./EditPartials/DeleteBlog.vue";

const blog_id = Number(useRoute().params.id);
</script>
