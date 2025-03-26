// This store will offer util functions for the permissions passed through store/roles.js's props.
import { useRoleStore } from "@/stores/roles";

/*
    This function will check if the AUTHENTICATED user has the role passed as parameter.
*/
export const hasRole = async(check_role) => {
    const roleStore = useRoleStore();
    const roles = await roleStore.getUserRoles();
    const data = roles.data;

    return roles.data.roles.some(role => role === check_role);
};

/*
    This function checks if a user has a specific permission
*/
export const hasPermission = async (check_permission) => {
    const roleStore = useRoleStore();
    const permissions = await roleStore.getUserPermissions();
    const data = permissions.data;

    return data.permissions.some(permission => permission === check_permission);
};