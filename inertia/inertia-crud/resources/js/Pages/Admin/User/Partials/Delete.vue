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
            v-model="form.confirm_password"
            required
            autocomplete="confirm_password"
            :error="form.errors.confirm_password"
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
import { useForm } from '@inertiajs/vue3';
import { toast } from "vue3-toastify";

import InputButton from '@/Components/InputButton.vue';
import InputText from '@/Components/InputText.vue';

const showDeleteContainer = ref(false);

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const form = useForm({
    confirm_password: ''
});

const deleteUser = () => {
    form.delete(route('admin.users.destroy', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.warning("User deleted successfully. All blogs posted by the user have been truncated.");
            form.clearErrors();
            showDeleteContainer.value = false;
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
}

</script>
