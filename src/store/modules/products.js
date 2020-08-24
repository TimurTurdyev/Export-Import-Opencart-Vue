import products from '@/api/products'

// initial state
const state = () => ({
    products: [],
    count: 0,
    edit: {},
    checks: [],
    pagination: {
        current: 1,       // Current page
        total: 100,       // Items total count
        itemsPerPage: 5   // Items per page
    }
})

// getters
const getters = {
    products: state => state.products,
    count: state => state.count,
    edit: state => state.edit,
    pagination: state => state.pagination,
    checks: state => state.checks
}


// actions
const actions = {
    async products({commit}, payload) {
        console.log(payload)
        const result = await products.get(payload ?? 1)
        commit('products', result)
        console.log(payload ?? 1)
        commit('pagination', {
            page: Number(payload ?? 1),
            total: Number(result.length)
        })
    },
    checks({commit}, payload) {
        commit('updateChecks', payload)
    },
    edit({commit}, payload) {
        console.log(commit, payload)
    }
}

// mutations
const mutations = {
    products(state, products) {
        state.products = products
    },
    pagination(state, pagination) {
        state.pagination.current = pagination.page
        state.pagination.total = pagination.total
    },
    edit(state, product) {
        console.log(product)
        state.details[product.id] = product
    },
    updateChecks(state, checks) {
        state.checks = checks
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}