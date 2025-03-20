<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputButton from '@/Components/InputButton.vue';
import InputText from '@/Components/InputText.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
        onSuccess: () => {
            toast.info("You have successfully confirmed your password.");
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
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <form>
            <div>
                <InputText
                    label="Password"
                    name="Password"
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                    :error="form.errors.password"
                />    
            </div>

            <div class="mt-4 flex justify-end">
                <InputButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submit"
                >
                    Confirm
                </InputButton>
            </div>
        </form>
    </GuestLayout>
</template>
