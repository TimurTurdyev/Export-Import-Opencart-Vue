let location_base = ''
let token = ''
if (process.env.NODE_ENV !== 'production') {
    location_base = '//www.lpack-spb.ru/admin/'
    token = '&token=AMijT9y6xC1t4CqajIHD2ihqvHc4EUfa'
} else {
    location_base = location.origin + '/admin/'
    token = location.href.replace(/.{0,}(&token=.*)/gi, '$1').replace(/#.+/gi, '')
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