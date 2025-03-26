<template>
	<AdminLayout title="Manage Users">
		<div class="flex flex-col gap-4">
			<div class="mt-3 flex flex-col gap-5">
				<div class="flex flex-col border border-zinc-600 rounded">
					<div class="p-2 bg-zinc-700 flex justify-between">
						<span> User List </span>
						<InputButtonLink type="primary" to="/admin/users/create">
							Create New User
						</InputButtonLink>
					</div>
					<table class="table-auto w-full text-left border-collapse">
						<thead>
							<tr class="bg-zinc-600 text-white">
								<th class="p-2 border-b border-zinc-500">ID</th>
								<th class="p-2 border-b border-zinc-500">Name</th>
								<th class="p-2 border-b border-zinc-500">Email</th>
								<th class="p-2 border-b border-zinc-500">Created At</th>
								<th class="p-2 border-b border-zinc-500">Updated At</th>
							</tr>
						</thead>
						<tbody>
							<tr
								v-for="user in users"
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
					<span class="p-2 bg-zinc-600">
						Click on a row to edit or delete the user.
					</span>
				</div>
			</div>

			<div class="w-full flex gap-2 justify-center items-center">
				<span
					@click="prevPage"
					class="p-2 rounded"
					:class="{
						'bg-zinc-600 border border-zinc-700 hover:bg-zinc-500 text-zinc-300 cursor-pointer':
							currentPage !== 0,
						'bg-zinc-600 border border-zinc-700 text-gray-400 cursor-default':
							currentPage === 0,
					}"
					>Previous</span
				>

				<div class="flex gap-2 items-center">
					<span
						v-for="key in getPageList()"
						@click="handlePageClick(key)"
						class="p-2 px-3 border"
						:class="{
							'bg-zinc-600 border-zinc-700 hover:bg-zinc-500 text-zinc-200 rounded cursor-pointer':
								currentPage !== key,
							'bg-indigo-500 hover:bg-indigo-400 border-indigo-600 text-indigo-200 rounded cursor-default':
								currentPage === key,
						}"
					>
						{{ formatPageName(key) }}
					</span>
				</div>

				<span
					@click="nextPage"
					class="p-2 rounded"
					:class="{
						'bg-zinc-600 border border-zinc-700 hover:bg-zinc-500 text-zinc-300 cursor-pointer':
							currentPage !== getMaxPage(),
						'bg-zinc-600 border border-zinc-700 text-gray-400 cursor-default':
							currentPage === getMaxPage(),
					}"
					>Next</span
				>
			</div>
		</div>
	</AdminLayout>
</template>
<script setup>
import { formatDate } from "@/composables/dates";

import AdminLayout from "@/layout/AdminLayout.vue";
import InputButtonLink from "@/components/InputButtonLink.vue";
import { onBeforeMount } from "vue";
import { useUserStore } from "@/stores/users";
import { ref } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();
const userStore = useUserStore();

const onClickRow = (user) => {
	router.push({ name: "AdminEditUser", params: { id: user.id } });
};

const users = ref({});
const allUsers = ref({});
onBeforeMount(async () => {
	const response = await userStore.getAllUsers();
	allUsers.value = await response.data.users;

	renderUserList();
});

// Pagination
const currentPage = ref(0);
const itemsPerPage = 10;

const renderUserList = () => {
	const new_data = allUsers.value.slice(
		currentPage.value * itemsPerPage,
		currentPage.value * itemsPerPage + itemsPerPage,
	);
	users.value = new_data;
};

const nextPage = () => {
	currentPage.value = currentPage.value + 1;
	if (currentPage.value > getMaxPage()) {
		currentPage.value = getMaxPage();
	}
	renderUserList();
};

const getMaxPage = () => {
	return Math.floor(allUsers.value.length / itemsPerPage);
};

const getPageList = () => {
	const pages = [];
	for (let i = 0; i <= Math.floor(allUsers.value.length / itemsPerPage); i++) {
		pages.push(i);
	}
	return pages;
};

const prevPage = () => {
	currentPage.value = currentPage.value - 1;
	if (currentPage.value < 0) {
		currentPage.value = 0;
	}
	renderUserList();
};

const handlePageClick = (page) => {
	currentPage.value = page;
	renderUserList();
};

const formatPageName = (value) => {
    return value + 1;
}
</script>
