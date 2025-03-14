<template>
    <AdminLayout title="Dashboard">
        <div class="p-2">
            In the admin dashboard, you can get an overview of all existing users and blogs. You will be able to modify both appropriately.
        </div>

        <div class="mt-3 flex flex-col gap-5">
            <div class="flex flex-col border border-neutral-600 rounded">
                <span class="p-2 bg-neutral-600">
                    Latest users
                </span>
                <span v-for="user in usePage().props.users" :key="user.id" class="p-2 bg-neutral-500 border-b border-neutral-600 rounded flex gap-2 items-center">
                    <div class="text-neutral-800 bg-neutral-200 border border-neutral-400 mr-2 p-1 rounded">
                        <IconEdit class="w-4 h-4" />
                    </div>
                    {{ user.name }}
                </span>
                <span class="p-2 bg-neutral-600">
                    Browse all users
                </span>
            </div>
            <div class="flex flex-col border border-neutral-600 rounded">
                <span class="p-2 bg-neutral-600">
                    Latest blogs
                </span>
                <span v-for="blog in usePage().props.blogs" :key="blog.id" class="p-2 bg-neutral-500 border-b border-neutral-600 rounded flex gap-2 items-center">
                    <div class="text-neutral-800 bg-neutral-200 border border-neutral-400 mr-2 p-1 rounded">
                        <IconEdit class="w-4 h-4" />
                    </div>
                    {{ blog.title }}
                </span>
                <span class="p-2 bg-neutral-600">
                    Browse all blogs
                </span>
            </div>
        </div>
    </AdminLayout>
</template>
<script setup>
import IconEdit from "@/Components/icons/Admin/IconEdit.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { usePage } from "@inertiajs/vue3";

console.log(usePage.props);
</script>
