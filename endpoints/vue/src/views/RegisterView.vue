<template>
	<GuestLayout>
		<form @submit.prevent="handleRegister">
			<div>
				<label for="name">Name</label>
				<input id="name" v-model="name" type="text" required placeholder="Your name" />
			</div>

			<div>
				<label for="email">Email</label>
				<input
					id="email"
					v-model="email"
					type="email"
					required
					placeholder="your@email.com"
				/>
			</div>

			<div>
				<label for="password">Password</label>
				<input
					id="password"
					v-model="password"
					type="password"
					required
					placeholder="Your password (min. 8 characters)"
				/>
			</div>

			<div>
				<label for="password_confirmation">Confirm Password</label>
				<input
					id="password_confirmation"
					v-model="password_confirmation"
					type="password"
					required
					placeholder="Confirm your password"
				/>
			</div>

			<button type="submit" :disabled="authStore.loading">
				{{ authStore.loading ? "Registering..." : "Register" }}
			</button>
		</form>

		<div>
			Already have an account?
			<router-link to="/login">Login</router-link>
		</div>
	</GuestLayout>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

import { useAuthStore } from "@/stores/auth";
import GuestLayout from "@/layout/GuestLayout.vue";

const authStore = useAuthStore();
const router = useRouter();

const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");

const handleRegister = async () => {
	try {
		const response = await authStore.register({
			name: name.value,
			email: email.value,
			password: password.value,
			password_confirmation: password_confirmation.value,
		});

		router.push("/login");
	} catch (error) {
		console.log(error.response.data.errors);
	}
};
</script>
