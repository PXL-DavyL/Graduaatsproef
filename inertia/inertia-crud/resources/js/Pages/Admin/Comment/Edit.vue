<template>
    <AdminLayout title="Manage comments">
        <form class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Comment ID:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ usePage().props.comment.id }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Author:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ usePage().props.comment.poster.name }}
                </span>
            </div>


            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Created at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(usePage().props.comment.created_at) }}
                </span>
            </div>

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
                    {{ formatDate(usePage().props.comment.updated_at) }}
                </span>
            </div>
        </form>

        <div class="flex flex-col gap-2">
            <InputButton
                class="mt-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="editComment"
            >
                Save comment
            </InputButton>
            
            <Delete :comment="usePage().props.comment" />

            <InputButtonLink
                :href="route('admin.comments.show', { blog: usePage().props.blog })"
                class="w-full"
                type="secondary"
            >
                Back to comments
            </InputButtonLink>
        </div>
    </AdminLayout>
</template>
<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates";
import { toast } from "vue3-toastify";

import AdminLayout from "@/Layouts/AdminLayout.vue";

import InputTextArea from "@/Components/InputTextArea.vue";
import InputButton from "@/Components/InputButton.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";
import Delete from "./Partials.vue/Delete.vue";

const form = useForm({
    content: usePage().props.comment.content,
});

const editComment = () => {
    form.patch(
        route("admin.comments.update", {
            blog: usePage().props.blog.id,
            comment: usePage().props.comment.id,
        }),
        {
            onFinish: () => form.reset(),
            onSuccess: () => {
                toast.success("Blog comment updated successfully.");
                form.clearErrors();
            },
            onError: (err) => {
                for (const error in err) {
                    toast.error(err[error]);
                }
            },
        }
    );
};
</script>
