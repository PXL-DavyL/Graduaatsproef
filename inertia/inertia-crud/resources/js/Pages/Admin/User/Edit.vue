<template>
    <AdminLayout title="Edit User">
        <form @submit.prevent="editUser" class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    User ID:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                    {{ usePage().props.user.id }}
                </span>
            </div>

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

            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Registered at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                {{ formatDate(usePage().props.user.created_at) }}
                </span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="block text-sm font-medium text-zinc-300">
                    Last update at:
                </span>
                <span
                    class="border border-zinc-700 bg-zinc-600 text-zinc-400 p-2 rounded-md w-full"
                >
                {{ formatDate(usePage().props.user.updated_at) }}
                </span>
            </div>

            <InputButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save User
            </InputButton>
        </form>

        <Permissions class="mt-4"/>
        <Delete :user="usePage().props.user" class="mt-4" />
    </AdminLayout>
</template>
<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { formatDate } from "@/Composables/dates";
import { toast } from "vue3-toastify";

import InputButton from "@/Components/InputButton.vue";
import InputText from "@/Components/InputText.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Delete from "./Partials/Delete.vue";
import Permissions from "./Partials/Permissions.vue";

const form = useForm({
    name: usePage().props.user.name,
    email: usePage().props.user.email,
    password: "",
    password_confirmation: "",
});

const editUser = () => {
    form.patch(route("admin.users.update", usePage().props.user.id), {
        onFinish: () => form.reset(),
        onSuccess: () => {
            toast.success("User updated successfully.");
            form.clearErrors();
            form.password = "";
            form.password_confirmation = "";
        },
        onError: (err) => {
            for (const error in err) {
                toast.error(err[error]);
            }
        },
    });
}
</script>
