import Vue from 'vue'
import VueRouter from 'vue-router'
import Export from '@/views/Export.vue'
import EditingProduct from "@/views/EditingProduct";
import 'bulma/bulma.sass'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Export',
        component: Export
    },
    {
        path: '/page/:id',
        name: 'Export',
        component: Export,
    },
    {
        path: '/product/:id',
        name: 'EditingProduct',
        component: EditingProduct,
        props: true
    },
    {
        path: '/import',
        name: 'Import',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/Import.vue')
    }
]

const router = new VueRouter({
    routes,
    mode: 'hash'
})

export default router
