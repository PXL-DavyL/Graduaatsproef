<template>
    <div class="flex flex-col w-full relative">
        <label
            v-if="props.name && props.name.length > 1"
            :for="props.id"
            class="block text-sm font-medium text-gray-700"
        >
            {{ props.name }}
        </label>
        <div
            class="bg-gray-50 border border-gray-200 rounded w-full p-2"
            @click="showList = !showList"
        >
            {{ placeholder.name }}
        </div>
        <div
            v-if="showList"
            class="absolute top-16 w-full flex flex-col  bg-gray-50 border border-gray-200 shadow rounded p-2"
        >
            <input
                type="text"
                placeholder="Search..."
                v-model="searchValue"
                @input="filterResults()"
                class="w-full p-2 border border-gray-200 rounded"
            />
            <div class="mt-2 max-h-48 overflow-auto flex flex-col">
                <span
                    v-for="option in options"
                    @click="onSelectOption(option)"
                    :key="option.id"
                    class="hover:bg-white cursor-pointer mt-2"
                >
                    {{ option.name }}
                </span>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const showList = ref(false);

const props = defineProps({
    name: {
        type: String,
        required: false,
    },
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
    console.log(option);
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
