import Vuex from 'vuex'
import Vue from 'vue'
import products from './modules/products'
import product from './modules/product'
import settings from "./modules/settings";

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        products,
        product,
        settings
    },
    strict: debug,
    devtools: debug
})
