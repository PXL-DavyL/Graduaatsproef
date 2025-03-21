import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axios from "axios";

export const useAuthStore = defineStore("auth", () => {
	const getUserData = () => {
		return JSON.parse(localStorage.getItem("user"));
	};
	const isAuthenticated = () => {
		return JSON.parse(localStorage.getItem("user")) !== null;
	};

	const register = async (credentials) => {

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
		}
	};

	const login = async (credentials) => {
		try {

			await getCsrfToken();
			const response = await axios.post("http://localhost:8000/api/login", credentials, {
				headers: {
					Accept: "application/json",
					"Content-Type": "application/json",
				},
				withCredentials: true,
			});

			localStorage.setItem("user", JSON.stringify(response.data.user));
			return response;
		} catch (error) {
			console.error("Login failed:", error);
			throw error;
		}
	};

	const fetchUser = async () => {
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

			console.error("Failed to fetch user:", error);
			throw error;
		}
	};

	const logout = async () => {
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

			delete axios.defaults.headers.common["Authorization"];

			localStorage.removeItem("user");
			return response;
		} catch (error) {
			console.error("Logout failed:", error);
			throw error;
		}
	};

	const getCsrfToken = async () => {
		try {
			const response = await axios.get("http://localhost:8000/api/csrf-token");
			axios.defaults.headers.common["X-CSRF-TOKEN"] = response.data.token;
			return response.data.token;
		} catch (error) {
			console.error("Failed to fetch CSRF token:", error);
			throw error;
		}
	};

	return {
		getUserData,
		isAuthenticated,

		register,
		login,
		fetchUser,
		logout,
	};
});
