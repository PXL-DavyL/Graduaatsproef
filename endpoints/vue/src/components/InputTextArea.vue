<template>
    <div>
        <label
            v-if="props.name.length > 1"
            :for="props.id"
            class="block text-sm font-medium text-gray-700"
            :class="{'text-zinc-300': props.darkmode}"
        >
            {{ props.name }}
        </label>
        <textarea
            :id="props.id"
            :type="props.type"
            :required="props.required"
            :autofocus="props.autofocus"
            :placeholder="props.placeholder"
            class="p-2 rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 w-full min-h-48 resize-none"
            :class="{ 'border-zinc-600 bg-zinc-400 text-zinc-700 focus:border-indigo-600 focus:ring-indigo-600': props.darkmode }"
            v-model="model"
        />
        
        <span
            v-if="props.error"
            class="mt-2 text-sm text-red-600"
            role="alert"
        >
            {{ props.error }}
        </span>
    </div>
</template>

<script setup>
const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: false,
    },
    placeholder: {
        type: String,
        default: "",
    },
    required: {
        type: Boolean,
        default: false,
    },
    autofocus: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        required: false,
    },
    darkmode: {
        type: Boolean,
        default: false,
    }
});

const model = defineModel({
    type: String,
    required: true,
});

const focus = () => {
    const input = document.getElementById(props.id);
    if (input) input.focus();
};

defineExpose({ focus });
</script>
