<template>
	<AdminLayout title="Manage reactions">
		<form class="flex flex-col gap-4" v-if="reaction">
			<div class="flex flex-col gap-1">
				<span class="block text-sm font-medium text-zinc-300"> Reaction ID: </span>
				<span
					class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
				>
					{{ reaction.id }}
				</span>
			</div>

			<div class="flex flex-col gap-1">
				<span class="block text-sm font-medium text-zinc-300"> Author: </span>
				<span
					class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
				>
					{{ reaction_author }}
				</span>
			</div>

			<div class="flex flex-col gap-1">
				<span class="block text-sm font-medium text-zinc-300"> Type: </span>
				<InputSelect v-model="type" :options="['upvote', 'downvote']" />
			</div>

			<div class="flex flex-col gap-1">
				<span class="block text-sm font-medium text-zinc-300"> Created at: </span>
				<span
					class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
				>
					{{ formatDate(reaction.created_at) }}
				</span>
			</div>

			<div class="flex flex-col gap-1">
				<span class="block text-sm font-medium text-zinc-300"> Last updated at: </span>
				<span
					class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
				>
					{{ formatDate(reaction.updated_at) }}
				</span>
			</div>
		</form>

		<div class="flex flex-col gap-2">
			<InputButton
				class="mt-4"
				:class="{ 'opacity-25': loading }"
				:disabled="loading"
				@click="editReaction"
			>
				Save reaction
			</InputButton>

			<Delete :reaction="reaction" />

			<InputButton @click="backToReactions" class="w-full" type="secondary">
				Back to reactions
			</InputButton>
		</div>
	</AdminLayout>
</template>
<script setup>
import { useReactionStore } from "@/stores/reactions";
import { onBeforeMount, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
const router = useRouter();
import { formatDate } from "@/composables/dates";
const reactionStore = useReactionStore();

import AdminLayout from "@/layout/AdminLayout.vue";
import { toast } from "vue3-toastify";

import InputButton from "@/components/InputButton.vue";
import InputButtonLink from "@/components/InputButtonLink.vue";
import InputSelect from "@/components/InputSelect.vue";

const reaction = ref([]);
const reaction_author = ref("");

const type = ref("");

onBeforeMount(async () => {
	try {
		const response = await reactionStore.getReactionAsAdmin({
			id: useRoute().params.reaction,
		});
		reaction.value = response.data.reaction;
		reaction_author.value = reaction.value.poster.name;
		type.value = reaction.value.type;
		console.log(reaction.value);
	} catch (error) {
		console.log(error);
	}
});

const loading = ref(false);
watch(
	async () => reactionStore.loading,
	(value) => (reactionStore.value = value),
);

const editReaction = async () => {
	console.log(type.value);
	try {
		const response = await reactionStore.updateReactionAsAdmin({
			id: reaction.value.id,
			type: type.value,
		});
		toast.success("Reaction updated successfully.");
	} catch (error) {
		toast.error("Failed to update reaction.");
		console.log(error);
	}
};

const backToReactions = () => {
	router.push({ name: "AdminListBlogReactions", params: { id: reaction.value.blog_id } });
};
</script>
