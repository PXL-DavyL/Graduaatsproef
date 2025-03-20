<template>
    <Layout :title="blog.title">
        <div class="flex flex-col gap-4">
            <div class="flex justify-between p-2 bg-gray-100 rounded">
                <span
                    >Posted by {{ blog.poster.name }} on
                    {{ formatDate(blog.created_at) }}</span
                >

                <span v-if="blog.created_at != blog.updated_at"
                    >Last changed on {{ formatDate(blog.updated_at) }}</span
                >
            </div>

            <span v-html="blog.content"></span>

            <div class="flex gap-2 w-full">
                <InputButton @click="showComments = !showComments">
                    Comments
                    <IconChevron
                        class="transform transition duration-300"
                        :class="{ 'rotate-180': showComments }"
                    />
                </InputButton>
                <div class="flex gap-2">
                    <InputButton
                        v-for="reaction in processedReactions"
                        type="secondary"
                        :key="reaction.id"
                        class="flex gap-2"
                        :class="{
                            'bg-sky-300':
                                getUserSubmittedReaction() !== undefined &&
                                getUserSubmittedReaction().type === reaction.id,
                        }"
                        @click="toggleReaction(reaction.id)"
                    >
                        <span v-html="reaction.icon"></span>
                        <span
                            v-if="reaction.count > 0"
                            v-html="reaction.count"
                        ></span>
                    </InputButton>
                </div>
                <div
                    class="self-end"
                    v-if="
                        usePage().props.auth.user !== null &&
                        usePage().props.auth.user.id == blog.poster.id
                    "
                >
                    <InputButtonLink
                        :href="route('blog.edit', blog.id)"
                        type="warning"
                        method="get"
                    >
                        Edit
                    </InputButtonLink>
                </div>
            </div>

            <ShowComments
                :comments="comments"
                :show="showComments"
                @refresh-comments="refreshComments"
            />
            <AddComment @submit-comment="refreshComments" />
        </div>
    </Layout>
</template>
<script setup>
import { onMounted, ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates.js";
import { toast } from "vue3-toastify";

import Layout from "@/Layouts/Layout.vue";
import InputButton from "@/Components/InputButton.vue";
import InputButtonLink from "@/Components/InputButtonLink.vue";
import IconChevron from "@/Components/icons/IconChevron.vue";

import ShowComments from "./ShowPartials/ShowComments.vue";
import AddComment from "./ShowPartials/AddComment.vue";

const blog = usePage().props.blog;
const comments = ref(usePage().props.comments);
const reactions = ref(usePage().props.reactions);

const refreshComments = () => {
    comments.value = usePage().props.comments;
};

const showComments = ref(true);

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
        { upvotes: 0, downvotes: 0 }
    );

    processedReactions.value = [
        { id: "upvote", icon: "👍", count: reactionCounts.upvotes },
        { id: "downvote", icon: "👎", count: reactionCounts.downvotes },
    ];
};

const form = useForm([]);
const toggleReaction = (type) => {
    form.post(
        route("blog.reaction.toggle", {
            blog: blog.id,
            type: type,
        }),
        {
            onFinished: () => {
                processReactions();
            },
            onSuccess: () => {
                reactions.value = usePage().props.reactions;

                switch(type) {
                    case 'upvote':
                        toast.success("Upvoted successfully.");
                        break;
                    case 'downvote':
                        toast.warning("Downvoted successfully.");
                        break;
                }

                processReactions();
            },
            onError: (err) => {
                for (const error in err) {
                    toast.error(err[error]);
                }
            },
        }
    );
};

const getUserSubmittedReaction = () => {
    return reactions.value.find(
        (reaction) => reaction.poster_id === usePage().props.auth.user.id
    );
};

onMounted(() => {
    processReactions();
});
</script>
