<template>
    <div>
        <span
            class="block text-sm font-medium text-zinc-300"
        >
            Change post author
        </span>

        <InputUserSelect
            :options="usePage().props.users"
            v-model="author"
            class="w-full"
        />
    </div>
</template>
<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

import InputUserSelect from "@/Components/InputUserSelect.vue";


const emit = defineEmits(["update-blog-author"])

const author = ref(usePage().props.blog.poster);
watch(() => author.value, () => {
    emit("update-blog-author", author.value);
});
</script>
