import axios from "axios";
import {getField, updateField} from 'vuex-map-fields';
// initial state
const state = () => ({
    name: '',
    meta_h1: '',
    meta_title: '',
    meta_description: '',
    description: '',
    model: '',
    price: '',
    cost: '',
    quantity: '',
    status: ''
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