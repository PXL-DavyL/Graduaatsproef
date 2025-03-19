<template>
    <AdminLayout title="Create User">
        <form @submit.prevent="createUser" class="flex flex-col gap-4">
            <InputText
                id="name"
                name="Name"
                type="text"
                v-model="form.name"
                required
                autocomplete="name"
                :error="form.errors.name"
                :darkmode="true"
            />

            <InputText
                id="email"
                name="Email"
                type="email"
                v-model="form.email"
                required
                autocomplete="email"
                :error="form.errors.email"
                :darkmode="true"
            />

            <InputText
                id="password"
                name="Password"
                type="password"
                v-model="form.password"
                autocomplete="password"
                :error="form.errors.password"
                :darkmode="true"
            />
            <InputText
                id="password_confirmation"
                name="Confirm password"
                type="password"
                v-model="form.password_confirmation"
                autocomplete="password_confirmation"
                :error="form.errors.password_confirmation"
                :darkmode="true"
            />

            <InputButton type="primary" class="w-full" :disabled="form.processing">
                Create User 
            </InputButton>
        </form>

    </AdminLayout>
</template>
<script setup>
import { useForm } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

import AdminLayout from '@/Layouts/AdminLayout.vue';

import InputButton from "@/Components/InputButton.vue";
import InputText from "@/Components/InputText.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const createUser = () => {
    form.post(route('admin.users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("User created successfully");
            form.reset();
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
};
</script>
