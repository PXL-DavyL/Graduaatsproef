<template>
	<AdminLayout title="Edit User">
		<form class="flex flex-col gap-4">
			<div class="flex flex-col gap-1">
				<span class="block text-sm font-medium text-zinc-300"> User ID: </span>
				<span
					class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
				>
					{{ user_id }}
				</span>
			</div>

			<InputText
				id="name"
				name="Name"
				type="text"
				v-model="name"
				required
				autocomplete="name"
				:error="errors.name"
				:darkmode="true"
			/>

			<InputText
				id="email"
				name="Email"
				type="email"
				v-model="email"
				required
				autocomplete="email"
				:error="errors.email"
				:darkmode="true"
			/>

			<InputText
				id="password"
				name="Password"
				type="password"
				v-model="password"
				autocomplete="password"
				:error="errors.password"
				:darkmode="true"
			/>
			<InputText
				id="password_confirmation"
				name="Confirm password"
				type="password"
				v-model="password_confirmation"
				autocomplete="password_confirmation"
				:error="errors.password_confirmation"
				:darkmode="true"
			/>

			<div class="flex flex-col gap-1">
				<span class="block text-sm font-medium text-zinc-300"> Registered at: </span>
				<span
					class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
				>
					{{ formatDate(user.created_at) }}
				</span>
			</div>
			<div class="flex flex-col gap-1">
				<span class="block text-sm font-medium text-zinc-300"> Last update at: </span>
				<span
					class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
				>
					{{ formatDate(user.updated_at) }}
				</span>
			</div>

			<InputButton :class="{ 'opacity-25': loading }" :disabled="loading" @click="editUser">
				Save User
			</InputButton>

            <Permissions/>
		</form>
	</AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layout/AdminLayout.vue";
import { useRoute, useRouter } from "vue-router";
import { useUserStore } from "@/stores/users";
import { onBeforeMount, ref, watch } from "vue";
import InputText from "@/components/InputText.vue";
import InputButton from "@/components/InputButton.vue";
import { formatDate } from "@/composables/dates";
import { toast } from "vue3-toastify";
import Permissions from "./Partials/Permissions.vue";

const userStore = useUserStore();
const router = useRouter();

// form data
const user_id = useRoute().params.id;
const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");

const errors = ref({});

// fetch user on page load based on params
const user = ref({});
onBeforeMount(async () => {
	const response = await userStore.getUser({
		user: user_id,
	});

	user.value = response.data.user;
	name.value = user.value.name;
	email.value = user.value.email;

	// If the user doesn't exist...
	if (user.value === null) {
		router.back();
		return;
	}
});

const loading = ref(false);
watch(
	() => userStore.loading,
	(value) => (loading.value = value),
);

const editUser = async () => {
	try {
		const response = await userStore.saveUser({
			name: name.value,
			email: email.value,
			password: password.value,
			password_confirmation: password_confirmation.value,
			user: user_id,
		});

		toast.success("User updated successfully!");
	} catch (error) {
		if (error.response) {
			errors.value = {};
			const response_errors = error.response.data.errors;
			for (const error in response_errors) {
				toast.error(response_errors[error]);
				errors.value[error] = response_errors[error][0];
			}

			resetForm();
		}
	}
};

const resetForm = () => {
	password.value = "";
	password_confirmation.value = "";
};
</script>
