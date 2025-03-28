<template>
	<div class="flex flex-col gap-2">
		<InputButton type="warning" @click="collapseDropdown = !collapseDropdown" class="w-full">
			<span>{{ collapseDropdown ? "Hide" : "Show" }} permissions</span>
			<IconChevron
				class="h-4 w-4 transition duration-300"
				:class="{
					'transform rotate-180 transition duration-300': collapseDropdown,
				}"
			/>
		</InputButton>

		<div
			v-if="collapseDropdown"
			class="bg-zinc-600 border border-zinc-700 text-zinc-100 p-2 rounded-md flex flex-col gap-1"
		>
			<span class="text-sm">Click on a permission to toggle it.</span>

			<span
				v-if="loadingPermissions || loadingUserPermissions"
				class="w-full flex flex-col gap-2 justify-center items-center"
			>
				<span class="font-xl">Loading permissions...</span>
				<IconLoading class="w-16 h-16" />
			</span>

			<span
				v-else
				v-for="permission in permissions"
				:key="permission.id"
				class="rounded p-2 cursor-pointer"
				:class="{
					'bg-emerald-200 border border-emerald-300 hover:bg-emerald-300 hover:border-emerald-400 text-emerald-800':
						doesUserHavePermission(permission),
					'bg-rose-200 border border-rose-300 hover:bg-rose-300 hover:border-rose-400 text-rose-800':
						!doesUserHavePermission(permission),
				}"
				@click="togglePermission(permission)"
			>
				{{ permission.name }}
			</span>

			<span class="text-sm">Alternatively, you may choose to make this user an admin:</span>
			<div @click="makeAdmin" class="w-full">
				<span
					class="block w-full rounded p-2 bg-amber-200 border border-amber-300 hover:bg-amber-300 hover:border-amber-400 text-amber-800 cursor-pointer"
					v-if="isUserAdmin === true"
				>
					Remove admin rights
				</span>
				<span
					v-else
					class="block w-full rounded p-2 text-indigo-800 bg-indigo-200 border border-indigo-300 hover:bg-indigo-300 hover:border-indigo-400 cursor-pointer"
				>
					Make this user an admin
				</span>
			</div>
		</div>
	</div>
</template>
<script setup>
import { onBeforeMount, ref } from "vue";
import { useRoute } from "vue-router";
import { toast } from "vue3-toastify";
import axios from "axios";

import { getCsrfToken } from "@/stores/utils";
import { useRoleStore } from "@/stores/roles";

const user_id = useRoute().params.id;
const roleStore = useRoleStore();

import IconChevron from "@/components/icons/IconChevron.vue";
import InputButton from "@/components/InputButton.vue";
import IconLoading from "@/components/icons/IconLoading.vue";

const collapseDropdown = ref(false);
const permissions = ref([]);
const userPermissions = ref({});

const isUserAdmin = ref(false);

const loadingPermissions = ref(true);
const loadingUserPermissions = ref(true);

onBeforeMount(async () => {
	await loadAllPermissions();
	await loadUserPermissions();
    await loadUserAdmin();
});

const doesUserHavePermission = (permission) => {
    if(isUserAdmin.value) {
        return true;
    }
	return userPermissions.value.some((p) => p.name === permission.name);
};

const loadUserAdmin = async() => {
    const roles = await roleStore.getUserRoles({
        user: user_id,
    });

    if(roles.data.roles.length) {
        if(roles.data.roles.includes('admin')) {
            isUserAdmin.value = true;
        } else {
            isUserAdmin.value = false;
        }
    }
}

const loadAllPermissions = async () => {
	try {
		loadingPermissions.value = true;
		await getCsrfToken();
		const response = await axios.get("http://localhost:8000/api/admin/get-permissions", {
			headers: {
				Accept: "application/json",
				"Content-Type": "application/json",
			},
			withCredentials: true,
		});

		permissions.value = response.data.permissions;
	} catch (error) {
		console.error("Failed to fetch roles:", error);
		throw error;
	} finally {
		loadingPermissions.value = false;
	}
};

const loadUserPermissions = async () => {
	try {
		loadingUserPermissions.value = true;
		const response = await roleStore.getUserPermissions({
			user: user_id,
		});
		userPermissions.value = response.data.permissions;
	} catch (error) {
		console.error("Failed to fetch roles:", error);
		throw error;
	} finally {
		loadingUserPermissions.value = false;
	}
};

const togglePermission = async (permission) => {
	const result = doesUserHavePermission(permission);

	if (!result) {
		toast.success("Permission granted successfully");
		userPermissions.value.push(permission.name);
	} else {
		toast.warning(`Permission revoked successfully`);
		userPermissions.value.splice(userPermissions.value.indexOf(permission.name), 1);
	}

	await roleStore.saveUserPermissions({
		user: user_id,
		permission: permission,
	});

	await loadUserPermissions();
};

const makeAdmin = async() => {
    const result = isUserAdmin.value;
    await roleStore.setUserAdmin({
        user: user_id
    });

    if(!result) {
        isUserAdmin.value = true;
        toast.success("User is now an admin");
    } else {
        isUserAdmin.value = false;
        toast.warning("User is no longer an admin");
    }

    await loadUserAdmin();
};
</script>
