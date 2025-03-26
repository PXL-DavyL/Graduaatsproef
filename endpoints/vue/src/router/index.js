import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth"; 
import { hasRole, hasPermission } from "@/composables/permissions";

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		{
			path: "/",
			name: "home",
			component: () => import("../views/Auth/HomeView.vue"),
		},
		{
			path: "/login",
			name: "login",
			component: () => import("@/views/Auth/LoginView.vue"),
			meta: { requiresGuest: true },
		},
		{
			path: "/forgot-password",
			name: "forgot-password",
			component: () => import("@/views/Auth/ForgotPasswordView.vue"),
			meta: { requiresGuest: true },
		},
		{
			path: "/register",
			name: "register",
			component: () => import("@/views/Auth/RegisterView.vue"),
			meta: { requiresGuest: true },
		},
		{
			path: "/profile",
			name: "Profile",
			component: () => import("@/views/Profile/ProfileView.vue"),
			meta: { requiresAuth: true },
		},
		{
			path: "/admin",
			name: "Admin",
			component: () => import("@/views/Admin/Index.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/users/index",
			name: "Admin Users",
			component: () => import("@/views/Admin/List.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/users/edit/:id",
			name: "AdminEditUser",
			component: () => import("@/views/Admin/User/AdminEditUserView.vue"),
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
