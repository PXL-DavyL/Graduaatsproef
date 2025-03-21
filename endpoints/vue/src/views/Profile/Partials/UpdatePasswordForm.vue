<template>
	<section>
		<header>
			<h2 class="text-lg font-medium text-gray-900">Update Password</h2>

			<p class="mt-1 text-sm text-gray-600">
				Ensure your account is using a long, random password to stay secure.
			</p>
		</header>

		<form class="mt-6 space-y-6">
			<div>
				<InputText
					id="current_password"
					name="Current Password"
					ref="currentPasswordInput"
					v-model="form.current_password"
					type="password"
					class="mt-1 block w-full"
					autocomplete="current-password"
					:error="form.errors.current_password"
				/>
			</div>

			<div>
				<InputText
					id="password"
					name="New Password"
					ref="passwordInput"
					v-model="form.password"
					type="password"
					class="mt-1 block w-full"
					autocomplete="new-password"
					:error="form.errors.password"
				/>
			</div>

			<div>
				<InputText
					id="password_confirmation"
					name="Confirm Password"
					v-model="form.password_confirmation"
					type="password"
					class="mt-1 block w-full"
					autocomplete="new-password"
					:error="form.errors.password_confirmation"
				/>
			</div>

			<div class="flex items-center gap-4">
				<InputButton :disabled="form.processing" @click="updatePassword">Save</InputButton>

				<Transition
					enter-active-class="transition ease-in-out"
					enter-from-class="opacity-0"
					leave-active-class="transition ease-in-out"
					leave-to-class="opacity-0"
				>
					<p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
				</Transition>
			</div>
		</form>
	</section>
</template>
<script setup>
import InputButton from '@/components/InputButton.vue';
import InputText from '@/components/InputText.vue';
import { ref } from 'vue';
import { toast } from 'vue3-toastify';

const form = ref({
    current_password: '',
    password: '',
    password_confirmation: '',
    processing: false,
    recentlySuccessful: false,
    errors: {
        current_password: null,
        password: null,
        password_confirmation: null,
    },
});

</script>