<template>
    <div class="flex flex-col gap-2 w-full">
        <InputButton type="danger" @click="showDeleteContainer = !showDeleteContainer">
            Delete Blog
        </InputButton>

        <div v-if="showDeleteContainer" class="bg-zinc-600 border border-zinc-700 text-zinc-100 p-2 rounded-md flex flex-col gap-2">
            <p class="text-sm">
                Are you sure you want to delete this blog?
            </p>

            <InputText
                id="title"
                name="Confirm blog title"
                type="text"
                v-model="form.confirm_title"
                required
                autocomplete="confirm_title"
                :error="form.errors.confirm_title"
                :darkmode="true"
            />

            <div class="flex gap-2 mt-2">
                <InputButton type="danger" @click="showDeleteContainer = false">
                    Cancel
                </InputButton>

                <InputButton type="primary" @click="deleteBlog">
                    Delete
                </InputButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { toast } from "vue3-toastify";

import InputButton from '@/Components/InputButton.vue';
import InputText from '@/Components/InputText.vue';

const showDeleteContainer = ref(false);

const props = defineProps({
    blog: {
        type: Object,
        required: true
    }
});

const form = useForm({
    confirm_title: ''
});

const deleteBlog = () => {
    form.delete(route('admin.blogs.destroy', props.blog.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.warning("Blog deleted successfully.");
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
