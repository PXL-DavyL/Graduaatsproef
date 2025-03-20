<template>
    <div v-if="props.show" class="flex flex-col gap-2 p-2 bg-gray-100 rounded">
        <h1 class="text-xl">Comments</h1>
        <div
            v-for="(comment, index) in props.comments.data"
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
                    <EditComment :comment="comment" @refresh-comments="refreshComments()" />
                </div>
            </div>

            <div class="flex gap-1" v-if="!editMode[index]">
                <div
                    v-if="
                        usePage().props.auth.user !== null &&
                        comment.poster.id === usePage().props.auth.user.id
                    "
                >
                    <InputButton type="warning" @click="editMode[index] = true"
                        >Edit</InputButton
                    >
                </div>
                <DeleteComment
                    :comment="comment"
                    @delete-comment="emit('refresh-comments')"
                />
            </div>
        </div>

        <Pagination :pagination="props.comments" />
    </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import { formatDate } from "@/Composables/dates.js";
import { usePage } from "@inertiajs/vue3";
import DeleteComment from "./DeleteComment.vue";

import Pagination from "@/Components/Pagination.vue";
import EditComment from "./EditComment.vue";
import InputButton from "@/Components/InputButton.vue";
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
const editMode = ref([]);

onMounted(() => {
    editMode.value = props.comments.data.map(() => false);
});

const refreshComments = () => {
    emit("refresh-comments");
    editMode.value = props.comments.data.map(() => false); // reset all edit modes
};
const emit = defineEmits(["refresh-comments"]);
</script>
