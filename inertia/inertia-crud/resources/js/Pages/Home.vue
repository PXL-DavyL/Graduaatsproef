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
                    <InputButton class="self-end"> Read more </InputButton>
                </article>
            </section>

            <Pagination :pagination="usePage().props.blogs" />
        </div>
    </Layout>
</template>

<script setup>
import InputButton from "@/Components/InputButton.vue";
import Pagination from "@/Components/Pagination.vue";
import Layout from "@/Layouts/Layout.vue";
import { usePage } from "@inertiajs/vue3";

const articles = usePage().props.blogs.data;
</script>
