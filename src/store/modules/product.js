import product from '@/api/product'
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
        console.log(payload)
        const result = await product.get(payload)
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