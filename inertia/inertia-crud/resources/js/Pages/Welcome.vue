<template>
    <Head title="Home" />

    <div
        id="layout"
        class="mt-4 container mx-auto min-h-screen flex flex-col gap-4"
    >
        <header class="w-full p-4 bg-gray-50 flex justify-between rounded">
            <div class="min-h-8 min-w-8">
                <ApplicationLogo class="text-red-600" />
            </div>
            <div class="flex gap-2">
                <section v-if="!user">
                    <Link
                        :href="route('login')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Log in
                    </Link>
                </section>
                <section v-else class="relative">

                    <div
                        class="flex items-center gap-1 text-sm p-2 rounded-md hover:cursor-pointer hover:bg-gray-100 transition"
                        @click="showUserDropdown = !showUserDropdown"
                    >
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
                            class="absolute right-0 mt-1 w-48 origin-top-right rounded-md bg-gray-50 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                            ref="dropdownMenu"
                        >
                            <Link
                                :href="route('profile.edit')"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                <IconProfile class="h-4 w-4" />
                                Profile
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                <IconLogout class="h-4 w-4" />
                                Log Out
                            </Link>
                        </div>
                    </transition>
                </section>
            </div>
        </header>

        <main class="w-full bg-white p-4 bg-gray-100 rounded">
            <section class="border-b border-gray-300 w-full mb-4">
                <h1 class="text-2xl font-semibold text-gray-800">Welcome</h1>
            </section>
            <section class="grid grid-flow-row grid-cols-3 gap-4">
                <article class="bg-red-200 min-h-48 min-w-48 p-4 rounded">
                    Article 1
                </article>
                <article class="bg-blue-200 min-h-48 min-w-48 p-4 rounded">
                    Article 2
                </article>
                <article class="bg-green-200 min-h-48 min-w-48 p-4 rounded">
                    Article 3
                </article>
                <article class="bg-yellow-200 min-h-48 min-w-48 p-4 rounded">
                    Article 4
                </article>
                <article class="bg-purple-200 min-h-48 min-w-48 p-4 rounded">
                    Article 5
                </article>
                <article class="bg-pink-200 min-h-48 min-w-48 p-4 rounded">
                    Article 6
                </article>
                <article class="bg-indigo-200 min-h-48 min-w-48 p-4 rounded">
                    Article 7
                </article>
                <article class="bg-teal-200 min-h-48 min-w-48 p-4 rounded">
                    Article 8
                </article>
                <article class="bg-orange-200 min-h-48 min-w-48 p-4 rounded">
                    Article 9
                </article>
            </section>
        </main>
        <footer class="w-full bg-gray-50 p-4 rounded">footer</footer>
    </div>
</template>

<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, onBeforeMount, onMounted, onUnmounted } from "vue";

import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import IconChevron from "@/Components/icons/IconChevron.vue";
import IconProfile from "@/Components/icons/IconProfile.vue";
import IconLogout from "@/Components/icons/IconLogout.vue";

const user = ref();
const showUserDropdown = ref(false);

onBeforeMount(() => {
    user.value = usePage().props.auth.user;
});


onMounted(() => document.addEventListener("click", handleClickOutside));
onUnmounted(() => document.removeEventListener("click", handleClickOutside));

const handleClickOutside = (event) => {
	if (showUserDropdown.value && !event.target.closest(".relative")) {
		showUserDropdown.value = false;
	}
};

</script>
