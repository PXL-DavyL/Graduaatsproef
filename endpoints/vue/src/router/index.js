import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth"; 
import { hasRole, hasPermission } from "@/composables/permissions";

// Views
import HomeView from "../views/Auth/HomeView.vue";
import LoginView from "@/views/Auth/LoginView.vue";
import RegisterView from "@/views/Auth/RegisterView.vue";
import ForgotPasswordView from "@/views/Auth/ForgotPasswordView.vue";
import ProfileView from "@/views/Profile/ProfileView.vue";
import AdminView from "@/views/Admin/AdminView.vue";
import AdminEditUserView from "@/views/Admin/User/AdminEditUserView.vue";

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
			meta: { requiresAuth: true },
		},
		{
			path: "/admin",
			name: "Admin",
			component: AdminView,
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/users/edit/:id",
			name: "AdminEditUser",
			component: AdminEditUserView,
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
	],
});

router.beforeEach(async(to, from, next) => {
	const authStore = useAuthStore();
	const isAuthenticated = authStore.isAuthenticated();

	// Authentication check
	if (to.meta.requiresAuth && !isAuthenticated) { // auth only
		next({ name: "login" });
		return;
	}
	else if (to.meta.requiresGuest && isAuthenticated) { // guest only
		next({ name: "home" });
		return;
	}

	// Roles and permissions
	else if (to.meta.requiresRole) { // role checker
		const role = await hasRole(to.meta.requiresRole);
		if (!role) {
			next({ name: "home" });
			return;
		}
	}
	else if (to.meta.requiresPermission) { // permission checker
		const permission = await hasPermission(to.meta.requiresPermission);
		if (!permission) {
			next({ name: "home" });
			return;
		}
	}


	next();
});

export default router;
