UserDadata({
    type: 'fio', delay: 777, onSelected: function (suggest, helper) {
        reloadAll();
    }, queue: [{
        awaiting: "[name='register[firstname]']", prefetch: function (target) {
            return {query: '', parts: ['NAME', 'PATRONYMIC']}
        }, callback: function (suggest, helper, element) {
            return helper('name patronymic', suggest);
        }
    }]
});
UserDadata({
    type: 'fio', delay: 777, onSelected: function (suggest, helper) {
        reloadAll();
    }, queue: [{
        awaiting: "[name='register[lastname]']", prefetch: function (target) {
            return {query: '', parts: ['SURNAME']}
        }, callback: function (suggest, helper, element) {
            return helper('surname', suggest);
        }
    }]
});
UserDadata({
    type: 'email', delay: 777, onSelected: function (suggest, helper) {
        reloadAll();
    }, queue: [{
        awaiting: "[name='register[email]']", callback: function (suggest, helper, element) {
            return helper('value', suggest);
        }
    }]
});
UserDadata({
    type: 'address', delay: 777, element: function (helper) {
        return {
            before: '#simpleregister .row-register_field24',
            newElement: helper.create({
                tag: 'div',
                props: {className: 'form-group'},
                child: '<label class="col-sm-2 control-label" for="input-address-1">Поиск адреса и' +
                    ' автозаполнение</label><div class="col-sm-10"><input type="text" class="form-control' +
                    ' suggestions-input" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"><div class="suggestions-wrap"><ul class="js_suggestions"></ul></div></div>'
            })
        }
    }, onSelected: function (suggest, helper) {
        reloadAll();
    }, queue: [{
        awaiting: "[name='register[zone_id]']", callback: function (suggest, helper, element) {
            return helper('region', suggest);
        }
    }, {
        awaiting: "[name='register[city]']", clear: true, prefetch: function (target) {
            return {
                locations: [JSON.parse(target.getAttribute('data-dadata'))],
                from_bound: {'value': 'city'},
                to_bound: {'value': 'settlement'},
                restrict_value: true,
                query: ''
            }
        }, callback: function (suggest, helper, element) {
            let res = (helper('city_fias_id', suggest, true) || helper('area_fias_id', suggest, true) || helper('region_fias_id', suggest, true));
            element.setAttribute('data-dadata', res);
            return helper('city, settlement', suggest);
        }
    }, {
        awaiting: "[name='register[field24]']", prefetch: function (target) {
            return {
                'locations': [JSON.parse(target.getAttribute('data-dadata'))],
                'from_bound': {'value': 'street',},
                'to_bound': {'value': 'flat',},
                'restrict_value': true,
                'query': ''
            }
        }, callback: function (suggest, helper, element) {
            let res = (helper('settlement_fias_id', suggest, true) || helper('city_fias_id', suggest, true));
            element.setAttribute('data-dadata', res);
            return helper('street_with_type', suggest);
        }
    }, {
        awaiting: "[name='register[field22]']", callback: function (suggest, helper, element) {
            return helper('house_type.house, block_type-block', suggest);
        }
    }, {
        awaiting: "[name='register[field23]']", callback: function (suggest, helper, element) {
            return helper('flat_type.flat', suggest);
        }
    }, {
        awaiting: "[name='register[postcode]']", callback: function (suggest, helper, element) {
            return helper('postal_code', suggest);
        }
    }]
});