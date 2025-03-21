<script setup>
import { computed } from "vue";

const emit = defineEmits(["update:checked"]);

const props = defineProps({
    text: {
        type: String,
        required: false,
    },
    name: {
        type: String,
        required: true,
    },
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },    
    error: {
        type: String,
        required: false,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit("update:checked", val);
    },
});
</script>

<template>
    <div class="flex gap-2 items-center">
        <input
            type="checkbox"
            :name="props.name"
            :value="value"
            v-model="proxyChecked"
            class="rounded border-gray-300 text-sky-400 shadow-sm focus:ring-sky-400"
        />

        <span class="text-sm text-gray-600" v-if="props.text.length > 0">{{
            props.text
        }}</span>
    </div>
    <span
            v-if="props.error"
        class="mt-2 text-sm text-red-600"
        role="alert"
    >
        {{ props.error }}
    </span>
</template>
