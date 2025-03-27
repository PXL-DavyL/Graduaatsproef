<template>
    <div v-if="props.show" class="flex flex-col gap-2 p-2 bg-gray-100 rounded">
        <h1 class="text-xl">Comments</h1>
        <div
            v-for="(comment, index) in comments"
            :key="comment.id"
            class="flex justify-between bg-white border border-gray-50 p-2 rounded"
        >
            <div class="flex flex-col gap-1 w-full">
                <span class="text-gray-400"
                    >Comment by {{ comment.poster.name }} on
                    {{ formatDate(comment.created_at) }}</span
                >

                <span v-if="!editMode[index]" v-html="comment.content"/>
                <div v-else>
                    <EditComment :comment="comment" @update-comments="refreshComments" @toggle-edit-comment="editMode[index] = false" :key="comment.id" />
                </div>
            </div>
            <div class="flex gap-1 items-center" v-if="!editMode[index]">
                <div
                    v-if="
                        userAuth !== null &&
                        comment.poster.id === userAuth.id
                    "
                >
                    <InputButton type="warning" @click="editMode[index] = true"
                        >Edit</InputButton
                    >
                </div>
                <DeleteComment
                    :auth="userAuth"
                    :comment="comment"
                    @delete-comment="refreshComments"
                />
            </div>
        </div>

        <Pagination
            :itemsPerPage="10"
            :totalItems="props.comments"
            @update-pagination-data="getPaginationData"
        />
    </div>
</template>
<script setup>
import { onBeforeMount, ref, watch } from "vue";
import { formatDate } from "@/composables/dates.js";

import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();
const userAuth = ref(null);

const editMode = ref([]);

import Pagination from "@/components/Pagination.vue";
import InputButton from "@/components/InputButton.vue";

import EditComment from "./EditComment.vue";
import DeleteComment from "./DeleteComment.vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    comments: {
        type: Object,
        required: true,
    },
});
onBeforeMount(async() => {
    userAuth.value = await authStore.getUserData();
});

const comments = ref([]);
const getPaginationData = (data, page) => {
	comments.value = data;
	//currentPage.value = page;
};

const emit = defineEmits(['refresh-comments']);
const refreshComments = () => {
    emit('refresh-comments');
}
</script>
