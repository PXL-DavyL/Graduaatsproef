<template>
    <AdminLayout title="Edit Blog Post">
        <form @submit.prevent="editBlog" class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Blog ID:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ usePage().props.blog.id }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Created at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(usePage().props.blog.created_at) }}
                </span>
            </div>

            <Author
                :blog="usePage().props.blog"
                @update-blog-author="updateBlogAuthor"
            />

            <InputText
                id="title"
                name="Title"
                type="text"
                v-model="form.title"
                required
                autocomplete="title"
                :error="form.errors.title"
                :darkmode="true"
            />

            <InputTextArea
                id="content"
                name="Content"
                v-model="form.content"
                required
                :error="form.errors.content"
                :darkmode="true"
            ></InputTextArea>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Last updated at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(usePage().props.blog.updated_at) }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Views
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ usePage().props.blog.views }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Comments
                </span>
                <div
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    <div class="flex justify-between items-center">
                        <span> {{ usePage().props.blog.comments.length }}</span>
                        <InputButton type="primary">Manage comments</InputButton>
                    </div>
                </div>
            </div>

            <InputButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save Blog Post
            </InputButton>
        </form>

        <Delete :blog="usePage().props.blog" class="mt-4" />
    </AdminLayout>
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates";
import { toast } from "vue3-toastify";

import InputButton from "@/Components/InputButton.vue";
import InputText from "@/Components/InputText.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputTextArea from "@/Components/InputTextArea.vue";
import Author from "./Partials/Author.vue";
import Delete from "./Partials/Delete.vue";

const form = useForm({
    title: usePage().props.blog.title,
    content: usePage().props.blog.content,
    author: usePage().props.blog.poster.id,
});

const updateBlogAuthor = (author) => {
    form.author = author.id;
};

const editBlog = () => {
    form.patch(route("admin.blogs.update", usePage().props.blog.id), {
        onFinish: () => form.reset(),
        onSuccess: () => {
            toast.success("Blog post updated successfully.");
            form.clearErrors();
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
};
</script>
