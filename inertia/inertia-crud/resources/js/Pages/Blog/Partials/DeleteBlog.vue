<template>
    <div class="bg-gray-50 w-full p-3 sm:p-6 shadow sm:rounded-lg flex gap-4">
        <div class="flex flex-col gap-2">
            <div>
                <h2 class="text-lg font-medium text-gray-900">Delete blog</h2>

                <span class="text-sm text-gray-600">
                    Alternatively, you might wish to delete the blog. You can do so
                    below by clicking on the delete button.
                </span>
            </div>
            <div>
                <InputButton type="danger" @click="showDeleteModal = true">
                    Delete blog
                </InputButton>
            </div>
        </div>
    </div>

    <Modal :show="showDeleteModal" @close="closeModal">
        <div class="p-6 flex flex-col gap-4">
            <div>
                <h2 class="text-lg font-medium text-gray-900">
                    Confirm deletion
                </h2>

                <span class="text-sm text-gray-600">
                    In order to delete the blog, please confirm the title and
                    click the delete button one last time.
                </span>
            </div>
            <div>
                <InputText
                    name="Confirm the title of the blog"
                    id="title"
                    v-model="form.confirm_title"
                    type="text"
                    class="block w-full"
                    required
                    autofocus
                    autocomplete="title"
                    :error="form.errors.confirm_title"
                />

                <InputButton
                    type="danger"
                    @click="deleteBlog()"
                    class="w-full mt-4"
                >
                    Delete blog
                </InputButton>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

import InputButton from "@/Components/InputButton.vue";
import Modal from "@/Components/Modal.vue";
import InputText from "@/Components/InputText.vue";

const form = useForm({
    blog: usePage().props.blog,
    confirm_title: "",
});

const showDeleteModal = ref(false);
const closeModal = () => {
    showDeleteModal.value = false;

    form.clearErrors();
    form.reset();
};

const deleteBlog = () => {
    form.delete(route("blog.destroy", {
        blog: form.blog,
        confirm_title: form.confirm_title,
    }), {
        onSuccess: () => {
            console.log("Blog deleted");
            closeModal();
        },
        onError: (err) => {
            console.error("Error deleting blog", err);
        },
    });
    
};
</script>
