<template>
    <div class="relative">
        <div
            class="border border-zinc-600 bg-zinc-400 text-zinc-700 focus:border-indigo-600 focus:ring-indigo-600 w-full p-2 rounded-md flex justify-between items-center"
            @click="collapseSelect = !collapseSelect"
        >
            <span>{{ value }}</span>
            <IconChevron
                class="transition-transform duration-200"
                :class="{ 'rotate-180': collapseSelect }"
            />
        </div>
        <div
            v-if="collapseSelect"
            class="absolute w-full bg-zinc-400 border border-zinc-600 rounded mt-1"
        >
            <div
                v-for="option in props.options"
                :key="option"
                @click="
                    () => {
                        value = option;
                        collapseSelect = false;
                    }
                "
                class="p-2 cursor-pointer hover:bg-zinc-500"
            >
                {{ option }}
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import IconChevron from "./icons/IconChevron.vue";

const collapseSelect = ref(false);

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});

const value = defineModel({
    type: String,
    required: true,
});
</script>
