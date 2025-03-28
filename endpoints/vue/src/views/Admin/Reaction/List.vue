<template>
    <AdminLayout title="Manage Reactions">
        <div class="flex flex-col gap-4">
            <div class="mt-3 flex flex-col gap-5">
                <div class="flex flex-col border border-zinc-600 rounded">
                    <div class="p-2 bg-zinc-700 flex justify-between">
                        <span>
                            Reaction list for blog id
                            {{ useRoute().params.id }}</span
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
                                v-for="reaction in reactions"
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
					:totalItems="all_reactions"
					:onPageChange="getPaginationData"
					@update-pagination-data="getPaginationData"
					:darkMode="true"
				/>
        </div>
    </AdminLayout>

</template>
<script setup>
import AdminLayout from '@/layout/AdminLayout.vue';
import { onBeforeMount, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
const router = useRouter();
const blog_id = useRoute().params.id;
import { formatDate } from '@/composables/dates';
import Pagination from '@/components/Pagination.vue';

import { useReactionStore } from '@/stores/reactions';
const reactionStore = useReactionStore();

const all_reactions = ref([]);
const reactions = ref([]);

onBeforeMount(async() => {
    try {
        const response = await reactionStore.getReactionsAsAdmin({
            id: useRoute().params.id,
        });
        all_reactions.value = response.data.reactions;
    }
    catch(error) {
        console.log(error);
    }
});

const getPaginationData = (data, page) => {
    reactions.value = data;
}
const onClickRow = (reaction) => {
    console.log(blog_id);
	router.push({ name: "AdminEditBlogReaction", params: { blog: blog_id, reaction: reaction.id } });
};


</script>