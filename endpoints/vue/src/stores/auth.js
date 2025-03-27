// This pinia store is responsible for handling user authentication.
import { defineStore } from "pinia";
import { ref } from "vue";
import { getCsrfToken } from "./utils";
import { toast } from "vue3-toastify";
import axios from "axios";

const loading = ref(false);


export const useAuthStore = defineStore("auth", () => {

	const getUserData = async() => {

		if(localStorage.getItem("user_unix") === null) {
			if(localStorage.getItem("user") !== null) {
				localStorage.removeItem("user");
				localStorage.removeItem("user_unix");
				toast.error("Session expired. Please log in again.");
				window.location.href = "/login";
				return;
			}
			// fix: infinite /login spam, if they're not logged in just return null
			return null;
		}

		if (localStorage.getItem("user_unix") < Math.floor(Date.now() / 1000) - 7200) { // 120 minutes - config/session.php
			localStorage.removeItem("user");
			localStorage.removeItem("user_unix");
			toast.error("Session expired. Please log in again.");
			window.location.href = "/login";
			return null;
		}

		return JSON.parse(localStorage.getItem("user"));
	};
	const isAuthenticated = () => {
		return JSON.parse(localStorage.getItem("user")) !== null;
	};

	const register = async (credentials) => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.post("http://localhost:8000/api/register", credentials, {
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});
			return response;
		} catch (error) {
			console.error("Registration failed:", error);
			throw error;
		} finally {
			loading.value = false;
		}
	};

	const login = async (credentials) => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.post("http://localhost:8000/api/login", credentials, {
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});

			localStorage.setItem("user_unix", Math.floor(Date.now() / 1000)); // get unix timestamp in seconds
			localStorage.setItem("user", JSON.stringify(response.data.user));
			return response;
		} catch (error) {
			console.error("Login failed:", error);
			throw error;
		} finally {
			loading.value = false;
		}
	};

	const forgot_pass = async (credentials) => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.post("http://localhost:8000/api/forgot_pass", credentials, {
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});

			return response;
		} catch (error) {
			console.error("Forgot password failed:", error);
			throw error;
		} finally {
			loading.value = false;
		}
	};

	const fetchUser = async () => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.get("http://localhost:8000/api/user", {
				headers: {
					Accept: "application/json",
					"X-CSRF-TOKEN": document
						.querySelector('meta[name="csrf-token"]')
						?.getAttribute("content"),
				},
				withCredentials: true,
			});
			return response;
		} catch (error) {
			delete axios.defaults.headers.common["Authorization"];
			localStorage.removeItem("user");
			localStorage.removeItem("user_unix");
			console.error("Failed to fetch user:", error);
			throw error;
		} finally {
			loading.value = false;
		}
	};

	const logout = async () => {
		loading.value = true;
		try {
			await getCsrfToken();
			const response = await axios.post("http://localhost:8000/api/logout", {
				headers: {
					Accept: "application/json",
					"X-CSRF-TOKEN": document
						.querySelector('meta[name="csrf-token"]')
						?.getAttribute("content"),
				},
				withCredentials: true,
			});

			return response;
		} catch (error) {
			console.error("Logout failed:", error);
			throw error;
		} finally {

			delete axios.defaults.headers.common["Authorization"];
			localStorage.removeItem("user");
			localStorage.removeItem("user_unix");
			loading.value = false;
		}
	};

	return {
		getUserData,
		isAuthenticated,
		register,
		login,
		forgot_pass,
		fetchUser,
		logout,
		loading,
	};
});
