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
					v-model="current_password"
					type="password"
					class="mt-1 block w-full"
					autocomplete="current-password"
					:error="errors.current_password"
				/>
			</div>

			<div>
				<InputText
					id="password"
					name="New Password"
					ref="passwordInput"
					v-model="password"
					type="password"
					class="mt-1 block w-full"
					autocomplete="new-password"
					:error="errors.password"
				/>
			</div>

			<div>
				<InputText
					id="password_confirmation"
					name="Confirm Password"
					v-model="password_confirmation"
					type="password"
					class="mt-1 block w-full"
					autocomplete="new-password"
					:error="errors.password_confirmation"
				/>
			</div>

			<div class="flex items-center gap-4">
				<InputButton :disabled="loading" @click="updatePassword">Save</InputButton>

				<Transition
					enter-active-class="transition ease-in-out"
					enter-from-class="opacity-0"
					leave-active-class="transition ease-in-out"
					leave-to-class="opacity-0"
				>
					<p v-if="recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
				</Transition>
			</div>
		</form>
	</section>
</template>
<script setup>
import InputButton from "@/components/InputButton.vue";
import InputText from "@/components/InputText.vue";
import { ref, watch } from "vue";
import { toast } from "vue3-toastify";
import { useProfileStore } from "@/stores/profile";
const profileStore = useProfileStore();

const current_password = ref("");
const password = ref("");
const password_confirmation = ref("");
const errors = ref({});
const recentlySuccessful = ref(false);

const loading = ref(false);
watch(
	() => profileStore.loading,
	(value) => (loading.value = value),
);

const updatePassword = async () => {
	if (loading.value) {
		return;
	}

	try {
		await profileStore.updatePassword({
			current_password: current_password.value,
			password: password.value,
			password_confirmation: password_confirmation.value,
		});

		recentlySuccessful.value = true;
		toast.success("Password updated.");

		setTimeout(() => {
			recentlySuccessful.value = false;
		}, 5000);
	} catch (error) {
		if (error.response) {
			errors.value = {};
			const response_errors = error.response.data.errors;
			for (const error in response_errors) {
				toast.error(response_errors[error]);
				errors.value[error] = response_errors[error][0];
			}

			resetForm();
		}
	} finally {
		resetForm();
	}
};

const resetForm = () => {
	current_password.value = "";
	password.value = "";
	password_confirmation.value = "";
};
</script>
