<template>
    <div class="w-full p-2">
        <InputTextArea
            :id="'comment_' + comment.id"
            name="Edit comment"
            v-model="content"
            class="w-full"
            :error="errors.content"
        />

        <InputButton type="warning" class="w-full" @click="editComment">Edit comment</InputButton>
    </div>
</template>
<script setup>
import { ref } from "vue";
import { toast } from "vue3-toastify";

import { useCommentStore } from "@/stores/comments";
const commentStore = useCommentStore();

import InputTextArea from "@/components/InputTextArea.vue";
import InputButton from "@/components/InputButton.vue";

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    }
});

const emits = defineEmits(['toggle-edit-comment', 'update-comments']);
const content = ref(props.comment.content);
const errors = ref({});

const editComment = async() => {

    try {
        const response = await commentStore.editComment({
            id: props.comment.id,
            content: content.value,
        });

        toast.warning("You have edited your comment.");
        emits('toggle-edit-comment', props.comment.id);
        emits('update-comments');
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
