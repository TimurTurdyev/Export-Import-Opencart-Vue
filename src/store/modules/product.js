import axios from "axios";
import {getField, updateField} from 'vuex-map-fields';
// initial state
const state = () => ({
    product: {
        model: '',
        price: 0.00,
        cost: 0.00,
        cost_percentage: 0.00,
        quantity: 0,
        status: ''
    },
    product_description: {
        name: '',
        meta_h1: '',
        meta_title: '',
        meta_description: '',
        description: '',
    },
    product_to_category: [],
    product_option_value: [],
    product_discount: [],
})

// getters
const getters = {
    getField
}

// actions
const actions = {
    async loadProduct({commit}, payload) {
        const root = this.state.settings
        const response = async () => {
            const response = await axios.get(`${root.base}index.php?route=beardedcode/product/single${root.token}&id=${payload}`)
            return response.data;
        };

        const result = await response()
        commit('setFields', result)
    },
    async update({state}) {

        const root = this.state.settings
        const product = state
        const message = {
            info: '',
            isClass: ''
        }
        const result = await axios.post(`${root.base}index.php?route=beardedcode/product/update${root.token}`, product)
            .then((response) => {
                return response.data
                // FileDownload(response.data, 'report.csv');
            })
            .catch((reject) => {
                console.log(reject)
            })
        message['info'] = result['success']
        message['isClass'] = 'success'
        return message
    }
}

// mutations
const mutations = {
    setFields(state, product) {
        for (const key in product) {
            state[key] = product[key]
        }
    },
    updateField
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}