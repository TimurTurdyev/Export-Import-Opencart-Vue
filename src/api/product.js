import axios from "axios";


export default {
    async get(id) {
        try {
            const response = await axios.get(`https://jsonplaceholder.typicode.com/posts/${id}`);
            return response.data
        } catch (error) {
            console.error(error);
        }
    }
}