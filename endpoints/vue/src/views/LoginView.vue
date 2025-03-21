<template>
	<GuestLayout>
		<div>
			<form @submit.prevent="handleLogin">
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
						placeholder="Your password"
					/>
				</div>

				<button type="submit">Login</button>
			</form>

			<div>
				Don't have an account?
				<router-link to="/register">Register</router-link>
			</div>
		</div>
	</GuestLayout>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

import { useAuthStore } from "@/stores/auth";
import GuestLayout from "@/layout/GuestLayout.vue";
const authStore = useAuthStore();

const router = useRouter();

const email = ref("");
const password = ref("");

const handleLogin = async () => {
	try {
		await authStore.login({
			email: email.value,
			password: password.value,
		});

		router.push("/");
	} catch (error) {
		console.log(error.response.data.errors);
	}
};
</script>
