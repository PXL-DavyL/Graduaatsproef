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
                v-model="form.confirm_author"
                required
                autocomplete="confirm_author"
                :error="form.errors.confirm_author"
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
import { useForm, usePage } from '@inertiajs/vue3';
import { toast } from "vue3-toastify";

import InputButton from '@/Components/InputButton.vue';
import InputText from '@/Components/InputText.vue';

const showDeleteContainer = ref(false);

const props = defineProps({
    comment: {
        type: Object,
        required: true
    }
});

const form = useForm({
    confirm_author: ''
});

const deleteComment = () => {
    form.delete(route('admin.comments.destroy', {
            blog: usePage().props.blog.id,
            comment: usePage().props.comment.id,
        }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.warning("Comment deleted successfully.");
            form.clearErrors();
            showDeleteContainer.value = false;
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
}
</script>
