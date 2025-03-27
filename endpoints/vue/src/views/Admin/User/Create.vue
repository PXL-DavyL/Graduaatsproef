<template>
    <AdminLayout title="Create User">
        <form class="flex flex-col gap-4">
            <InputText
                id="name"
                name="Name"
                type="text"
                v-model="name"
                required
                autocomplete="name"
                :error="errors.name"
                :darkmode="true"
            />

            <InputText
                id="email"
                name="Email"
                type="email"
                v-model="email"
                required
                autocomplete="email"
                :error="errors.email"
                :darkmode="true"
            />

            <InputText
                id="password"
                name="Password"
                type="password"
                v-model="password"
                autocomplete="password"
                :error="errors.password"
                :darkmode="true"
            />
            <InputText
                id="password_confirmation"
                name="Confirm password"
                type="password"
                v-model="password_confirmation"
                autocomplete="password_confirmation"
                :error="errors.password_confirmation"
                :darkmode="true"
            />

            <InputButton type="primary" class="w-full" :disabled="loading" @click="createUser">
                Create User 
            </InputButton>
        </form>

    </AdminLayout>
</template>
<script setup>

import { ref } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();

import { useUserStore } from "@/stores/users";
const userStore = useUserStore();

import { toast } from "vue3-toastify";

import AdminLayout from '@/layout/AdminLayout.vue';

import InputButton from "@/components/InputButton.vue";
import InputText from "@/components/InputText.vue";

const loading = ref(userStore.loading);

const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");

const errors = ref({});


const createUser = async() => {

    try {
        const response = await userStore.createUser({
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        });
        toast.success("User created successfully");

        const new_user_id = response.data.user.id;
        router.push({ name: "AdminEditUser", params: { id: new_user_id } });
    }
    catch(error) {
        if (error.response) {
			errors.value = {};
			const response_errors = error.response.data.errors;
			for (const error in response_errors) {
				toast.error(response_errors[error]);
				errors.value[error] = response_errors[error][0];
			}
		}
    }
    finally {
        name.value = "";
        email.value = "";
        password.value = "";
        password_confirmation.value = "";
    }
};
</script>
