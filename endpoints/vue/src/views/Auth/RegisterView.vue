<template>
	<GuestLayout>
		<form>
			<div>
				<InputText
					id="name"
					name="Name"
					type="text"
					class="mt-1 block w-full"
					v-model="name"
					required
					autofocus
					autocomplete="name"
					:error="errors.name"
				/>
			</div>

			<div class="mt-4">
				<InputText
					id="email"
					name="Email"
					type="email"
					class="mt-1 block w-full"
					v-model="email"
					required
					autofocus
					autocomplete="email"
					:error="errors.email"
				/>
			</div>

			<div class="mt-4">
				<InputText
					id="password"
					name="Password"
					type="password"
					class="mt-1 block w-full"
					v-model="password"
					required
					autofocus
					:error="errors.password"
				/>
			</div>

			<div class="mt-4">
				<InputText
					id="password_confirmation"
					name="Confirm Password"
					type="password"
					class="mt-1 block w-full"
					v-model="password_confirmation"
					required
					autofocus
					:error="errors.password_confirmation"
				/>
			</div>

			<div class="mt-4 gap-2 flex items-center justify-end">
				<div>
					<router-link
						to="/login"
						class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
						>Already registered?</router-link
					>
				</div>

				<InputButton type="primary" :disabled="loading" @click="handleRegister">
					{{ loading ? "Registering..." : "Register" }}
				</InputButton>
			</div>
		</form>
	</GuestLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { toast } from "vue3-toastify";
import { useRouter } from "vue-router";
const router = useRouter();

import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();
import GuestLayout from "@/layout/GuestLayout.vue";
import InputButton from "@/components/InputButton.vue";
import InputText from "@/components/InputText.vue";

const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");
const errors = ref({});

const loading = ref(false);
watch(
	() => authStore.loading,
	(value) => (loading.value = value),
);

const handleRegister = async () => {
	try {
		const response = await authStore.register({
			name: name.value,
			email: email.value,
			password: password.value,
			password_confirmation: password_confirmation.value,
		});

		toast.info("You have successfully registered!");
		router.push("/login");
	} catch (error) {
		const errors = error.response.data.errors;
		for (const error in errors) {
			toast.error(errors[error]);
		}
		errors.value = error.response.data.errors;
	}
};
</script>
