<template>
    <div>
        <label
            v-if="props.name.length > 1"
            :for="props.id"
            class="block text-sm font-medium text-gray-700"
        >
            {{ props.name }}
        </label>
        <textarea
            :id="props.id"
            :type="props.type"
            :required="props.required"
            :autofocus="props.autofocus"
            :placeholder="props.placeholder"
            class="rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 w-full min-h-96 resize-none"
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
