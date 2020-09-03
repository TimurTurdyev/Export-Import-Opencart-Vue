import axios from "axios";
export default {
    async get(id) {
        try {
            const response = await axios.get(`index.php?route=bearded_export_import/productSingle&id=${id}&token=`);
            return response.data
        } catch (error) {
            console.error(error);
        }
    }
}