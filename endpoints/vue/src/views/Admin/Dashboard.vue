<template>
	<AdminLayout title="Dashboard">
		<div class="p-2">
			In the admin dashboard, you can get an overview of all existing users and blogs.
			<br />
			If you wish to edit one of the users or blogs on this page, simply click on the row and
			you will be redirected to the edit page.
		</div>

		<div class="mt-3 flex flex-col gap-5">
			<div
				v-if="!loadedIndexProps"
				class="w-full flex flex-col gap-2 justify-center items-center"
			>
				<span class="font-xl">Loading users...</span>
				<IconLoading class="w-16 h-16" />
			</div>
			<div class="flex flex-col border border-zinc-600 rounded" v-else>
				<span class="p-2 bg-zinc-700"> Latest users </span>
				<table class="table-auto w-full text-left border-collapse">
					<thead>
						<tr class="bg-zinc-600 text-white">
							<th class="p-2 border-b border-zinc-500">ID</th>
							<th class="p-2 border-b border-zinc-500">Name</th>
							<th class="p-2 border-b border-zinc-500">Email</th>
							<th class="p-2 border-b border-zinc-500">Created At</th>
						</tr>
					</thead>
					<tbody>
						<tr
							v-for="user in users"
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
								{{ new Date(user.created_at).toLocaleDateString() }}
							</td>
						</tr>
					</tbody>
				</table>
				<span class="p-2 bg-zinc-600">
					<router-link :to="{ name: 'AdminUsers'}" class="hover:underline">
						Browse all users
					</router-link>
				</span>
			</div>

			<div class="flex flex-col border border-zinc-600 rounded">
				<span class="p-2 bg-zinc-700"> Latest blog details </span>
				<table class="table-auto w-full text-left border-collapse">
					<thead>
						<tr class="bg-zinc-600 text-white">
							<th class="p-2 border-b border-zinc-500">ID</th>
							<th class="p-2 border-b border-zinc-500">Poster ID</th>
							<th class="p-2 border-b border-zinc-500">Title</th>
							<th class="p-2 border-b border-zinc-500">Views</th>
							<th class="p-2 border-b border-zinc-500">Comments</th>
							<th class="p-2 border-b border-zinc-500">Created At</th>
							<th class="p-2 border-b border-zinc-500">Updated At</th>
						</tr>
					</thead>
					<tbody>
						<tr
							v-for="blog in blogs"
							:key="blog.id"
							class="odd:bg-zinc-500 even:bg-zinc-400 hover:text-zinc-800 hover:bg-zinc-300 cursor-pointer"
							@click="onClickBlogRow(blog)"
						>
							<td class="p-2 border-b border-zinc-500">
								{{ blog.id }}
							</td>
							<td class="p-2 border-b border-zinc-500">
								{{ blog.poster_id }}
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
								{{ new Date(blog.created_at).toLocaleDateString() }}
							</td>
							<td class="p-2 border-b border-zinc-500">
								{{ new Date(blog.updated_at).toLocaleDateString() }}
							</td>
						</tr>
					</tbody>
				</table>
				<span class="p-2 bg-zinc-600">
					<router-link :to="{ name: 'AdminBlogs'}" class="hover:underline">
						Browse all blogs
					</router-link>
				</span>
			</div>
		</div>
	</AdminLayout>
</template>
<script setup>
import { onBeforeMount, ref } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();
import { getCsrfToken } from "@/stores/utils";
import axios from "axios";

import AdminLayout from "@/layout/AdminLayout.vue";
import IconLoading from "@/components/icons/IconLoading.vue";

const loadedIndexProps = ref(false);
const users = ref([]);
const blogs = ref([]);

onBeforeMount(async () => {
	const response = await loadProps();
	users.value = response.users;
	blogs.value = response.blogs;
});

const loadProps = async () => {
	loadedIndexProps.value = false;
	try {
		await getCsrfToken();
		const response = await axios.get("http://localhost:8000/api/admin", {
			headers: {
				Accept: "application/json",
				"Content-Type": "application/json",
			},
			withCredentials: true,
		});
		loadedIndexProps.value = true;
		return response.data;
	} catch (error) {
		loadedIndexProps.value = false;
		console.error("Get users failed:", error);
		throw error;
	}
};

const onClickUserRow = (user) => {
	router.push({ name: "AdminEditUser", params: { id: user.id } });
};
const onClickBlogRow = (blog) => {
	router.push({ name: "AdminEditBlog", params: { id: blog.id } });
};
</script>
