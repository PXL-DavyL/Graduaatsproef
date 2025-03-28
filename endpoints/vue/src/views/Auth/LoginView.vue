<template>
	<GuestLayout>
		<div>
			<form>
				<div>
					<InputText
						id="email"
						name="Email"
						type="email"
						class="mt-1 block w-full"
						v-model="email"
						required
						autofocus
						autocomplete="username"
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
				<div class="mt-4 block">
					<InputCheck text="Remember me" name="remember" v-model:checked="remember" />
				</div>
				<div class="mt-4 flex items-center justify-end gap-2">
					<router-link :to="{ name: 'forgot-password' }"
						class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
					>
						Forgot your password?
					</router-link>
					<InputButton type="primary" :disabled="loading" @click="handleLogin">
						{{ loading ? "Logging in..." : "Login" }}
					</InputButton>
				</div>
			</form>

			<div>
				<router-link :to="{ name: 'register' }"
					class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
					>Create an account</router-link
				>
			</div>
		</div>
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
import InputCheck from "@/components/InputCheck.vue";
import InputButton from "@/components/InputButton.vue";
import InputText from "@/components/InputText.vue";

const email = ref("");
const password = ref("");
const remember = ref(false);
const errors = ref({});

const loading = ref(false);
watch(
	() => authStore.loading,
	(value) => (loading.value = value),
);

const handleLogin = async () => {
	if (loading.value) {
		return;
	}

	try {
		await authStore.login({
			email: email.value,
			password: password.value,
		});

		toast.info("You have logged in. Welcome to the blog!");
		router.push("/");
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
</script>
