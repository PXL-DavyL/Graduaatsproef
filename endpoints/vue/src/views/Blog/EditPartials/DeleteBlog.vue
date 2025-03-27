<template>
	<div class="bg-gray-50 w-full p-3 sm:p-6 shadow sm:rounded-lg flex gap-4">
		<div class="flex flex-col gap-2">
			<div>
				<h2 class="text-lg font-medium text-gray-900">Delete blog</h2>

				<span class="text-sm text-gray-600">
					Alternatively, you might wish to delete the blog. You can do so below by
					clicking on the delete button.
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
				<h2 class="text-lg font-medium text-gray-900">Confirm deletion</h2>

				<span class="text-sm text-gray-600">
					In order to delete the blog, please confirm the title and click the delete
					button one last time.
				</span>
			</div>
			<div>
				<InputText
					name="Confirm the title of the blog"
					id="title"
					v-model="confirm_title"
					type="text"
					class="block w-full"
					required
					autofocus
					autocomplete="title"
					:error="errors.confirm_title"
				/>

				<InputButton type="danger" @click="deleteBlog()" class="w-full mt-4">
					Delete blog
				</InputButton>
			</div>
		</div>
	</Modal>
</template>
<script setup>
import { ref } from "vue";
import { toast } from "vue3-toastify";

import { useBlogStore } from "@/stores/blogs";
const blogStore = useBlogStore();

import {  useRouter } from "vue-router";
const router = useRouter();

import InputButton from "@/components/InputButton.vue";
import Modal from "@/components/Modal.vue";
import InputText from "@/components/InputText.vue";

const props = defineProps({
	blog: {
		type: Number,
		required: true,
	},
});

const confirm_title = ref("");
const errors = ref({});

const showDeleteModal = ref(false);
const closeModal = () => {
	showDeleteModal.value = false;
};

const deleteBlog = async () => {
	try {
		const response = await blogStore.destroyBlog({
			blog: props.blog,
			confirm_title: confirm_title.value,
		});

        
        toast.warning("Blog deleted successfully");
		router.push({ name: "home" });

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
