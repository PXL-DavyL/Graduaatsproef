<template>
    <div
        v-if="
            props.auth !== null &&
            props.comment.poster.id === props.auth.id
        "
    >
        <InputButton type="danger" @click="deleteComment(props.comment)"
            >Delete</InputButton
        >
    </div>
</template>
<script setup>
import InputButton from "@/components/InputButton.vue";
import { useCommentStore } from "@/stores/comments";
const commentStore = useCommentStore();

import { toast } from "vue3-toastify";

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
    auth: {
        type: Object,
        required: true,
    }
});
const emit = defineEmits(["delete-comment"]);


const deleteComment = async() => {

    try {
        const response = await commentStore.destroyComment({
            id: props.comment.id,
        })

        toast.warning("You have deleted your comment.");
        emit('delete-comment');
    }
    catch(error) {
        if (error.response) {
			const response_errors = error.response.data.errors;
			for (const error in response_errors) {
				toast.error(response_errors[error]);
			}
		}
    }
};
</script>
