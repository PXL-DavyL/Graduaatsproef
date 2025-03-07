<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputButton from '@/Components/InputButton.vue';
import InputText from '@/Components/InputText.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputText
                    id="name"
                    name="Name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    :error="form.errors.name"
                />    
            </div>

            <div class="mt-4">
                <InputText
                    id="email"
                    name="Email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :error="form.errors.email"
                />    
            </div>

            <div class="mt-4">

                <InputText
                    id="password"
                    name="Password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    :error="form.errors.password"
                />    
            </div>

            <div class="mt-4">
                <InputText
                    id="password_confirmation"
                    name="Confirm Password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    :error="form.errors.password_confirmation"
                />    
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>

                <InputButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </InputButton>
            </div>
        </form>
    </GuestLayout>
</template>
