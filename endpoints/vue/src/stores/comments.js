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
            const response = await axios.patch("http://localhost:8000/api/comment/update", credentials, {
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

    return {
        editComment,
    }
});