<template>
    <Head :title="props.title" />
    <div class="flex text-zinc-200 h-screen">
        <div
            class="min-h-screen min-w-64 bg-zinc-700 border-r border-zinc-800 p-4"
        >
            <span class="text-xl bold w-full">Admin Menu</span>
            <div class="mt-8 flex flex-col gap-2">
                <Link :href="route('admin.index')"
                    ><span class="flex w-full gap-4"
                        ><IconHome class="h-6 w-6" /> Home</span
                    ></Link
                >
                
                <Link :href="route('admin.users.index')"
                    ><span class="flex w-full gap-4"
                        ><IconUser class="h-6 w-6" /> Users</span
                    ></Link
                >
                <span class="flex w-full gap-4"
                    ><IconBlog class="h-6 w-6" /> Blogs</span
                >
            </div>
            <div class="mt-8">
                <Link :href="route('home')"
                    ><span class="flex w-full gap-4"
                        ><IconReturn class="h-6 w-6" /> Back to site</span
                    ></Link>
            </div>
        </div>
        <div class="w-full bg-zinc-500">
            <div
                class="min-h-16 w-full bg-zinc-600 border-b border-zinc-800 p-4"
            >
                <span class="text-2xl font-semibold text-zinc-200">{{
                    props.title
                }}</span>
            </div>
            <div class="min-w-full p-4">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
<script setup>
import IconBlog from "@/Components/icons/Admin/IconBlog.vue";
import IconHome from "@/Components/icons/Admin/IconHome.vue";
import IconUser from "@/Components/icons/Admin/IconUser.vue";
import IconReturn from "@/Components/icons/Admin/IconReturn.vue";

import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>
