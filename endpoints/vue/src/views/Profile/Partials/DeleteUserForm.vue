
<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Delete Account
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <InputButton type="danger" @click="confirmUserDeletion">Delete Account</InputButton>
        
        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900"
                >
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>


                <div class="mt-6">
                    <InputText
                        id="password"
                        name="Password"
                        ref="passwordInput"
                        v-model="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                        :error="errors.password"
                    />
                </div>

                <div class="mt-6 flex justify-end">
                    <InputButton type="secondary" @click="closeModal">
                        Cancel
                    </InputButton>

                    <InputButton
                        type="danger"
                        class="ms-3"
                        :class="{ 'opacity-25': loading }"
                        :disabled="loading"
                        @click="deleteUser"
                    >
                        Delete Account
                    </InputButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

<script setup>

import InputButton from '@/components/InputButton.vue';
import InputText from '@/components/InputText.vue';
import Modal from '@/components/Modal.vue';
import { nextTick, ref, watch } from 'vue';
import { toast } from 'vue3-toastify';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const password = ref('');

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const errors = ref({});

const loading = ref(false);
const status = ref("");
/*
    make 'userStore' for profile changes, every module will have it's own store
watch(
	() => authStore.loading,
	(value) => (loading.value = value),
);*/

const closeModal = () => {
    confirmingUserDeletion.value = false;

    password.value = '';
    errors.value = {};
};

const deleteUser = () => {
    console.log('Deleting user...');
}
</script>