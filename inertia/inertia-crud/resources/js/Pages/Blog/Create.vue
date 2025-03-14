<template>
    <Layout title="Create Blog">
        <form @submit.prevent="submit">
            <InputText
                id="title"
                name="Desired title of your blog"
                type="text"
                class="mt-1 block w-full"
                v-model="form.title"
                required
                autofocus
                autocomplete="title"
                :error="form.errors.title"
            />

            <InputTextArea
                id="content"
                name="Desired content of your blog"
                class="mt-1 block w-full"
                v-model="form.content"
                required
                autofocus
                :error="form.errors.content"
            />

            <InputButton
                class="mt-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Create Blog
            </InputButton>
        </form>
    </Layout>
</template>
<script setup>
import InputButton from "@/Components/InputButton.vue";
import InputText from "@/Components/InputText.vue";
import InputTextArea from "@/Components/InputTextArea.vue";
import Layout from "@/Layouts/Layout.vue";
import { useForm } from "@inertiajs/vue3";
import { toast } from 'vue3-toastify';

const form = useForm({
    title: "",
    content: "",
});

const submit = () => {
    form.post(route("blog.store"), {
        onFinish: () => form.reset(),        
        onSuccess: () => {
            toast.success("You have successfully created a blog.");
        },
        onError: (err) => {
            for(const error in err) {
                toast.error(err[error]);
            }
        },
    });
};
</script>
