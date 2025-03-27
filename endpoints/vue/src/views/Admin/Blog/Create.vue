<template>
	<AdminLayout title="Create Blog Post">
		<form class="flex flex-col gap-4">
			<InputText
				id="title"
				name="Title"
				type="text"
				v-model="title"
				required
				autocomplete="title"
				:error="errors.title"
				:darkmode="true"
			/>

			<InputTextArea
				id="content"
				name="Content"
				class="mt-1 block w-full"
				v-model="content"
				required
				autofocus
				:error="errors.content"
				:darkmode="true"
			/>

			<InputButton type="primary" class="w-full" :disabled="loading" @click="createBlogPost">
				Create Blog Post
			</InputButton>
		</form>
	</AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();
import { toast } from "vue3-toastify";
import { useBlogStore } from "@/stores/blogs";
const blogStore = useBlogStore();

import AdminLayout from "@/layout/AdminLayout.vue";

import InputButton from "@/components/InputButton.vue";
import InputText from "@/components/InputText.vue";
import InputTextArea from "@/components/InputTextArea.vue";

const title = ref("");
const content = ref("");
const loading = ref(false);
const errors = ref({});

const createBlogPost = async () => {
	try {
		const response = await blogStore.createBlogAsAdmin({
			title: title.value,
			content: content.value,
		});
        toast.success('Blog post created successfully');

        const new_blog_id = response.data.blog.id;
        router.push({ name: "AdminEditBlog", params: { id: new_blog_id } });
    } catch (error) {
        if (error.response) {
			errors.value = {};
			const response_errors = error.response.data.errors;
			for (const error in response_errors) {
				toast.error(response_errors[error]);
				errors.value[error] = response_errors[error][0];
			}
		}
	}
};
</script>
