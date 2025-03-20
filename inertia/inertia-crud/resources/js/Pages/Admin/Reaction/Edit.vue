<template>
    <AdminLayout title="Manage reactions">
        <form class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Reaction ID:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ usePage().props.reaction.id }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Author:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ usePage().props.reaction.poster.name }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Type:
                </span>
                <InputSelect v-model="form.type" :options="['upvote', 'downvote']"/>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Created at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(usePage().props.reaction.created_at) }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Last updated at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(usePage().props.reaction.updated_at) }}
                </span>
            </div>
        </form>

        <div class="flex flex-col gap-2">
            <InputButton
                class="mt-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="editReaction"
            >
                Save reaction
            </InputButton>
            
            <Delete :reaction="usePage().props.reaction" />

            <InputButtonLink
                :href="route('admin.reactions.show', { blog: usePage().props.blog })"
                class="w-full"
                type="secondary"
            >
                Back to reactions
            </InputButtonLink>
        </div>
    </AdminLayout>
</template>
<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates";
import { toast } from "vue3-toastify";

import AdminLayout from "@/Layouts/AdminLayout.vue";

import InputButton from "@/Components/InputButton.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";
import InputSelect from "@/Components/InputSelect.vue";
import Delete from "./Partials/Delete.vue";

const form = useForm({
    type: usePage().props.reaction.type,
});

const editReaction = () => {
    form.patch(
        route("admin.reactions.update", {
            blog: usePage().props.blog.id,
            reaction: usePage().props.reaction.id,
        }),
        {
            onFinish: () => form.reset(),
            onSuccess: () => {
                toast.success("Blog reaction updated successfully.");
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
