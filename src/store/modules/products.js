import axios from "axios";

const state = () => ({
    products: [],
    count: 0,
    edit: {},
    checks: [],
    pagination: {
        current: 1,       // Current page
        total: 0,       // Items total count
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
        const root = this.state.settings
        console.log(payload)
        let filter = '';
        for (const key in payload) {
            filter += `&filter[${key}]=${payload[key]}`
        }
        const response = async () => {
            const response = await axios.get(`${root.base}index.php?route=beardedcode/product/list${root.token}${filter}`)
            return response.data;
        };

        const result = await response()

        commit('products', result.products)

        commit('pagination', {
            page: Number(payload.page ?? 1),
            total: Number(result.total),
            itemsPerPage: payload.limit
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
        state.pagination.itemsPerPage = pagination.itemsPerPage
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