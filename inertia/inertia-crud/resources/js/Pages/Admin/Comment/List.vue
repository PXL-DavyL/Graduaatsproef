<template>
    <AdminLayout title="Manage Comments">
        <div class="flex flex-col gap-4">
            <div class="mt-3 flex flex-col gap-5">
                <div class="flex flex-col border border-zinc-600 rounded">
                    <div class="p-2 bg-zinc-700 flex justify-between">
                        <span> Comment list for blog id {{ usePage().props.blog.id }}</span>
                    </div>
                    <table class="table-auto w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-600 text-white">
                                <th class="p-2 border-b border-zinc-500">ID</th>
                                <th class="p-2 border-b border-zinc-500">
                                    Content
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Poster
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Created on
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Updated on
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="comment in usePage().props.comments.data"
                                :key="comment.id"
                                :class="'odd:bg-zinc-500 even:bg-zinc-400 hover:text-zinc-800 hover:bg-zinc-300 cursor-pointer'"
                                @click="onClickRow(comment)"
                            >
                                <td class="p-2 border-b border-zinc-500">
                                    {{ comment.id }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ comment.content }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ comment.poster.name }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(comment.created_at) }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(comment.updated_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span class="p-2 bg-zinc-600"> Click on a row to edit or delete the comment. </span>
                </div>
            </div>

            <Pagination :pagination="usePage().props.comments" :darkmode="true" />
        </div>
        <InputButtonLink type="secondary" class="mt-4 w-full" :href="route('admin.blogs.show', { blog: usePage().props.blog })">
            Back to blog   
        </InputButtonLink>
    </AdminLayout>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates";

import Pagination from "@/Components/Pagination.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";

const onClickRow = (comment) => {
    window.location.href = route("admin.comments.edit", {
        blog: usePage().props.blog.id,
        comment: comment.id,
    });
};
</script>
