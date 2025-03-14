<template>
    <AdminLayout title="Manage Users">
        <span class="text-2xl font-bold">Users</span>

        <table class="table-auto w-full mt-4 border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Created At</th>
                    <th class="border border-gray-300 px-4 py-2">Updated At</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in usePage().props.users.data" :key="user.id" class="hover:bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ user.id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ user.name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ user.email }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ user.created_at }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ user.updated_at }}</td>
                </tr>
            </tbody>
        </table>

        <Pagination :pagination="usePage().props.users" />
    </AdminLayout>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

console.log(usePage().props.users);
</script>
