<template>
    <div class="flex flex-col gap-2">
        <InputButton type="warning"
            @click="collapseDropdown = !collapseDropdown"
            class="w-full"
        >
            <span>{{ collapseDropdown ? 'Hide' : 'Show'  }} permissions</span>
            <IconChevron class="h-4 w-4 transition duration-300" 
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
                v-for="permission in permissions"
                :key="permission.id"
                class="rounded p-2 cursor-pointer"
                :class="
                {'bg-emerald-200 border border-emerald-300 hover:bg-emerald-300 hover:border-emerald-400 text-emerald-800': doesUserHavePermission(permission)},
                {'bg-rose-200 border border-rose-300 hover:bg-rose-300 hover:border-rose-400 text-rose-800': !doesUserHavePermission(permission)}
                
                
                "
                @click="togglePermission(permission)"
            >
                {{ permission.name }}
            </span>

            <span class="text-sm">Alternatively, you may choose to make this user an admin:</span>
                <div @click="makeAdmin" class="w-full">
                <span
                    class="block w-full rounded p-2 bg-amber-200 border border-amber-300 hover:bg-amber-300 hover:border-amber-400 text-amber-800 cursor-pointer"
                    v-if="doesUserHaveRole(usePage().props.user, 'admin') === true"
                >
                    Remove admin rights
                </span>
                <span v-else 
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
import { usePage, useForm } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import axios from "axios";
import IconChevron from "@/Components/icons/IconChevron.vue";
import { doesUserHaveRole } from "@/Composables/permissions";
import InputButton from "@/Components/InputButton.vue";

const collapseDropdown = ref(false);
const permissions = ref([]);
const userPermissions = usePage().props.user_permissions;

const form = useForm({
    permission: {},
});

onBeforeMount(async () => {
    await axios
        .get(route("admin.permissions"))
        .then((response) => {
            permissions.value = response.data.permissions;
        })
        .catch((error) => {
            console.error(error);
        });
});

const doesUserHavePermission = (permission) => {
    return userPermissions.includes(permission.name);
};

const togglePermission = (permission) => {
    form.permission = permission;

    const result = doesUserHavePermission(permission);

    form.post(
        route("admin.users.toggle_permission", {
            user: usePage().props.user.id,
            permission: permission.id,
        }),
        {
            onSuccess: () => {
                if (!result) {
                    toast.success("Permission granted successfully");
                    userPermissions.push(permission.name);
                } else {
                    toast.warning(`Permission revoked successfully`);
                    userPermissions.splice(
                        userPermissions.indexOf(permission.name),
                        1
                    );
                }
            },
            onError: (err) => {
                for (const error in err) {
                    toast.error(err[error]);
                }
            },
        }
    );
};

const makeAdmin = () => {
    const result = doesUserHaveRole(usePage().props.user, "admin");

    form.post(
        route("admin.users.toggle_admin", usePage().props.user.id),
        {
            onSuccess: () => {
                if(!result) {
                    toast.success("User is now an admin");
                } else {
                    toast.success("User is no longer an admin");
                }
            },
            onError: (err) => {
                for (const error in err) {
                    toast.error(err[error]);
                }
            },
        }
    );
}
</script>
