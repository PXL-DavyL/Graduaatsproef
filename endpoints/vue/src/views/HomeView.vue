<template>
	<Layout title="Home">
		<div class="flex flex-col gap-3">
			<section
				class="grid grid-flow-row grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
			>
				<article
					v-for="(article, index) in blogs"
					class="min-h-24 min-w-48 rounded flex flex-col justify-between bg-gray-50 border border-gray-200 p-2"
					:key="article.id"
				>
					<div class="flex justify-between bg-sky-100 rounded p-2">
						<span>
							{{ article.poster.name }}
						</span>
						<span> {{ formatDate(article.created_at) }}</span>
					</div>
					{{ article.title }}

					<div class="w-full flex justify-between items-center">
						<div class="flex gap-2">
							<div class="flex gap-2">
								<div class="flex gap-1 items-center text-gray-400">
									<IconView class="h-4 w-4" />
									<span class="text-sm">
										{{ article.views }}
									</span>
								</div>
								<div class="flex gap-1 items-center text-gray-400">
									<IconComments class="h-4 w-4" />
									<span class="text-sm">{{ article.comments.length }}</span>
								</div>
							</div>
							<div class="flex gap-2">
                                <div
                                    class="flex gap-1 items-center text-gray-400"
                                    v-for="reaction in processedReactions[index]
                                        .reactions"
                                >
                                    <span v-html="reaction.icon"></span>
                                    <span
                                        v-if="reaction.count > 0"
                                        v-html="reaction.count"
                                    ></span>
                                </div>
                            </div>
						</div>
						<InputButton @click="onShowBlog(article.id)" class="self-end">
							Read more
						</InputButton>
					</div>
				</article>
			</section>

			<Pagination
				:itemsPerPage="12"
				:totalItems="allBlogs"
				:onPageChange="getPaginationData"
				@update-pagination-data="getPaginationData"
			/>
		</div>
	</Layout>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();
import { useBlogStore } from "@/stores/blogs";
const blogStore = useBlogStore();
import { formatDate } from "@/composables/dates.js";

import Layout from "@/layout/Layout.vue";
import Pagination from "@/components/Pagination.vue";
import IconView from "@/components/icons/IconView.vue";
import IconComments from "@/components/icons/IconComments.vue";
import InputButton from "@/components/InputButton.vue";

const blogs = ref([]);
const allBlogs = ref([]);
onBeforeMount(async () => {
	const response = await blogStore.getBlogs();
	allBlogs.value = response.data.blogs;
});

const getPaginationData = (data, page) => {
	blogs.value = data;
	processReactions();
	//currentPage.value = page;
};

const onShowBlog = (id) => {
	router.push({ name: "ShowBlog", params: { id: id } });
};


const processedReactions = ref([]);
const processReactions = () => {
    processedReactions.value = blogs.value.map((article) => {
        const reactionCounts = article.reactions.reduce(
            (acc, reaction) => {
                if (reaction.type === "upvote") {
                    acc.upvotes++;
                } else if (reaction.type === "downvote") {
                    acc.downvotes++;
                }
                return acc;
            },
            { upvotes: 0, downvotes: 0 }
        );

        return {
            id: article.id,
            reactions: [
                { id: "upvote", icon: "👍", count: reactionCounts.upvotes },
                { id: "downvote", icon: "👎", count: reactionCounts.downvotes },
            ],
        };
    });
};
</script>
