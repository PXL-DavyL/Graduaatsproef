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
					<div
						v-for="reaction in processedReactions"
						type="secondary"
						:key="reaction.id"
						class="inline-flex justify-center items-center rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 hover:bg-slate-300 focus:bg-slate-600 focus:ring-slate-500 active:bg-slate-500 text-white flex gap-2"
						:class="
							getUserSubmittedReaction() !== undefined &&
							getUserSubmittedReaction().type === reaction.id
								? 'bg-sky-400'
								: 'bg-slate-400'
						"
						@click="toggleReaction(reaction.id)"
					>
						<span v-html="reaction.icon"></span>
						<span v-if="reaction.count > 0" v-html="reaction.count"></span>
					</div>
				</div>

				<div
					class="self-end"
					v-if="auth_user && blog.poster && auth_user.id === blog.poster.id"
				>
					<InputButtonLink name="EditBlog" :params="{id: blog.id}" class="self-end" type="warning">
						Edit
					</InputButtonLink>
				</div>
			</div>

			<ShowComments
				:comments="comments"
				:show="showComments"
				@refresh-comments="refreshComments"
			/>
			<AddComment @add-comment="refreshComments" />
		</div>
	</Layout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { formatDate } from "@/composables/dates.js";

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
import AddComment from "./ShowPartials/AddComment.vue";

const showComments = ref(true);
const blog_id = useRoute().params.id;
const blog_title = ref("Blog");
const comments = ref([]);
const reactions = ref([]);

onMounted(async () => {
	try {
		const authResponse = await authStore.getUserData();
		auth_user.value = authResponse;

		await reloadblogs();
	} catch (error) {
		console.error("Blog error:", error);
	}

	await increaseView();
});

const reloadblogs = async () => {
	try {
		const blogResponse = await blogStore.getBlog({
			id: blog_id,
		});

		blog.value = blogResponse.data.blog;
		blog_title.value = blog.value.title;

		comments.value = blogResponse.data.comments;
		reactions.value = blogResponse.data.blog.reactions;

		processReactions();
	} catch (error) {
		console.log("failed to (re)load blogs: ", error);
	}
};

const processedReactions = ref([]);
const processReactions = () => {
	const reactionCounts = reactions.value.reduce(
		(filtered, reaction) => {
			if (reaction.type === "upvote") {
				filtered.upvotes++;
			} else if (reaction.type === "downvote") {
				filtered.downvotes++;
			}
			return filtered;
		},
		{ upvotes: 0, downvotes: 0 },
	);

	processedReactions.value = [
		{ id: "upvote", icon: "👍", count: reactionCounts.upvotes },
		{ id: "downvote", icon: "👎", count: reactionCounts.downvotes },
	];
};

const toggleReaction = async (type) => {
	try {
		await getCsrfToken();
		const response = await axios.post(
			"http://localhost:8000/api/blog/reaction",
			{
				id: blog_id,
				type: type,
			},
			{
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			},
		);

		await reloadblogs();

		return response;
	} catch (error) {
		console.error("Toggle reaction failed:", error);
		throw error;
	}
};

const getUserSubmittedReaction = () => {
	const reaction = reactions.value.find((reaction) => reaction.poster_id === auth_user.value.id);
	return reaction;
};

const increaseView = async () => {
	try {
		await getCsrfToken();
		const response = await axios.post(
			"http://localhost:8000/api/blog",
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

const refreshComments = async () => {
	try {
		const blogResponse = await blogStore.getBlog({
			id: blog_id,
		});
		comments.value = blogResponse.data.comments;
	} catch (error) {
		console.error("Refresh comments error:", error);
	}
};
</script>
