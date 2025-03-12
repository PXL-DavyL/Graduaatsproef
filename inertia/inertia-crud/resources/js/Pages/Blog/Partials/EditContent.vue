<template>
    <div class="bg-white w-full p-4 shadow sm:rounded-lg sm:p-8 flex flex-col gap-4">
        <InputText
            name="Title"
            id="title"
            v-model="form.title"
            class="w-full"
        />

        <InputTextArea
            name="Content"
            id="content"
            v-model="form.content"
            class="w-full"
        />

        <div class="flex justify-between">
            <InputButton type="danger" @click="reset()">
                Reset to original
            </InputButton>

            <InputButton @click="submit()">
                Update blog content
            </InputButton>
        </div>
    </div>
</template>
<script setup>
import InputTextArea from "@/Components/InputTextArea.vue";
import InputText from "@/Components/InputText.vue";
import { useForm, usePage } from "@inertiajs/vue3";
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
            console.log('Blog content updated');
        },
        onError: (err) => {
            console.error('Error updating blog content', err);
        },
    });

};
</script>
