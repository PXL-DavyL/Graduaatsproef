<template>
    <AdminLayout title="Manage Users">
        <div class="flex flex-col gap-4">
            <div class="mt-3 flex flex-col gap-5">
                <div class="flex flex-col border border-zinc-600 rounded">
                    <div class="p-2 bg-zinc-700 flex justify-between">
                        <span> User List </span>
                        <InputButtonLink
                            type="primary"
                            :href="route('admin.users.create')"
                        >
                            Create New User
                        </InputButtonLink>
                    </div>
                    <table class="table-auto w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-600 text-white">
                                <th class="p-2 border-b border-zinc-500">ID</th>
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
                                :class="'odd:bg-zinc-500 even:bg-zinc-400 hover:text-zinc-800 hover:bg-zinc-300 cursor-pointer'"
                                @click="onClickRow(user)"
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
                    <span class="p-2 bg-zinc-600"> Click on a row to edit or delete the user. </span>
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
import InputButtonLink from "@/Components/InputButtonLink.vue";

const onClickRow = (user) => {
    window.location.href = route("admin.users.edit", user);
};
</script>
