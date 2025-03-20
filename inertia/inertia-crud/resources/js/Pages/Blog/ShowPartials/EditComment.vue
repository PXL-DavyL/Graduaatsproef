<template>
    <div class="w-full p-2">
        <InputTextArea
            :id="'comment_' + comment.id"
            name="Edit comment"
            v-model="form.content"
            class="w-full"
        />

        <InputButton type="warning" class="w-full" @click="editComment">Edit comment</InputButton>
    </div>
</template>
<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

import InputTextArea from "@/Components/InputTextArea.vue";
import InputButton from "@/Components/InputButton.vue";

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    content: props.comment.content,
});

const emit = defineEmits(["refresh-comments"]);

const editComment = () => {
    form.patch(route('blog.comment.update', {
        blog: props.comment.blog_id,
        comment: props.comment.id,
    }), {
        onFinish: () => form.reset(),
        onSuccess: () => {
            toast.success("You have updated your comment.");
            emit("refresh-comments");
            form.clearErrors();
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
}
</script>
