<template>
    <div class="bg-white w-full p-3 sm:p-6 shadow sm:rounded-lg flex flex-col gap-2">
        <h2 class="text-lg font-medium text-gray-900">
            Update blog author
        </h2>

        <span class="text-sm text-gray-600">
            You can browse a list of users and change the author of the blog post using the dropdown below.
        </span>

        <div class="flex gap-2 flex-wrap md:flex-nowrap">
            <InputUserSelect
                name="Author"
                :options="usePage().props.users"
                v-model="form.author"
                class="w-full"
            />

            <div class="w-full md:w-48 md:h-10 self-end">
                <InputButton @click="submit()" class="w-full h-full">
                    Change author
                </InputButton>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

import InputUserSelect from "@/Components/InputUserSelect.vue";
import InputButton from "@/Components/InputButton.vue";

console.log(usePage().props.blog.poster);

const form = useForm({
    author: ref(usePage().props.blog.poster),
});

const submit = () => {
    form.patch(route("blog.update", usePage().props.blog.id), {
        
        onSuccess: () => {
            console.log('Author updated');
        },
        onFinish: () => {
            form.author = usePage().props.blog.poster;
        },
        onError: (err) => {
            console.error('Error updating author', err);
        },
    });
};

</script>
