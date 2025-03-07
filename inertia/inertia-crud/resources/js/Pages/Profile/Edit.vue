<script setup>
import Layout from "@/Layouts/Layout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <Layout title="Profile">
        <div class="flex flex-col gap-2 justify-center items-center h-full w-full">
        <div class="flex gap-2 w-full ">
            <div class="bg-white w-full p-4 shadow sm:rounded-lg sm:p-8">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div class="bg-white w-full p-4 shadow sm:rounded-lg sm:p-8">
                <UpdatePasswordForm class="max-w-xl" />
            </div>
        </div>
        <div class="bg-white w-full p-4 shadow sm:rounded-lg sm:p-8">
            <DeleteUserForm class="max-w-xl" />
        </div>
        </div>
    </Layout>
</template>
