// This pinia store is responsible for all things related to blogs

import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";
import { getCsrfToken } from "./utils";

const loading = ref(false);

export const useBlogStore = defineStore("blogs", () => {
    const getBlogs = async (credentials) => {
        loading.value = true;
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/blog/index", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Get all blogs failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    const getBlog = async (credentials) => {
        try {
            await getCsrfToken();
            const response = await axios.get("http://localhost:8000/api/blog/show", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Get specific blog failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }


    const editBlog = async (credentials) => {
        try {
            await getCsrfToken();
            const response = await axios.patch("http://localhost:8000/api/blog/edit", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Update blog failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    const editBlogAsAdmin = async (credentials) => {
        try {
            await getCsrfToken();
            const response = await axios.patch("http://localhost:8000/api/admin/blog", credentials, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Update blog failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }


    const deleteBlogAsAdmin = async (credentials) => {
        try {
            await getCsrfToken();
            const response = await axios.delete("http://localhost:8000/api/admin/blog", {
                params: credentials,
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                withCredentials: true,
            });
            return response;
        } catch (error) {
            console.error("Update blog failed:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    return {
        getBlogs,
        getBlog,
        editBlog,
        editBlogAsAdmin,
        deleteBlogAsAdmin,
    };
});