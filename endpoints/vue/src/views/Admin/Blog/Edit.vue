<template>
    <AdminLayout title="Edit Blog Post">
        <form class="flex flex-col gap-4" v-if="blog">
            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Blog ID:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ blog.id }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Created at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(blog.created_at) }}
                </span>
            </div>

            <Author
                :blog="blog"
                @update-blog-author="updateBlogAuthor"
            />

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
                v-model="content"
                required
                :error="errors.content"
                :darkmode="true"
            ></InputTextArea>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Last updated at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ formatDate(blog.updated_at) }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Views
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ blog.views }}
                </span>
            </div>

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Comments
                </span>
                <div
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    <div class="flex justify-between items-center">
                        <span> {{ blog.comments.length }}</span>
                        <InputButtonLink type="primary" name="AdminListBlogComments" :params="{id: blog.id}">Manage comments</InputButtonLink>
                    </div>
                </div>
            </div>


            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Reactions
                </span>
                <div
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    <div class="flex justify-between items-center">
                        <span> {{ blog.reactions.length }}</span>
                        <InputButtonLink type="primary" name="AdminListBlogReactions" :params="{id: blog.id}">Manage reactions</InputButtonLink>
                    </div>
                </div>
            </div>
            
            <InputButton
                :class="{ 'opacity-25': loading }"
                :disabled="loading"
                @click="editBlog"
            >
                Save Blog Post
            </InputButton>
        </form>

        <Delete v-if="blog" :blog="blog" class="mt-4" />

        <InputButtonLink class="mt-4 w-full" type="secondary" name="AdminBlogs">
            Back to blogs
        </InputButtonLink>
    </AdminLayout>
</template>

<script setup>

import { ref, watch, onBeforeMount } from "vue";
import { useRoute, useRouter } from "vue-router";
const router = useRouter();
import { useBlogStore } from "@/stores/blogs";
const blogStore = useBlogStore();
const blog = ref(null);

import { formatDate } from "@/composables/dates";
import { toast } from "vue3-toastify";

import InputButton from "@/components/InputButton.vue";
import InputText from "@/components/InputText.vue";
import AdminLayout from "@/layout/AdminLayout.vue";
import InputTextArea from "@/components/InputTextArea.vue";
import Author from "./Partials/Author.vue";
import Delete from "./Partials/Delete.vue";
import InputButtonLink from "@/components/InputButtonLink.vue";

const title = ref("");
const content = ref("");
const author = ref(0);
const errors = ref({});

const loading = ref(false);
watch(
	() => blogStore.loading,
	(value) => (loading.value = value),
);

onBeforeMount(async() => {
    try {
        const blogResponse = await blogStore.getBlog({
            id: useRoute().params.id,
        });

        blog.value = blogResponse.data.blog;
        title.value = blog.value.title;
        content.value = blog.value.content;
        author.value = blog.value.poster_id;
    } catch (error) {
        console.error("Blog error:", error);
    }
});

const updateBlogAuthor = (emit) => {
    author.value = emit.id;
};

const editBlog = async() => {
    try {
        const response = await blogStore.editBlogAsAdmin({
            blog: blog.value.id,
            title: title.value,
            content: content.value,
            author: author.value
        });

        toast.success("Blog post updated successfully!");
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
