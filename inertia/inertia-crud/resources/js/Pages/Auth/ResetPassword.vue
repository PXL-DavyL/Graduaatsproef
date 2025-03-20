<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputButton from '@/Components/InputButton.vue';
import InputText from '@/Components/InputText.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onSuccess: () => {
            toast.warning("Your password has been reset.");
        },
        onError: (err) => {
            for(const error in err) {
                toast.error(err[error]);
            }
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <form>
            <div>

                <InputText
                    id="email"
                    name="Email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
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
                <InputButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submit"
                >
                    Reset Password
                </InputButton>
            </div>
        </form>
    </GuestLayout>
</template>
