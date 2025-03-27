<template>
	<Layout :title="blog_title">
		<div class="flex flex-col gap-4">
			<div class="flex justify-between p-2 bg-gray-100 rounded" v-if="blog && blog.poster">
				<span>
					Posted by {{ blog.poster.name }} on
					{{ formatDate(blog.created_at) }}
				</span>

				<span v-if="blog.created_at !== blog.updated_at">
					Last changed on {{ formatDate(blog.updated_at) }}
				</span>
			</div>

			<span v-if="blog" v-html="blog.content"></span>

			<div class="flex gap-2 w-full" v-if="blog && blog.poster">
				<InputButton @click="showComments = !showComments">
					Comments
					<IconChevron
						class="transform transition duration-300"
						:class="{ 'rotate-180': showComments }"
					/>
				</InputButton>
				<div class="flex gap-2">
					<!-- reactions -->
				</div>

				<div
					class="self-end"
					v-if="auth_user && blog.poster && auth_user.id === blog.poster.id"
				>
					<InputButtonLink :to="`/blog/edit/${blog.id}`" class="self-end" type="warning">
						Edit
					</InputButtonLink>
				</div>
			</div>

            <ShowComments
                :comments="comments"
                :show="showComments"
				@refresh-comments="refreshComments"
            />
		</div>
	</Layout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { formatDate } from "@/composables/dates.js";
import { toast } from "vue3-toastify";

import axios from "axios";
import { getCsrfToken } from "@/stores/utils";

import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();
const auth_user = ref(null);

import { useBlogStore } from "@/stores/blogs";
const blogStore = useBlogStore();
const blog = ref(null);

import Layout from "@/layout/Layout.vue";
import InputButton from "@/components/InputButton.vue";
import InputButtonLink from "@/components/InputButtonLink.vue";
import IconChevron from "@/components/icons/IconChevron.vue";

import ShowComments from "./ShowPartials/ShowComments.vue";

const showComments = ref(true);
const blog_id = useRoute().params.id;
const blog_title = ref("Blog");
const comments = ref([]);


onMounted(async () => {
	try {
		const authResponse = await authStore.getUserData();
		auth_user.value = authResponse;

		const blogResponse = await blogStore.getBlog({
			id: blog_id,
		});
		
		blog.value = blogResponse.data.blog;
		blog_title.value = blog.value.title;

        comments.value = blogResponse.data.comments;
	} catch (error) {
		console.error("Blog error:", error);
	}

	await increaseView();
});

const increaseView = async () => {
	try {
		await getCsrfToken();
		const response = await axios.post(
			"http://localhost:8000/api/blog/add-view",
			{
				id: blog_id,
			},
			{
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			},
		);
		return response;
	} catch (error) {
		console.error("Increase view failed:", error);
		throw error;
	}
};

const refreshComments = async() => {
	try {
		const blogResponse = await blogStore.getBlog({
			id: blog_id,
		});
		comments.value = blogResponse.data.comments;
	} catch (error) {
		console.error("Refresh comments error:", error);
	}
}
</script>
