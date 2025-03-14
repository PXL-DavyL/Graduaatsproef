<template>
    <AdminLayout title="Manage Users">
        <div class="flex flex-col gap-4">
            <span class="text-2xl font-bold">Users</span>

            <div class="mt-3 flex flex-col gap-5">
                <div class="flex flex-col border border-zinc-600 rounded">
                    <span class="p-2 bg-zinc-700"> Users </span>
                    <table class="table-auto w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-600 text-white">
                                <th class="p-2 border-b border-zinc-500">
                                    ID
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Name
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Email
                                </th>
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
                                v-for="user in usePage().props.users.data"
                                :key="user.id"
                                class="odd:bg-zinc-500 even:bg-zinc-400"
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
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(user.updated_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span class="p-2 bg-zinc-600"> Browse all users </span>
                </div>
            </div>

            <Pagination :pagination="usePage().props.users" :darkmode="true" />
        </div>
    </AdminLayout>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates";
import Pagination from "@/Components/Pagination.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

console.log(usePage().props.users);
</script>
