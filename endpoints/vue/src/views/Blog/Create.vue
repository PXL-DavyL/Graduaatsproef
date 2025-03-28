<template>
    <Layout title="Create Blog">

        <form>

            <InputText
                id="title"
                name="Title"
                class="mt-1 block w-full"
                v-model="title"
                required
                autocomplete="title"
                :error="errors.title"
            />

            <InputTextArea
                id="content"
                name="Content"
                class="mt-1 block w-full"
                v-model="content"
                required
                :error="errors.content"
            />

            <InputButton type="primary" class="w-full" @click="createBlog">
                Create Blog
            </InputButton>
        </form>
    </Layout>
</template>
<script setup>
import InputButton from '@/components/InputButton.vue';
import InputText from '@/components/InputText.vue';
import InputTextArea from '@/components/InputTextArea.vue';
import Layout from '@/layout/Layout.vue';
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import { useBlogStore } from '@/stores/blogs';
const blogStore = useBlogStore();

import { useRouter } from "vue-router";
const router = useRouter();

const title = ref('');
const content = ref('');
const errors = ref({});

const createBlog = async () => {

    try {
        const response = await blogStore.createBlog({
            title: title.value,
            content: content.value
        });
        
        const new_blog_id = response.data.blog.id;
        router.push({ name: "ShowBlog", params: { id: new_blog_id } });

        toast.success('Blog created successfully');
    }
    catch (error) {
        if (error.response) {
			errors.value = {};
			const response_errors = error.response.data.errors;
			for (const error in response_errors) {
				toast.error(response_errors[error]);
				errors.value[error] = response_errors[error][0];
			}
		}
    }
}
</script>