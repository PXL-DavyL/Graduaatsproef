<template>
	<div class="flex flex-col gap-2 w-full">
		<InputButton type="danger" @click="showDeleteContainer = !showDeleteContainer">
			Delete Blog
		</InputButton>

		<div
			v-if="showDeleteContainer"
			class="bg-zinc-600 border border-zinc-700 text-zinc-100 p-2 rounded-md flex flex-col gap-2"
		>
			<p class="text-sm">Are you sure you want to delete this blog?</p>

			<InputText
				id="title"
				name="Confirm blog title"
				type="text"
				v-model="confirm_title"
				required
				autocomplete="confirm_title"
				:error="errors.confirm_title"
				:darkmode="true"
			/>

			<div class="flex gap-2 mt-2">
				<InputButton type="danger" @click="showDeleteContainer = false">
					Cancel
				</InputButton>

				<InputButton type="primary" @click="deleteBlog"> Delete </InputButton>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref } from "vue";
import { toast } from "vue3-toastify";

import InputButton from "@/components/InputButton.vue";
import InputText from "@/components/InputText.vue";
import { useRouter } from "vue-router";
const router = useRouter();

import { useBlogStore } from "@/stores/blogs";
const blogStore = useBlogStore();

const showDeleteContainer = ref(false);

const props = defineProps({
	blog: {
		type: Object,
		required: true,
	},
});

const confirm_title = ref("");
const errors = ref({});

const deleteBlog = async () => {
	try {
		const response = await blogStore.deleteBlogAsAdmin({
			blog: props.blog.id,
			title: confirm_title.value,
		});

		toast.warning("Blog post deleted successfully!");
		router.push("/admin/blogs/index");
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
