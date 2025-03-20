<template>
    <AdminLayout title="Manage Reactions">
        <div class="flex flex-col gap-4">
            <div class="mt-3 flex flex-col gap-5">
                <div class="flex flex-col border border-zinc-600 rounded">
                    <div class="p-2 bg-zinc-700 flex justify-between">
                        <span>
                            Reaction list for blog id
                            {{ usePage().props.blog.id }}</span
                        >
                    </div>
                    <table class="table-auto w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-600 text-white">
                                <th class="p-2 border-b border-zinc-500">ID</th>
                                <th class="p-2 border-b border-zinc-500">
                                    Type
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
                                v-for="reaction in usePage().props.reactions
                                    .data"
                                :key="reaction.id"
                                class="cursor-pointer"
                                :class="
                                    reaction.type === 'upvote'
                                        ? 'bg-emerald-200 hover:bg-emerald-300 text-emerald-800'
                                        : 'bg-rose-200 hover:bg-rose-300 text-rose-800'
                                "
                                @click="onClickRow(reaction)"
                            >
                                <td class="p-2 border-b border-zinc-500">
                                    {{ reaction.id }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ reaction.type }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ reaction.poster.name }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(reaction.created_at) }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(reaction.updated_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span class="p-2 bg-zinc-600">
                        Click on a row to edit or delete the reaction.
                    </span>
                </div>
            </div>

            <Pagination
                :pagination="usePage().props.reactions"
                :darkmode="true"
            />
        </div>
        <InputButtonLink
            type="secondary"
            class="mt-4 w-full"
            :href="route('admin.blogs.show', { blog: usePage().props.blog })"
        >
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

const onClickRow = (reaction) => {
    window.location.href = route("admin.reactions.edit", {
        blog: usePage().props.blog.id,
        reaction: reaction.id,
    });
};
</script>
