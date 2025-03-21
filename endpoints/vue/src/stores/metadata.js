import axios from "axios";
export const getMetaData = async () => {
	try {
        const response = await axios.get("http://localhost:8000/metadata", {
            withCredentials: true,
            headers: {
                'Accept': 'application/json'
            }
        });
		return response;
	} catch (error) {
		console.error("Metadata failed:", error);
		throw error;
	}
};
