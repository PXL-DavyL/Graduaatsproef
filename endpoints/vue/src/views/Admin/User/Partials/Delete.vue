<template>
    <div class="flex flex-col gap-2 w-full">
    <InputButton type="danger" @click="showDeleteContainer = !showDeleteContainer">
        Delete User
    </InputButton>

    <div v-if="showDeleteContainer" class="bg-zinc-600 border border-zinc-700 text-zinc-100 p-2 rounded-md flex flex-col gap-2">
        <p class="text-sm">
            Are you sure you want to delete this user?
        </p>

        <InputText
            id="password"
            name="Confirm your password"
            type="password"
            v-model="confirm_password"
            required
            autocomplete="confirm_password"
            :error="errors.confirm_password"
            :darkmode="true"
        />

        <div class="flex gap-2 mt-2">
            <InputButton type="danger" @click="showDeleteContainer = false">
                Cancel
            </InputButton>

            <InputButton type="primary" @click="deleteUser">
                Delete
            </InputButton>
        </div>
    </div>
</div>
</template>
<script setup>
import { ref } from 'vue';
import { toast } from "vue3-toastify";
import { useUserStore } from '@/stores/users';

const userStore = useUserStore();

import InputButton from '@/components/InputButton.vue';
import InputText from '@/components/InputText.vue';
import { useRouter } from 'vue-router';
const router = useRouter();

const showDeleteContainer = ref(false);

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const confirm_password = ref('');
const errors = ref({});


const deleteUser = async() => {

    try {
        const response = await userStore.deleteUser({
            user: props.user.id,
            confirm_password: confirm_password.value
        });

        toast.success('User deleted successfully');
        router.push("/admin");

    } catch (error) {
        if (error.response) {
			errors.value = {};
			const response_errors = error.response.data.errors;
			for (const error in response_errors) {
				toast.error(response_errors[error]);
				errors.value[error] = response_errors[error][0];
			}

            confirm_password.value = '';
		}
    }
}

</script>
