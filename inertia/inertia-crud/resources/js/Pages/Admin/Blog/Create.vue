<template>
    <AdminLayout title="Create Blog Post">
        <form class="flex flex-col gap-4">
            <InputText
                id="title"
                name="Title"
                type="text"
                v-model="form.title"
                required
                autocomplete="title"
                :error="form.errors.title"
                :darkmode="true"
            />


            <InputTextArea
                id="content"
                name="Content"
                class="mt-1 block w-full"
                v-model="form.content"
                required
                autofocus
                :error="form.errors.content"
                :darkmode="true"
            />

            <InputButton type="primary" class="w-full" :disabled="form.processing" @click="createBlogPost">
                Create Blog Post
            </InputButton>
        </form>
    </AdminLayout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

import AdminLayout from '@/Layouts/AdminLayout.vue';

import InputButton from "@/Components/InputButton.vue";
import InputText from "@/Components/InputText.vue";
import InputTextArea from "@/Components/InputTextArea.vue";

const form = useForm({
    title: "",
    content: "",
});

const createBlogPost = () => {
    form.post(route('admin.blogs.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Blog post created successfully");
            form.reset();
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
};
</script>
