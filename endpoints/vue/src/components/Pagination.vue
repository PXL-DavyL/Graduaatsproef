<template>
	<div class="w-full flex gap-2 justify-center items-center">
		<span
			@click="prevPage()"
			class="p-2 rounded"
			:class="{
				'bg-zinc-600 border border-zinc-700 hover:bg-zinc-500 text-zinc-300 cursor-pointer':
					currentPage !== 0 && props.darkMode,
				'bg-zinc-600 border border-zinc-700 text-gray-400 cursor-default':
					currentPage === 0 && props.darkMode,
				'bg-gray-100 border border-gray-200 hover:bg-gray-200 hover:border-gray-300 text-gray-800 cursor-pointer':
					currentPage !== 0 && !props.darkMode,
				'bg-gray-100 border border-gray-200 text-gray-400 cursor-default':
					currentPage === 0 && !props.darkMode,
			}"
			>Previous</span
		>

		<div class="flex gap-2 items-center">
			<span
				v-for="key in getPageList()"
				@click="handlePageClick(key)"
				class="p-2 px-3 border"
				:class="{
					'bg-zinc-600 border-zinc-700 hover:bg-zinc-500 text-zinc-200 rounded cursor-pointer':
						currentPage !== key && props.darkMode,
					'bg-indigo-500 hover:bg-indigo-400 border-indigo-600 text-indigo-200 rounded cursor-default':
						currentPage === key && props.darkMode,
					'bg-gray-100 border-gray-200 hover:bg-gray-200 hover:border-gray-300 text-gray-800 rounded cursor-pointer':
						currentPage !== key && !props.darkMode,
					'bg-sky-300 hover:bg-sky-400 border-sky-500 text-sky-800 rounded cursor-default':
						currentPage === key && !props.darkMode,
				}"
			>
				{{ formatPageName(key) }}
			</span>
		</div>

		<span
			@click="nextPage()"
			class="p-2 rounded"
			:class="{
				'bg-zinc-600 border border-zinc-700 hover:bg-zinc-500 text-zinc-300 cursor-pointer':
					currentPage !== getMaxPage() && props.darkMode,
				'bg-zinc-600 border border-zinc-700 text-gray-400 cursor-default':
					currentPage === getMaxPage() && props.darkMode,
				'bg-gray-100 border border-gray-200 hover:bg-gray-200 hover:border-gray-300 text-gray-800 cursor-pointer':
					currentPage !== getMaxPage() && !props.darkMode,
				'bg-gray-100 border border-gray-200 text-gray-400 cursor-default':
					currentPage === getMaxPage() && !props.darkMode,
			}"
			>Next</span
		>
	</div>
</template>
<script setup>
import { onUpdated, ref } from "vue";

const props = defineProps({
	itemsPerPage: {
		type: Number,
		default: 10,
	},
	totalItems: {
		type: Object,
		required: true,
	},
	darkMode: {
		type: Boolean,
		default: false,
	},
});
const emits = defineEmits(["update-pagination-data"]);
const currentPage = ref(0);

onUpdated(() => {
	updateBigData();
});

const updateBigData = () => {
	const new_data = props.totalItems.slice(
		currentPage.value * props.itemsPerPage,
		currentPage.value * props.itemsPerPage + props.itemsPerPage,
	);

	emits("update-pagination-data", new_data, currentPage.value);
};

const nextPage = () => {
	currentPage.value = currentPage.value + 1;
	if (currentPage.value > getMaxPage()) {
		currentPage.value = getMaxPage();
	}
	updateBigData();
};

const prevPage = () => {
	currentPage.value = currentPage.value - 1;
	if (currentPage.value < 0) {
		currentPage.value = 0;
	}
	updateBigData();
};

const handlePageClick = (page) => {
	currentPage.value = page;
	updateBigData();
};

const getMaxPage = () => {
	return Math.floor(props.totalItems.length / props.itemsPerPage);
};

const getPageList = () => {
	const pages = [];
	for (let i = 0; i <= Math.floor(props.totalItems.length / props.itemsPerPage); i++) {
		pages.push(i);
	}
	return pages;
};

const formatPageName = (value) => {
	return value + 1;
};
</script>
