<template>
	<header>
		<div v-if="authStore.isAuthenticated()">
			Welcome {{ userData.name }}
			<button @click="onLogout">Logout</button>
		</div>
		<div class="flex gap-2" v-else>
			<router-link to="/login">Login</router-link>
			<router-link to="/register">Register</router-link>
		</div>
	</header>
	<main>
		<slot></slot>
	</main>
</template>
<script setup>
import { ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const router = useRouter();
const authStore = useAuthStore();

const userData = ref(authStore.getUserData());

console.log(userData.value);

const onLogout = () => {
	authStore.logout();
	router.push("/login");
};
</script>
