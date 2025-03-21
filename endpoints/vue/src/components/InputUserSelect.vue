<template>
    <div class="flex flex-col w-full relative">
        <div
            class="border border-zinc-600 bg-zinc-400 text-zinc-700 focus:border-indigo-600 focus:ring-indigo-600 w-full p-2 rounded-md flex justify-between items-center"
            @click="showList = !showList"
        >
            <span> {{ placeholder.name }}</span>
            <IconChevron
                class="transition-transform duration-200"
                :class="{ 'rotate-180': showList }"
            />
        </div>
        <div
            v-if="showList"
            class="absolute top-12 w-full flex flex-col border-zinc-600 bg-zinc-400 text-zinc-700 shadow rounded p-2"
        >

            <input
                type="text"
                placeholder="Search..."
                v-model="searchValue"
                @input="filterResults()"
                class=" rounded-md shadow-sm w-full border border-zinc-600 bg-zinc-300 text-zinc-700 focus:border-indigo-600 focus:ring-indigo-600"
            />
            <div class="mt-2 max-h-48 overflow-auto flex flex-col">
                <span
                    v-for="option in options"
                    @click="onSelectOption(option)"
                    :key="option.id"
                    class="hover:bg-zinc-700 hover:text-zinc-100 p-1 cursor-pointer mt-2"
                >
                    {{ option.name }}
                </span>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import IconChevron from "./icons/IconChevron.vue";

const showList = ref(false);

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});

const model = defineModel({
    type: Object,
    required: true,
});

const placeholder = ref(model.value);
const searchValue = ref("");

const onSelectOption = (option) => {
    model.value = option;
    placeholder.value = option;
    showList.value = false;
};

onMounted(() => document.addEventListener("click", handleClickOutside));
onUnmounted(() => document.removeEventListener("click", handleClickOutside));

const handleClickOutside = (event) => {
    if (showList.value && !event.target.closest(".relative")) {
        showList.value = false;
    }
};

const options = ref(props.options);
const filterResults = () => {
    options.value = props.options.filter((option) =>
        option.name.toLowerCase().includes(searchValue.value.toLowerCase())
    );

    // If there is no search value, reset the options
    if (searchValue.value.length < 1) {
        options.value = props.options;
    }
};
</script>
