import axios from "axios";
// initial state
const state = () => ({
    product: {}
})

// getters
const getters = {
    product: state => state.product,
}

// actions
const actions = {
    async product({commit}, payload) {
        const root = this.state.settings
        const result = await axios.get(`${root.base}index.php?route=beardedcode/product/single${root.token}&id=${payload}`)
            .then((resolve) => {resolve.data}).catch((reject) => console.log(reject));
        commit('product', result)
    },
}

// mutations
const mutations = {
    product(state, product) {
        state.product = product
    },
    editing(state, product) {
        state.product = product
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}