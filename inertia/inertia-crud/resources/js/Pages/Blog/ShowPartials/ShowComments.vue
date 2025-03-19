<template>
    <div v-if="props.show" class="flex flex-col gap-2 p-2 bg-gray-100 rounded">
        <h1 class="text-xl">Comments</h1>
        <div
            v-for="comment in props.comments.data"
            :key="comment.id"
            class="flex justify-between bg-white border border-gray-50 p-2 rounded"
        >
            <div class="flex flex-col gap-1">
                <span class="text-gray-400"
                    >Comment by {{ comment.poster.name }} on
                    {{ formatDate(comment.created_at) }}</span
                >
                <span>{{ comment.content }}</span>
            </div>
            <DeleteComment :comment="comment" @delete-comment="emit('refresh-comments')"/>
        </div>

        <Pagination :pagination="props.comments" />
    </div>
</template>
<script setup>
import { formatDate } from "@/Composables/dates.js";
import DeleteComment from "./DeleteComment.vue";

import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    comments: {
        type: Object,
        required: true,
    }
});
const emit = defineEmits(["refresh-comments"]);

</script>
