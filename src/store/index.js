import Vuex from 'vuex'
import Vue from 'vue'
import products from './modules/products'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        products
    },
    strict: debug,
    devtools: debug
})
