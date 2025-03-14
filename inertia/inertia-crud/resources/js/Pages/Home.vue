<template>
    <Layout title="Home">
        <div class="flex flex-col gap-3">
            <section
                class="grid grid-flow-row grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
            >
                <article
                    v-for="article in articles"
                    class="min-h-24 min-w-48 rounded flex flex-col justify-between bg-gray-50 border border-gray-200 p-2"
                    :key="article.id"
                >
                    <div class="flex justify-between bg-sky-100 rounded p-2">
                        <span>
                            {{ article.poster.name }}
                        </span>
                        <span>
                            {{
                                new Date(article.created_at).toLocaleDateString(
                                    "nl-BE"
                                )
                            }}</span
                        >
                    </div>
                    {{ article.title }}

                    <InputButtonLink :href="route('blog.show', article.id, true)" class="self-end">Read more</InputButtonLink>
                </article>
            </section>

            <Pagination :pagination="usePage().props.blogs" />
        </div>
    </Layout>
</template>

<script setup>
import { onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { toast } from 'vue3-toastify';

import Layout from "@/Layouts/Layout.vue";
import Pagination from "@/Components/Pagination.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";

const articles = usePage().props.blogs.data;

onMounted(() => {
    if(usePage().props.errors) {

        for(const error in usePage().props.errors) {
            toast.error(usePage().props.errors[error]);
        }
    }
});

console.log(usePage().props);
</script>
