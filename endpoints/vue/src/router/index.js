import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth"; 
import { useBlogStore } from "@/stores/blogs"; 
import { hasRole, hasPermission } from "@/composables/permissions";

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		{
			path: "/",
			name: "home",
			component: () => import("@/views/HomeView.vue"),
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
			path: "/blog/create",
			name: "CreateBlog",
			component: () => import("@/views/Blog/Create.vue"),
			meta: { requiresAuth: true },
		},
		{
			path: "/blog/:id",
			name: "ShowBlog",
			component: () => import("@/views/Blog/Show.vue"),
			meta: { requiresAuth: true },
		},
		{
			path: "/blog/:id",
			name: "EditBlog",
			component: () => import("@/views/Blog/Edit.vue"),
			meta: { requiresAuth: true, checkBlogAuthor: true },
		},
		{
			path: "/admin",
			name: "Admin",
			component: () => import("@/views/Admin/Dashboard.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/users",
			name: "AdminUsers",
			component: () => import("@/views/Admin/User/List.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},

		{
			path: "/admin/users/create",
			name: "AdminCreateUser",
			component: () => import("@/views/Admin/User/Create.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/users/:id",
			name: "AdminEditUser",
			component: () => import("@/views/Admin/User/Edit.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/blogs",
			name: "AdminBlogs",
			component: () => import("@/views/Admin/Blog/List.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/blogs/create",
			name: "AdminCreateBlog",
			component: () => import("@/views/Admin/Blog/Create.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/blogs/:id",
			name: "AdminEditBlog",
			component: () => import("@/views/Admin/Blog/Edit.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/blogs/:id/comment",
			name: "AdminListBlogComments",
			component: () => import("@/views/Admin/Comment/List.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/blogs/:id/reaction",
			name: "AdminListBlogReactions",
			component: () => import("@/views/Admin/Reaction/List.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/blogs/:blog/comment/:comment",
			name: "AdminEditBlogComment",
			component: () => import("@/views/Admin/Comment/Edit.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		},
		{
			path: "/admin/blogs/:blog/reaction/:reaction",
			name: "AdminEditBlogReaction",
			component: () => import("@/views/Admin/Reaction/Edit.vue"),
			meta: { requiresAuth: true, requiresRole: 'admin'},
		}
	],
});

router.beforeEach(async(to, from, next) => {
	const authStore = useAuthStore();
	const blogStore = useBlogStore();
	const isAuthenticated = authStore.isAuthenticated();
	const user = await authStore.getUserData();

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

	// Blog related
	else if(to.meta.checkBlogAuthor) { // match author with auth user
		const blogId = to.params.id;
		const blog = await blogStore.getBlog({id: blogId});
		if (user.id !== blog.data.blog.poster_id) {
			next({ name: "home" });
			return;
		}
	}


	next();
});

export default router;
