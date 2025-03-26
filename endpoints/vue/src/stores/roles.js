// This pinia store is responsible for handling user roles and permissions.


import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";
import { getCsrfToken } from "./utils";

const loading = ref(false);

export const useRoleStore = defineStore("roles-and-permissions", () => {

    const getUserPermissions = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/user-permissions", credentials, {
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

    const getUserRoles = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/user-roles", credentials, {
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
    }

    return {
        getUserPermissions,
        getUserRoles,
    }
});