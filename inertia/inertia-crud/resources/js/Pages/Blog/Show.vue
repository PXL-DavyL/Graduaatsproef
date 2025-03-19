<template>
    <Layout :title="blog.title">
        <div class="flex flex-col gap-4">
            <div class="flex justify-between p-2 bg-gray-100 rounded">
                <span
                    >Posted by {{ blog.poster.name }} on
                    {{ formatDate(blog.created_at) }}</span
                >

                <span v-if="blog.created_at != blog.updated_at"
                    >Last changed on {{ formatDate(blog.updated_at) }}</span
                >
            </div>

            <span v-html="blog.content"></span>

            <div class="flex gap-2 w-full">
                <InputButton @click="showComments = !showComments">
                    Comments
                    <IconChevron class="transform transition duration-300" :class="{ 'rotate-180': showComments }" />
                </InputButton>
                <div class="flex gap-2">
                    <InputButton
                        v-for="reaction in reactions"
                        type="secondary"
                        :key="reaction.id"
                        class="flex gap-2"
                    >
                        <span v-html="reaction.icon"></span>
                        <span
                            v-if="reaction.count > 0"
                            v-html="reaction.count"
                        ></span>
                    </InputButton>
                </div>
                <div
                    class="self-end"
                    v-if="
                        usePage().props.auth.user !== null &&
                        usePage().props.auth.user.id == blog.poster.id
                    "
                >
                    <InputButtonLink
                        :href="route('blog.edit', blog.id)"
                        type="warning"
                        method="get"
                    >
                        Edit
                    </InputButtonLink>
                </div>
            </div>

            <div v-if="showComments" class="flex flex-col gap-2 p-2 bg-gray-100 rounded">
                <h1 class="text-xl">Comments</h1>
                <div
                    v-for="comment in blog.comments"
                    :key="comment.id"
                    class="flex flex-col gap-2 bg-white border border-gray-50 p-2 rounded"
                >
                    <span>Comment by {{ comment.poster.name }} on {{ formatDate(comment.created_at) }}</span>
                    <span>{{ comment.content }}</span>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates.js";

import Layout from "@/Layouts/Layout.vue";
import InputButton from "@/Components/InputButton.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";
import IconChevron from "@/Components/icons/IconChevron.vue";

const showComments = ref(true);
const reactions = [
    {
        id: 1,
        name: "Like",
        icon: "👍",
        count: 0,
    },
    {
        id: 2,
        name: "Dislike",
        icon: "👎",
        count: 2,
    },
];
const comments = [
    {
        id: 1,
        user: {
            id: 1,
            name: "John Doe",
        },
        content: "This is a comment",
        created_at: "2021-09-01 12:00:00",
    },
    {
        id: 2,
        user: {
            id: 2,
            name: "Jane Doe",
        },
        content: "This is another comment",
        created_at: "2021-09-01 12:00:00",
    }
]

const blog = usePage().props.blog;
console.log(blog);
</script>
