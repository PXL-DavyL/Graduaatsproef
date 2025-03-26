<template>
	<AdminLayout title="Dashboard">
		<div class="p-2">
			In the admin dashboard, you can get an overview of all existing users and blogs.
			<br />
			If you wish to edit one of the users or blogs on this page, simply click on the row and
			you will be redirected to the edit page.
		</div>

		{{ loadedUsers }}
		<div class="mt-3 flex flex-col gap-5">
			<div v-if="!loadedUsers" class="w-full flex flex-col gap-2 justify-center items-center">
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
					<router-link to="/admin/users" class="hover:underline">
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
						<tr>
							<td class="p-2 border-b border-zinc-500">NaN</td>
							<td class="p-2 border-b border-zinc-500">NaN</td>
							<td class="p-2 border-b border-zinc-500">NaN</td>
							<td class="p-2 border-b border-zinc-500">NaN</td>
							<td class="p-2 border-b border-zinc-500">NaN</td>
							<td class="p-2 border-b border-zinc-500">NaN</td>
							<td class="p-2 border-b border-zinc-500">NaN</td>
						</tr>
					</tbody>
				</table>
				<span class="p-2 bg-zinc-600">
					<router-link to="/admin/blogs" class="hover:underline">
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

const loadedUsers = ref(false);
const users = ref([]);

onBeforeMount(async () => {
	users.value = await getUsers();
});

const getUsers = async () => {
	loadedUsers.value = false;
	try {
		await getCsrfToken();
		const response = await axios.get("http://localhost:8000/api/admin/users", {
			headers: {
				Accept: "application/json",
				"Content-Type": "application/json",
			},
			withCredentials: true,
		});
        console.log(response);
		loadedUsers.value = true;
		return response.data.users;
	} catch (error) {
		loadedUsers.value = false;
		console.error("Get users failed:", error);
		throw error;
	}
};

const onClickUserRow = (user) => {
	router.push({ name: "AdminEditUser", params: { id: user.id } });
};
</script>
