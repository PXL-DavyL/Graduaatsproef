<template>
	<AdminLayout title="Manage Comments">
		<div class="flex flex-col gap-4">
			<div class="mt-3 flex flex-col gap-5">
				<div class="flex flex-col border border-zinc-600 rounded">
					<div class="p-2 bg-zinc-700 flex justify-between">
						<span> Comment list for blog id {{ useRoute().params.id }}</span>
					</div>
					<table class="table-auto w-full text-left border-collapse">
						<thead>
							<tr class="bg-zinc-600 text-white">
								<th class="p-2 border-b border-zinc-500">ID</th>
								<th class="p-2 border-b border-zinc-500">Content</th>
								<th class="p-2 border-b border-zinc-500">Poster</th>
								<th class="p-2 border-b border-zinc-500">Created on</th>
								<th class="p-2 border-b border-zinc-500">Updated on</th>
							</tr>
						</thead>
						<tbody>
							<tr
								v-for="comment in comments"
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
					<span class="p-2 bg-zinc-600">
						Click on a row to edit or delete the comment.
					</span>
				</div>

				<Pagination
					:totalItems="all_comments"
					:onPageChange="getPaginationData"
					@update-pagination-data="getPaginationData"
					:darkMode="true"
				/>
			</div>
		</div>
		<InputButtonLink type="secondary" class="mt-4 w-full" name="AdminBlogs">
			Back to blog
		</InputButtonLink>
	</AdminLayout>
</template>
<script setup>
import { formatDate } from "@/composables/dates";

import Pagination from "@/components/Pagination.vue";
import AdminLayout from "@/layout/AdminLayout.vue";
import InputButtonLink from "@/components/InputButtonLink.vue";

import { useCommentStore } from "@/stores/comments";
const commentStore = useCommentStore();
import { onBeforeMount, ref } from "vue";

import { useRoute, useRouter } from "vue-router";
const router = useRouter();

const blog = ref([]);
const comments = ref([]);
const all_comments = ref([]);
onBeforeMount(async () => {
	try {
		const response = await commentStore.getCommentsAsAdmin({
			id: useRoute().params.id,
		});

		blog.value = response.data.blog;
		all_comments.value = response.data.comments;
	} catch (error) {
		console.log(error);
	}
});

const onClickRow = (comment) => {
	router.push({
		name: "AdminEditBlogComment",
		params: { blog: blog.value.id, comment: comment.id },
	});
};

const getPaginationData = (data, page) => {
	comments.value = data;
	//currentPage.value = page;
};
</script>
