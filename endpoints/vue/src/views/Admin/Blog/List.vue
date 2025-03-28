<template>
    <AdminLayout title="Manage Blogs">
        <div class="flex flex-col gap-4">
            <div class="mt-3 flex flex-col gap-5">
                <div class="flex flex-col border border-zinc-600 rounded">
                    <div class="p-2 bg-zinc-700 flex justify-between">
                        <span> Blog List </span>
                        <InputButtonLink
                            type="primary"
                            name="AdminCreateBlog"
                        >
                            Create New Blog
                        </InputButtonLink>
                    </div>
                    <table class="table-auto w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-600 text-white">
                                <th class="p-2 border-b border-zinc-500">ID</th>
                                <th class="p-2 border-b border-zinc-500">
                                    Title
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Poster
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Published on
                                </th>
                                <th class="p-2 border-b border-zinc-500">
                                    Last update on
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="blog in blogs"
                                :key="blog.id"
                                :class="'odd:bg-zinc-500 even:bg-zinc-400 hover:text-zinc-800 hover:bg-zinc-300 cursor-pointer'"
                                @click="onClickRow(blog)"
                            >
                                <td class="p-2 border-b border-zinc-500">
                                    {{ blog.id }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ blog.title }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ blog.poster.name }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(blog.created_at) }}
                                </td>
                                <td class="p-2 border-b border-zinc-500">
                                    {{ formatDate(blog.updated_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span class="p-2 bg-zinc-600"> Click on a row to edit or delete the blog. </span>
                </div>
            </div>
			<Pagination
				:itemsPerPage="12"
				:totalItems="all_blogs"
				:onPageChange="getPaginationData"
				@update-pagination-data="getPaginationData"
                :darkMode="true"
			/>
        </div>
    </AdminLayout>
</template>
<script setup>

import { ref, onBeforeMount} from "vue";
import { formatDate } from "@/composables/dates";
import { useBlogStore } from "@/stores/blogs";
const blogStore = useBlogStore();
import { useRouter } from "vue-router";
const router = useRouter();

import Pagination from "@/components/Pagination.vue";
import AdminLayout from "@/layout/AdminLayout.vue";
import InputButtonLink from "@/components/InputButtonLink.vue";

const onClickRow = (blog) => {
	router.push({ name: "AdminEditBlog", params: { id: blog.id } });
};

const blogs = ref([]);
const all_blogs = ref([]);
onBeforeMount(async () => {
    const response = await blogStore.getBlogs();
    all_blogs.value = response.data.blogs;
});

const getPaginationData = (data, page) => {
	blogs.value = data;
	//currentPage.value = page;
};
</script>
