<template>
    <AdminLayout title="Dashboard">
        <div class="p-2">
            In the admin dashboard, you can get an overview of all existing
            users and blogs.
            <br />
            If you wish to edit one of the users or blogs on this page, simply
            click on the row and you will be redirected to the edit page.
        </div>

        <div class="mt-3 flex flex-col gap-5">
            <div class="flex flex-col border border-zinc-600 rounded">
                <span class="p-2 bg-zinc-700"> Latest users </span>
                <table class="table-auto w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-600 text-white">
                            <th class="p-2 border-b border-zinc-500">ID</th>
                            <th class="p-2 border-b border-zinc-500">Name</th>
                            <th class="p-2 border-b border-zinc-500">Email</th>
                            <th class="p-2 border-b border-zinc-500">
                                Created At
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in usePage().props.users"
                            :key="user.id"
                            class="odd:bg-zinc-500 even:bg-zinc-400 hover:text-zinc-800 hover:bg-zinc-300 cursor-pointer"
                            @click="onClickUserRow(user)"
                        >
                            <td class="p-2 border-b border-zinc-500">
                                {{ user.id }}
                            </td>
                            <td class="p-2 border-b border-zinc-500">
                                {{ user.name }}
                            </td>
                            <td class="p-2 border-b border-zinc-500">
                                {{ user.email }}
                            </td>
                            <td class="p-2 border-b border-zinc-500">
                                {{ formatDate(user.created_at) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <span class="p-2 bg-zinc-600">
                    <Link :href="route('admin.users.index')" class="hover:underline">
                        Browse all users
                    </Link>
                </span>
            </div>

            <div class="flex flex-col border border-zinc-600 rounded">
                <span class="p-2 bg-zinc-700"> Latest blog details </span>
                <table class="table-auto w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-600 text-white">
                            <th class="p-2 border-b border-zinc-500">ID</th>
                            <th class="p-2 border-b border-zinc-500">
                                Poster ID
                            </th>
                            <th class="p-2 border-b border-zinc-500">Title</th>
                            <th class="p-2 border-b border-zinc-500">Views</th>
                            <th class="p-2 border-b border-zinc-500">Comments</th>
                            <th class="p-2 border-b border-zinc-500">
                                Created At
                            </th>
                            <th class="p-2 border-b border-zinc-500">
                                Updated At
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="blog in usePage().props.blogs"
                            :key="blog.id"
                            class="odd:bg-zinc-500 even:bg-zinc-400 hover:text-zinc-800 hover:bg-zinc-300 cursor-pointer"
                            @click="onClickBlogRow(blog)"
                        >
                            <td class="p-2 border-b border-zinc-500">
                                {{ blog.id }}
                            </td>
                            <td class="p-2 border-b border-zinc-500">
                                {{ blog.poster.name }}
                            </td>
                            <td class="p-2 border-b border-zinc-500">
                                {{ blog.title }}
                            </td>
                            <td class="p-2 border-b border-zinc-500">
                                {{ blog.views }}
                            </td>
                            <td class="p-2 border-b border-zinc-500">
                                {{ blog.comments.length }}
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
                <span class="p-2 bg-zinc-600">
                    <Link :href="route('admin.blogs.index')" class="hover:underline">
                        Browse all blogs
                    </Link>
                </span>
            </div>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { usePage, Link } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates";

const onClickUserRow = (user) => {
    window.location.href = route("admin.users.edit", user);
};
const onClickBlogRow = (blog) => {
    window.location.href = route("admin.blogs.edit", blog);
};
</script>
