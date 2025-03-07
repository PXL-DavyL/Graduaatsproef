<template>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 w-full"
        v-model="model"
        ref="input"
    />
</template>
<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>