<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputButton from '@/Components/InputButton.vue';
import InputText from '@/Components/InputText.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onSuccess: () => {
            toast.info("We have sent an email containing a password reset link.");
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
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

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

            <div class="mt-4 flex items-center justify-end">
                <InputButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submit"
                >
                    Email Password Reset Link
                </InputButton>
            </div>
        </form>
    </GuestLayout>
</template>
