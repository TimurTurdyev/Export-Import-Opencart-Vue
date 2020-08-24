/**
 * Mocking client-server processing
 */
const _products = {"id": 1, "title": "iPad 4 Mini", "price": 500.01, "inventory": 2,}


export default {
    getDetail() {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // переведёт промис в состояние fulfilled с результатом "result"
                resolve(_products);
                console.log(reject)
            }, 1000);
        });
    },
}