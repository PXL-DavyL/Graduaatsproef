import axios from "axios";
export const getMetaData = async () => {
	try {
        const response = await axios.get("http://localhost:8000/metadata", {
            withCredentials: true,
            headers: {
                'Accept': 'application/json'
            }
        });
        
		console.log(response);
		return response;
	} catch (error) {
		console.error("Metadata failed:", error);
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
