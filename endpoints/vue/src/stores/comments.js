// This pinia store is responsible for all things related to blogs

import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";
import { getCsrfToken } from "./utils";

const loading = ref(false);

export const useCommentStore = defineStore("comments", () => {
    const editComment = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.patch("http://localhost:8000/api/blog/comment", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Edit comment failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    const destroyComment = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.delete("http://localhost:8000/api/blog/comment", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Destroy comment failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    const addComment = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.post("http://localhost:8000/api/blog/comment", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Add comment failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    const getCommentsAsAdmin = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/admin/blogs/comments", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Get comments failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    const getCommentAsAdmin = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/admin/blogs/comment", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Get comments failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    const updateCommentAsAdmin = async (credentials) => {

        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.patch("http://localhost:8000/api/admin/blogs/comment", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Get comments failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }


    const destroyCommentAsAdmin = async (credentials) => {

        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.delete("http://localhost:8000/api/admin/blogs/comment", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Get comments failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }


    return {
        addComment,
        editComment,
        destroyComment,

        getCommentsAsAdmin,
        getCommentAsAdmin,
        updateCommentAsAdmin,
        destroyCommentAsAdmin,
    }
});