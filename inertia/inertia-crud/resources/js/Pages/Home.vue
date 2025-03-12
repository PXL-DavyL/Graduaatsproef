<template>
    <Layout title="Home">
        <div class="flex flex-col gap-3">
            <section
                class="grid grid-flow-row grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
            >
                <article
                    v-for="article in articles"
                    class="min-h-48 min-w-48 rounded flex flex-col justify-between bg-gray-50"
                    :key="article.id"
                >
                    <div class="flex-col">
                        {{
                            new Date(article.created_at).toLocaleDateString(
                                "nl-BE"
                            )
                        }}
                    </div>

                    {{ article.content }}

                    <a href="#" class="self-end">Read more</a>
                </article>
            </section>

            <Pagination :pagination="usePage().props.blogs" />
        </div>
    </Layout>
</template>

<script setup>
import Pagination from "@/Components/Pagination.vue";
import Layout from "@/Layouts/Layout.vue";
import { usePage } from "@inertiajs/vue3";

const articles = usePage().props.blogs.data;
</script>
