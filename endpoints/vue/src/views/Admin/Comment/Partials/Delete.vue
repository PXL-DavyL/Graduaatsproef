<template>
    <div class="flex flex-col gap-2 w-full">
        <InputButton type="danger" @click="showDeleteContainer = !showDeleteContainer">
            Delete Comment
        </InputButton>

        <div v-if="showDeleteContainer" class="bg-zinc-600 border border-zinc-700 text-zinc-100 p-2 rounded-md flex flex-col gap-2">
            <p class="text-sm">
                Are you sure you want to delete this comment?
            </p>

            <InputText
                id="title"
                name="Confirm comment author"
                type="text"
                v-model="confirm_author"
                required
                autocomplete="confirm_author"
                :error="errors.confirm_author"
                :darkmode="true"
            />

            <div class="flex gap-2 mt-2">
                <InputButton type="danger" @click="showDeleteContainer = false">
                    Cancel
                </InputButton>

                <InputButton type="primary" @click="deleteComment">
                    Delete
                </InputButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { toast } from "vue3-toastify";

import { useCommentStore } from '@/stores/comments';
const commentStore = useCommentStore();

import { useRouter } from 'vue-router';
const router = useRouter();

import InputButton from '@/components/InputButton.vue';
import InputText from '@/components/InputText.vue';

const showDeleteContainer = ref(false);

const props = defineProps({
    comment: {
        type: Object,
        required: true
    }
});

const confirm_author = ref('');
const errors = ref({});

const deleteComment = async () => {

    try {
        const response = await commentStore.destroyCommentAsAdmin({
            id: props.comment.id,
            confirm_author: confirm_author.value
        });

        toast.success('Comment deleted successfully');

        router.push({
            name: 'AdminListBlogComments',
            params: {
                id: props.comment.blog_id,
            }
        });
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
    
}
</script>
