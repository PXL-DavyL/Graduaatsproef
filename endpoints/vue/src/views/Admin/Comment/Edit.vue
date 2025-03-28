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
                    {{ comment.id }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Author:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ author_name }}
                </span>
            </div>


            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Created at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(comment.created_at) }}
                </span>
            </div>

            <InputTextArea
                id="content"
                name="Content"
                v-model="content"
                required
                :error="errors.content"
                :darkmode="true"
            ></InputTextArea>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Last updated at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(comment.updated_at) }}
                </span>
            </div>
        </form>

        <div class="flex flex-col gap-2">
            <InputButton
                class="mt-4"
                :class="{ 'opacity-25': loading }"
                :disabled="loading"
                @click="editComment"
            >
                Save comment
            </InputButton>
            
            <Delete v-if="!loading" :comment="comment" />

            <InputButtonLink
                class="w-full"
                type="secondary"
                name="AdminListBlogComments"
                :params="{ id: comment.blog_id }"
            >
                Back to comments
            </InputButtonLink>
        </div>
    </AdminLayout>
</template>
<script setup>
import { formatDate } from "@/composables/dates";
import { toast } from "vue3-toastify";

import { useRoute, useRouter } from 'vue-router';
const router = useRouter();
import { onMounted, ref, watch } from "vue";
import { useCommentStore } from "@/stores/comments";
const commentStore = useCommentStore();

import AdminLayout from "@/layout/AdminLayout.vue";

import InputTextArea from "@/components/InputTextArea.vue";
import InputButton from "@/components/InputButton.vue";
import InputButtonLink from "@/components/InputButtonLink.vue";
import Delete from "./Partials/Delete.vue";


const author_name = ref("");
const content = ref("");
const errors = ref({});


const comment = ref([]);
onMounted(async () => {
    
    try {
        const response = await commentStore.getCommentAsAdmin({
            id: useRoute().params.comment
        });

        comment.value = response.data.comment;
        content.value = comment.value.content;
        author_name.value = comment.value.poster.name;

        loading.value = false;
    }
    catch(error) {
        console.log(error);
    }
});

const loading = ref(true);
watch(
	() => commentStore.loading,
	(value) => (loading.value = value),
);

const editComment = async () => {

    try {
        const response = await commentStore.updateCommentAsAdmin({
            id: comment.value.id,
            content: content.value,
        });

        toast.success('Comment updated successfully!');

        comment.value = response.data.comment;
        content.value = comment.value.content;
    }
    catch(error) {
        if (error.response) {
			errors.value = {};
			const response_errors = error.response.data.errors;
			for (const error in response_errors) {
				toast.error(response_errors[error]);
				errors.value[error] = response_errors[error][0];
			}
		}
    }
};
</script>