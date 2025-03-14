<template>
    <div
        class="bg-white w-full p-3 sm:p-6 shadow sm:rounded-lg flex flex-col gap-4"
    >
        <div>
            <h2 class="text-lg font-medium text-gray-900">
                Update blog content
            </h2>

            <span class="text-sm text-gray-600">
                You are able to edit the blog title and content in the form
                below.
            </span>
        </div>

        <InputText
            name="Title"
            id="title"
            v-model="form.title"
            class="w-full"
            :error="form.errors.title"
        />

        <InputTextArea
            name="Content"
            id="content"
            v-model="form.content"
            class="w-full"
            :error="form.errors.content"
        />

        <div class="flex justify-between">
            <InputButton type="warning" @click="reset()">
                Reset to original
            </InputButton>

            <InputButton @click="submit()"> Update blog content </InputButton>
        </div>
    </div>
</template>
<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

import InputTextArea from "@/Components/InputTextArea.vue";
import InputText from "@/Components/InputText.vue";
import InputButton from "@/Components/InputButton.vue";

const form = useForm({
    title: usePage().props.blog.title,
    content: usePage().props.blog.content,
});

const reset = () => {
    form.title = usePage().props.blog.title;
    form.content = usePage().props.blog.content;
};

const submit = () => {
    form.patch(route("blog.update", usePage().props.blog.id), {
        onSuccess: () => {
            toast.success("Blog has been updated.");
        },
        onError: (err) => {
            for(const error in err) {
                toast.error(err[error]);
            }
        },
    });
};
</script>
