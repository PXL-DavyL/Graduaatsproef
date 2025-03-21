import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/Auth/HomeView.vue";
import LoginView from "@/views/Auth/LoginView.vue";
import RegisterView from "@/views/Auth/RegisterView.vue";
import ForgotPasswordView from "@/views/Auth/ForgotPasswordView.vue";
import ProfileView from "@/views/Profile/ProfileView.vue";

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		{
			path: "/",
			name: "home",
			component: HomeView,
		},
		{
			path: "/login",
			name: "login",
			component: LoginView,
			meta: { requiresGuest: true },
		},
		{
			path: "/forgot-password",
			name: "forgot-password",
			component: ForgotPasswordView,
			meta: { requiresGuest: true },
		},
		{
			path: "/register",
			name: "register",
			component: RegisterView,
			meta: { requiresGuest: true },
		},
		{
			path: "/profile",
			name: "Profile",
			component: ProfileView,
			meta: { requiresGuest: true },
		}
	],
});

export default router;
