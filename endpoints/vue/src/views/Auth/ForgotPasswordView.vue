<template>
	<GuestLayout>
		<div class="mb-4 text-sm text-gray-600">
			Forgot your password? No problem. Just let us know your email address and we will email
			you a password reset link that will allow you to choose a new one.
		</div>

		<div v-if="status" class="mb-4 text-sm font-medium text-green-600">
			{{ status }}
		</div>

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

			<div class="mt-4 flex items-center justify-end gap-2">
				<InputButton type="primary" :disabled="loading" @click="handleForgotPass">
					{{ loading ? "Sending e-mail..." : "Email Password Reset Link" }}
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

const email = ref("");
const errors = ref({});

const loading = ref(false);
const status = ref("");
watch(
	() => authStore.loading,
	(value) => (loading.value = value),
);

const handleForgotPass = async () => {
	if (loading.value) {
		return;
	}

	try {
		const response = await authStore.forgot_pass({
			email: email.value,
		});
		status.value = response.data.status;
		toast.info("We have sent an email containing a password reset link.");
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
