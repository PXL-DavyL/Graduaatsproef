// This pinia store is responsible for all things related to blogs

import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";
import { getCsrfToken } from "./utils";

const loading = ref(false);

export const useReactionStore = defineStore("reactions", () => {


    const getReactionsAsAdmin = async(credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/admin/blog/reactions", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        }
        catch(error) {
            console.log('Get reactions failed:', error);
        }
        finally {
            loading.value = false;
        }
    }
    const getReactionAsAdmin = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/admin/blog/reaction", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Get reactions failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    const updateReactionAsAdmin = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.patch("http://localhost:8000/api/admin/blog/reaction", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Update reactions failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    const destroyReactionAsAdmin = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.delete("http://localhost:8000/api/admin/blog/reaction", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Delete reactions failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }


    return {
        getReactionsAsAdmin,
        getReactionAsAdmin,
        updateReactionAsAdmin,
        destroyReactionAsAdmin,
    }
});