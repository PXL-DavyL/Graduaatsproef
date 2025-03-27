<template>
    <div
        class="bg-gray-50 border border-gray-100 p-2 rounded w-full flex flex-col gap-2"
    >
        <InputTextArea
            name="Post new comment"
            id="content"
            v-model="content"
            class="w-full"
            :error="errors.content"
        />

        <InputButton class="w-full" @click="submitComment"
            >Post comment</InputButton
        >
    </div>
</template>
<script setup>
import { ref } from "vue";
const emit = defineEmits(["add-comment"]);

import { useCommentStore } from "@/stores/comments";
const commentStore = useCommentStore();

import { useRoute } from "vue-router";
const blog_id = Number(useRoute().params.id);

import { toast } from "vue3-toastify";

import InputButton from "@/components/InputButton.vue";
import InputTextArea from "@/components/InputTextArea.vue";

const content = ref("");
const errors = ref({});

const submitComment = async() => {
    
    try {
        const response = await commentStore.addComment({
            content: content.value,
            blog_id: blog_id,
        });

        toast.success("You have posted a new comment.");
        emit('add-comment');
    }
    catch(error) {
        console.log(error);
    }
}
</script>
