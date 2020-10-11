let location_base = ''
let token = ''
if (process.env.NODE_ENV !== 'production') {
    location_base = '//www.lpack-spb.ru/admin/'
    token = '&token=LZTfNBB0UmSIy0TxA6g76xTknI1WGZeq'
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