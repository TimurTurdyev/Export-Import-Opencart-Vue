import axios from 'axios'

export default {
    async get(page, limit = 20) {
        try {
            const response = await axios.get(`https://jsonplaceholder.typicode.com/posts?_limit=${limit}&_page=${page}`);
            return response.data
        } catch (error) {
            console.error(error);
        }
    }
}