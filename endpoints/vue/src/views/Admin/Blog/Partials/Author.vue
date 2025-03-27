<template>
    <div>
        <span
            class="block text-sm font-medium text-zinc-300"
        >
            Change post author
        </span>

        <InputUserSelect
            :options="userList"
            v-model="author"
            class="w-full"
        />
    </div>
</template>
<script setup>
import { onBeforeMount, ref, watch } from "vue";
import { useUserStore } from "@/stores/users";
const userStore = useUserStore();

import InputUserSelect from "@/components/InputUserSelect.vue";

const props = defineProps({
    blog: {
        type: Object,
        required: true,
    },
});

const userList = ref([]);
onBeforeMount(async() => {
    const response = await userStore.getAllUsers();
    const userListObj = response.data.users;

    for(const user in userListObj) {
        userList.value.push({
            id: userListObj[user].id,
            name: userListObj[user].name,
        });
    }
});

const emit = defineEmits(["update-blog-author"])

const author = ref(props.blog.poster);
watch(() => author.value, () => {
    emit("update-blog-author", author.value);
});
</script>
