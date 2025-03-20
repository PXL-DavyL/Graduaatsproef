<template>
    <div
        class="bg-gray-50 border border-gray-100 p-2 rounded w-full flex flex-col gap-2"
    >
        <InputTextArea
            name="Post new comment"
            id="content"
            v-model="form.content"
            class="w-full"
            :error="form.errors.content"
        />

        <InputButton class="w-full" @click="submitComment"
            >Post comment</InputButton
        >
    </div>
</template>
<script setup>
import InputButton from "@/Components/InputButton.vue";
import InputTextArea from "@/Components/InputTextArea.vue";
import { useForm, usePage} from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

const emits = defineEmits(["submit-comment"]);

const form = useForm({
    content: "",
});

const submitComment = () => {
    
    form.post(route('blog.comment.add', usePage().props.blog.id), {
        onFinish: () => form.reset(),
        onSuccess: () => {
            toast.success("You have posted a comment.");
            form.clearErrors();
            emits("submit-comment");
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
}
</script>
