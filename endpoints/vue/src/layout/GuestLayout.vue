<template>
	<header>
		<router-link to="/">Home</router-link>
	</header>
	<main>
		<slot></slot>
	</main>
</template>
<script setup>
import { useRouter } from "vue-router";
const router = useRouter();
</script>
