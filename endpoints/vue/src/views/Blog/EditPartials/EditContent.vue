<template>
	<div class="bg-white w-full p-3 sm:p-6 shadow sm:rounded-lg flex flex-col gap-4">
		<div>
			<h2 class="text-lg font-medium text-gray-900">Update blog content</h2>

			<span class="text-sm text-gray-600">
				You are able to edit the blog title and content in the form below.
			</span>
		</div>

		<InputText name="Title" id="title" v-model="title" class="w-full" :error="errors.title" />

		<InputTextArea
			name="Content"
			id="content"
			v-model="content"
			class="w-full"
			:error="errors.content"
		/>

		<div class="flex justify-between">
			<InputButton type="warning" @click="reset()"> Reset to original </InputButton>

			<InputButton @click="submit()"> Update blog content </InputButton>
		</div>
	</div>
</template>
<script setup>
import { toast } from "vue3-toastify";
import { useRoute } from "vue-router";
import { useBlogStore } from "@/stores/blogs";
const blogStore = useBlogStore();

import InputTextArea from "@/components/InputTextArea.vue";
import InputText from "@/components/InputText.vue";
import InputButton from "@/components/InputButton.vue";
import { onBeforeMount, ref } from "vue";

const title = ref("");
const content = ref("");
const errors = ref({});

const blog = ref(null);
onBeforeMount(async () => {
	const response = await blogStore.getBlog({ id: useRoute().params.id });
	blog.value = response.data.blog;

	title.value = blog.value.title;
	content.value = blog.value.content;
});

const submit = async() => {

    try {
        const response = await blogStore.editBlog({
            id: blog.value.id,
            title: title.value,
            content: content.value,
        })

        toast.success("Blog updated successfully");
    }
    catch(error) {
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

const reset = () => {
	title.value = blog.value.title;
	content.value = blog.value.content;
};
</script>
