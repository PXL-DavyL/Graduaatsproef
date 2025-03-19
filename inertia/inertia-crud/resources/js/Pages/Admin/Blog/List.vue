<template>
    <AdminLayout title="Manage Blogs">
        <div class="flex flex-col gap-4">
            <div class="mt-3 flex flex-col gap-5">
                <div class="flex flex-col border border-zinc-600 rounded">
                    <div class="p-2 bg-zinc-700 flex justify-between">
                        <span> Blog List </span>
                        <InputButtonLink
                            type="primary"
                            :href="route('admin.blogs.create')"
                        >
                            Create New Blog
                        </InputButtonLink>
                    </div>
                    <table class="table-auto w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-600 text-white">
                                <th class="p-2 border-b border-zinc-500">ID</th>
                                <th class="p-2 border-b border-zinc-500">
                                    Title
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Poster
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Published on
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Last update on
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="blog in usePage().props.blogs.data"
                                :key="blog.id"
                                :class="'odd:bg-zinc-500 even:bg-zinc-400 hover:text-zinc-800 hover:bg-zinc-300 cursor-pointer'"
                                @click="onClickRow(blog)"
                            >
                                <td class="p-2 border-b border-zinc-500">
                                    {{ blog.id }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ blog.title }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ blog.poster.name }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(blog.created_at) }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(blog.updated_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span class="p-2 bg-zinc-600"> Click on a row to edit or delete the blog. </span>
                </div>
            </div>

            <Pagination :pagination="usePage().props.blogs" :darkmode="true" />
        </div>
    </AdminLayout>
</template>
<script setup>
console.log(usePage().props.blogs);

import { usePage } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates";

import Pagination from "@/Components/Pagination.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";

const onClickRow = (user) => {
    window.location.href = route("admin.blogs.edit", user);
};
</script>
