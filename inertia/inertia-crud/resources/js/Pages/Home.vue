<template>
    <Layout title="Home">
        <div class="flex flex-col gap-3">
            <section
                class="grid grid-flow-row grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
            >
                <article
                    v-for="(article, index) in articles"
                    class="min-h-24 min-w-48 rounded flex flex-col justify-between bg-gray-50 border border-gray-200 p-2"
                    :key="article.id"
                >
                    <div class="flex justify-between bg-sky-100 rounded p-2">
                        <span>
                            {{ article.poster.name }}
                        </span>
                        <span> {{ formatDate(article.created_at) }}</span>
                    </div>
                    {{ article.title }}

                    <div class="w-full flex justify-between items-center">
                        <div class="flex gap-2">
                            <div class="flex gap-2">
                                <div
                                    class="flex gap-1 items-center text-gray-400"
                                >
                                    <IconView class="h-4 w-4" />
                                    <span class="text-sm">
                                        {{ article.views }}
                                    </span>
                                </div>
                                <div
                                    class="flex gap-1 items-center text-gray-400"
                                >
                                    <IconComments class="h-4 w-4" />
                                    <span class="text-sm">{{
                                        article.comments.length
                                    }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div
                                    class="flex gap-1 items-center text-gray-400"
                                    v-for="reaction in processedReactions[index]
                                        .reactions"
                                >
                                    <span v-html="reaction.icon"></span>
                                    <span
                                        v-if="reaction.count > 0"
                                        v-html="reaction.count"
                                    ></span>
                                </div>
                            </div>
                        </div>
                        <InputButtonLink
                            :href="route('blog.show', article.id, true)"
                            class="self-end"
                        >
                            Read more
                        </InputButtonLink>
                    </div>
                </article>
            </section>

            <Pagination :pagination="usePage().props.blogs" />
        </div>
    </Layout>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import { formatDate } from "@/Composables/dates.js";

import Layout from "@/Layouts/Layout.vue";
import Pagination from "@/Components/Pagination.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";
import IconView from "@/Components/icons/IconView.vue";
import IconComments from "@/Components/icons/IconComments.vue";

const articles = usePage().props.blogs.data;

const processedReactions = ref([]);

const processReactions = () => {
    processedReactions.value = articles.map((article) => {
        const reactionCounts = article.reactions.reduce(
            (acc, reaction) => {
                if (reaction.type === "upvote") {
                    acc.upvotes++;
                } else if (reaction.type === "downvote") {
                    acc.downvotes++;
                }
                return acc;
            },
            { upvotes: 0, downvotes: 0 }
        );

        return {
            id: article.id,
            reactions: [
                { id: "upvote", icon: "👍", count: reactionCounts.upvotes },
                { id: "downvote", icon: "👎", count: reactionCounts.downvotes },
            ],
        };
    });
};

onMounted(() => {
    if (usePage().props.errors) {
        for (const error in usePage().props.errors) {
            toast.error(usePage().props.errors[error]);
        }
    }
});

// Process reactions before the DOM gets loaded....
onBeforeMount(() => {
    processReactions();
});
</script>
