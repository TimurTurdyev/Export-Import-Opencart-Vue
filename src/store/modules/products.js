import axios from "axios";

const state = () => ({
    products: [],
    count: 0,
    edit: {},
    checks: [],
    checkNames: [],
    checkCategories: [],
    checkStatus: '',
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
    checks: state => state.checks,
    checkNames: state => state.checkNames,
    checkCategories: state => state.checkCategories,
    checkStatus: state => state.checkStatus,
}

// actions
const actions = {
    async products({commit, state}, payload) {
        const root = this.state.settings

        let filter = '';

        for (const key in payload) {
            filter += `&filter[${key}]=${payload[key]}`
        }

        if (state.checkNames) {
            state.checkNames.forEach((name) => {
                filter += `&filter[names][]=${decodeURIComponent(name)}`
            })
        }

        if (state.checkCategories) {
            state.checkCategories.forEach((category_id) => {
                filter += `&filter[categories][]=${category_id}`
            })
        }

        filter += `&filter[status]=${state.checkStatus}`

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
    checksNames({commit}, payload) {
        commit('updateCheckNames', payload)
    },
    checksCategories({commit}, payload) {
        commit('updateCheckCategories', payload)
    },
    checksStatus({commit}, payload) {
        commit('updateStatus', payload)
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
    },
    updateCheckNames(state, checks) {
        state.checkNames = checks
    },
    updateCheckCategories(state, checks) {
        state.checkCategories = checks
    },
    updateStatus(state, checks) {
        state.checkStatus = checks
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}