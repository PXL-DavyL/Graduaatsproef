<template>
    <Layout :title="blog.title">
        <div class="flex flex-col">
            <div class="flex justify-between">
                <span>{{ blog.poster.name }}</span>
                <span>{{ new Date(blog.created_at).toLocaleDateString("nl-BE") }}</span>
            </div>

            <span v-html="blog.content"></span>
        </div>
        
    </Layout>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";

const blog = usePage().props.blog;

console.log(usePage().props);
</script>
