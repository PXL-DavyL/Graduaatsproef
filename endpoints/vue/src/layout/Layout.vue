<template>
    <div class="font-sans antialiased"> 
        <div class="mt-4 container mx-auto max-h-screen flex flex-col gap-4">
            <header
                class="w-full p-4 bg-gray-50 flex items-center justify-between rounded shadow-md"
            >
                <div class="flex gap-2 items-center">
                    <div class="min-h-8 min-w-8">
                        <ApplicationLogo class="text-red-600" />
                    </div>
                    <div
                        class="w-full flex"
                        v-if="canWriteBlogs || isAdmin"
                    >

                        <router-link to="/blog/create" class="p-1 bg-gray-100 border border-gray-200 hover:bg-gray-200 hover:border-gray-300 text-gray-500 rounded">
                            <IconWrite class="h-6 w-6" />
                        </router-link>
                    </div>
                </div>

                <div class="flex gap-2">
                    <section v-if="!user">
                        <router-link
                            to="/login"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Log in
                        </router-link>
                    </section>
                    <section v-else class="relative">
                        <div
                            class="flex items-center gap-1 text-sm p-2 rounded-md hover:cursor-pointer hover:bg-gray-100 transition"
                            @click="showUserDropdown = !showUserDropdown"
                        >
                            <IconAccount class="h-4 w-4 text-gray-700" />
                            {{ user.name }}
                            <IconChevron
                                class="transition-transform duration-200"
                                :class="{ 'rotate-180': showUserDropdown }"
                            />
                        </div>

                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <div
                                v-if="showUserDropdown"
                                class="absolute right-0 mt-1 w-48 origin-top-right rounded-md bg-gray-50 py-1 shadow-lg border border-gray-100 focus:outline-none z-10"
                                ref="dropdownMenu"
                            >
                                <router-link
                                    to="/profile"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    <IconProfile class="h-4 w-4" />
                                    Profile
                                </router-link>
                                <router-link
                                    v-if="isAdmin"
                                    to="/admin"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    <IconHome class="h-4 w-4" />
                                    Admin
                                </router-link>
                                <button
                                    @click="onLogout"
                                    class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                                >
                                    <IconLogout class="h-4 w-4" />
                                    Log Out
                                </button>
                            </div>
                        </transition>
                    </section>
                </div>
            </header>

            <main class="w-full bg-white p-4 bg-gray-100 rounded shadow-md">
                <section class="border-b border-gray-300 w-full mb-4">
                    <h1 class="text-2xl font-semibold text-gray-800">
                        {{ props.title }}
                    </h1>
                </section>

                <slot />
            </main>

            <footer
                class="w-full flex justify-between p-4 bg-gray-50 rounded shadow-md"
            >
                <span>Laravel v{{ laravel_Version }}</span>
                <span>PHP v{{ php_Version }}</span>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref, onBeforeMount, onMounted, onUnmounted } from "vue";
import { hasPermission, hasRole } from "@/composables/permissions.js";

import { getMetaData } from "@/stores/metadata";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const router = useRouter();
const authStore = useAuthStore();

const laravel_Version = ref('');
const php_Version = ref('');

import ApplicationLogo from "@/components/ApplicationLogo.vue";
import IconChevron from "@/components/icons/IconChevron.vue";
import IconProfile from "@/components/icons/IconProfile.vue";
import IconLogout from "@/components/icons/IconLogout.vue";
import IconAccount from "@/components/icons/IconAccount.vue";
import IconHome from "@/components/icons/Admin/IconHome.vue";
import IconWrite from "@/components/icons/IconWrite.vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});

// Permission props
const isAdmin = ref(false);
const canWriteBlogs = ref(false);

const user = ref();
const showUserDropdown = ref(false);

onBeforeMount(async() => {
    user.value = await authStore.getUserData();
    const metadata = await getMetaData();

    laravel_Version.value = metadata.data.laravelVersion;
    php_Version.value = metadata.data.phpVersion;

    // Fetch permissions
    isAdmin.value = await hasRole('admin');
    canWriteBlogs.value = await hasPermission('create blog');
});

onMounted(() => document.addEventListener("click", handleClickOutside));
onUnmounted(() => document.removeEventListener("click", handleClickOutside));

const handleClickOutside = (event) => {
    if (showUserDropdown.value && !event.target.closest(".relative")) {
        showUserDropdown.value = false;
    }
};

const onLogout = async() => {

    try {
        await authStore.logout();
    } catch (error) {
        console.error(error);
    }
    finally {
        router.push("/login");
    }
};
</script>
