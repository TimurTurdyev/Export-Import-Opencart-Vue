import axios from "axios";
// initial state
const state = () => ({
    categories: [],
    discount: ''
})
const getters = {
    categories: state => state.categories,
    discount: state => state.discount
}
// actions
const actions = {
    async loadCategories({commit}) {
        const root = this.state.settings
        const response = async () => {
            const response = await axios.get(`${root.base}index.php?route=beardedcode/product/categories${root.token}`)
            return response.data;
        };

        const result = await response()
        commit('setCategories', result)
    },
    async loadDiscount({commit}, payload) {
        const root = this.state.settings
        const response = async () => {
            const response = await axios.get(`${root.base}index.php?route=beardedcode/product/discount${root.token}&product_id=${payload}`)
            return response.data;
        };

        const result = await response()
        commit('setDiscount', result)
    },
}

// mutations
const mutations = {
    setCategories(state, data) {
        state.categories = data['categories']
    },
    setDiscount(state, data) {
        state.categories = data['discount']
    },
}

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}