<template>
	<section>
		<header>
			<h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

			<p class="mt-1 text-sm text-gray-600">
				Update your account's profile information and email address.
			</p>
		</header>

		<form class="mt-6 space-y-6">
			<div>
				<InputText
					id="name"
					name="Name"
					type="text"
					class="mt-1 block w-full"
					v-model="name"
					autofocus
					autocomplete="name"
					:error="errors.name"
				/>
			</div>

			<div>
				<InputText
					id="email"
					name="Email"
					type="email"
					class="mt-1 block w-full"
					v-model="email"
					required
					autocomplete="username"
					:error="errors.email"
				/>
			</div>

			<div class="flex items-center gap-4">
				<InputButton :disabled="loading" @click="updateProfile">Save</InputButton>
			</div>
		</form>
	</section>
</template>
<script setup>
import InputButton from "@/components/InputButton.vue";
import InputText from "@/components/InputText.vue";
import { useProfileStore } from "@/stores/profile";
const profileStore = useProfileStore();
import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();

import { onMounted, ref, watch } from "vue";
import { toast } from "vue3-toastify";

const name = ref("");
const email = ref("");
const errors = ref({});

onMounted(async () => {
	await resetForm();
});

const loading = ref(false);
watch(
	() => profileStore.loading,
	(value) => (loading.value = value),
);

const updateProfile = async () => {
	if (loading.value) {
		return;
	}

	try {
		await profileStore.updateProfile({
			name: name.value,
			email: email.value,
		});

		toast.info("You have changed your profile information!");
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

const resetForm = async () => {
	const response = await authStore.fetchUser();
	name.value = response.data.name;
	email.value = response.data.email;
};
</script>
