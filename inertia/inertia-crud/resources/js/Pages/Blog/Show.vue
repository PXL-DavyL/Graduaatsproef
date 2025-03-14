<template>
    <Layout :title="blog.title">
        <div class="flex flex-col">
            <div class="flex justify-between">
                <span>{{ blog.poster.name }}</span>
                <span>{{ new Date(blog.created_at).toLocaleDateString("nl-BE") }}</span>

            </div>

            <span v-html="blog.content"></span>

            <div v-if="usePage().props.auth.user.id == blog.poster.id">
                <InputButtonLink :href="route('blog.edit', blog.id)" type="warning" method="get">
                    Edit
                </InputButtonLink>
            </div>
        </div>
        
    </Layout>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";

const blog = usePage().props.blog;
</script>
