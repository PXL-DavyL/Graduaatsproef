<template>
    <div v-if="usePage().props.auth.user !== null && props.comment.poster.id === usePage().props.auth.user.id">
        <InputButton type="secondary" @click="deleteComment(props.comment)"
            >Delete</InputButton
        >
    </div>
</template>
<script setup>
import InputButton from "@/Components/InputButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
});
const emit = defineEmits(["delete-comment"]);

const form = useForm([]);

const deleteComment = (comment) => {
    form.delete(route("blog.comment.delete", comment.id), {
        onSuccess: () => {
            toast.warning("You have deleted a comment.");
            emit("delete-comment");
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
};
</script>
