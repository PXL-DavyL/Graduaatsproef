// This pinia store is responsible for handling profile customization.

import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";
import { getCsrfToken } from "./utils";

const loading = ref(false);

export const useProfileStore = defineStore("profile", () => {
    const getUserData = () => {
        return JSON.parse(localStorage.getItem("user"));
    };
    const isAuthenticated = () => {
        return JSON.parse(localStorage.getItem("user")) !== null;
    };

    // Update name and email
    const updateProfile = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.post("http://localhost:8000/api/update-profile", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Profile update failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    // Update password
    const updatePassword = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.post("http://localhost:8000/api/update-password", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Password update failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    // Delete account
    const deleteAccount = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.post("http://localhost:8000/api/delete-account", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            localStorage.removeItem("user");
            localStorage.removeItem("user_unix");
            window.location.reload();
            return response;
        } catch (error) {
            console.error("Account deletion failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    return {
        getUserData,
        isAuthenticated,
        updateProfile,
        updatePassword,
        deleteAccount,
        loading,
    };
});