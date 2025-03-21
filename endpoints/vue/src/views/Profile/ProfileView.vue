<template>
	<Layout title="Profile">
		<div class="flex flex-col gap-2 justify-center items-center h-full w-full">
			<div class="flex flex-col lg:flex-row gap-2 w-full">
				<div class="bg-white w-full p-4 shadow sm:rounded-lg sm:p-8">
					<UpdateProfileInformationForm class="max-w-xl"/>
				</div>

				<div class="bg-white w-full p-4 shadow sm:rounded-lg sm:p-8">
					<UpdatePasswordForm class="max-w-xl"/>
				</div>
			</div>

			<div class="bg-white w-full p-4 shadow sm:rounded-lg sm:p-8">
                <DeleteUserForm class="max-w-xl"/>
            </div>
		</div>
	</Layout>
</template>
<script setup>
import Layout from "@/layout/Layout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
</script>
