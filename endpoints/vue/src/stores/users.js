// This pinia store is responsible for getting user data.
import { defineStore } from "pinia";
import { ref } from "vue";
import { getCsrfToken } from "./utils";
import axios from "axios";

const loading = ref(false);

export const useUserStore = defineStore("user", () => {
	const getAllUsers = async () => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.get("http://localhost:8000/api/admin/users", {
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});
			return response;
		} catch (error) {
			console.error("Failed to fetch user list:", error);
			throw error;
		} finally {
			loading.value = false;
		}
	};

	const getUser = async (credentials) => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.get("http://localhost:8000/api/admin/user", {
                params: credentials,
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});
			return response;
		} catch (error) {
			console.error("Failed to fetch user:", error);
			throw error;
		} finally {
			loading.value = false;
		}
	};

    const saveUser = async (credentials) => {
        loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.patch("http://localhost:8000/api/admin/user", credentials, {
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});
			return response;
		} catch (error) {
			console.error("Failed to update user:", error);
			throw error;
		} finally {
			loading.value = false;
		}
    };

	const deleteUser = async (credentials) => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.delete("http://localhost:8000/api/admin/user", {
				params: credentials,
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});
			return response;
		}
		catch (error) {
			console.error("Failed to delete user:", error);
			throw error;
		}
		finally {
			loading.value = false;
		}
	}

	const createUser = async (credentials) => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.post("http://localhost:8000/api/admin/user", credentials, {
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});
			return response;
		}
		catch (error) {
			console.error("Failed to create user:", error);
			throw error;
		}
		finally {
			loading.value = false;
		}
	}

    return {
        loading,
		getAllUsers,
        getUser,
        saveUser,
		deleteUser,
		createUser,
    }
});
