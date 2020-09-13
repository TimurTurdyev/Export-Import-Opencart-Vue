let location_base = ''
let token = ''
if (process.env.NODE_ENV) {
    location_base = '//www.lpack-spb.ru/admin/'
    token = '&token=MxRb0wqpIJZIO3yzIX6stChQmmi4cLAo'
} else {
    location_base = location.origin + location.pathname + '?route=beardedcode/export'
    token = location.href.replace(/.+(&token=.+)?&.{0,}/gi, '$1')
}

// initial state
const state = () => ({
    base: location_base,
    token: token
})

export default {
    namespaced: true,
    state,
}