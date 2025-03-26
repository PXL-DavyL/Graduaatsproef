// This pinia store is responsible for handling user roles and permissions.


import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";
import { getCsrfToken } from "./utils";

const loading = ref(false);

export const useRoleStore = defineStore("roles-and-permissions", () => {

    const getAuthPermissions = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/auth-permissions", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Permission fetch failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };
    const getAuthRoles = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/auth-roles", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Role fetch failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };


    const getUserRoles = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/user-roles", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Role fetch failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    const getUserPermissions = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/user-permissions", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Permission fetch failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    const saveUserPermissions = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.post("http://localhost:8000/api/admin/user/set-permission", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Permission update failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    const setUserAdmin = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.post("http://localhost:8000/api/admin/user/toggle-admin", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        }
        catch (error) {

            console.error("Admin role update failed:", error);
            throw error;
        }
        finally {
            loading.value = false;
        }
    };

    return {
        getAuthPermissions,
        getAuthRoles,
        saveUserPermissions,
        setUserAdmin,
        getUserPermissions,
        getUserRoles,
    }
});