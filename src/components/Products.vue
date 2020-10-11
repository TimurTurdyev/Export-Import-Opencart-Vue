<template>
  <div class="columns">
    <div class="column is-3">
      <div class="card">
        <div class="card-content">
          <div class="is-size-4">Быстрые фильтры</div>
          <br>
          <span class="is-small">Filter to name</span>
          <div class="field">
            <div class="control">
              <input class="input is-small" type="text" placeholder="Write name and press [ENTER]"
                     v-on:change.prevent="writeNames">
            </div>
          </div>
          <div class="tags">
            <span class="tag is-success" v-for="(name, index) in check_names" :key="index">
            {{ name }}
            <button class="delete is-small" v-on:click.prevent="check_names.splice(index, 1)"></button>
          </span>
          </div>
          <hr>
          <span class="is-pulled-right is-small">[ctrl+click]</span>
          <span class="is-small">Filter to categories</span>
          <div class="select is-multiple is-small">
            <select multiple size="4" v-on:change="updateChecksCategories">
              <option v-for="(category, index) in categories" :key="index"
                      :value="category.category_id"
                      :selected="check_categories.indexOf(category.category_id) >= 0"
                      v-html="'[' + category.category_id + '] ' + category.name">
              </option>
            </select>
          </div>
          <hr>
          <div :class="{'is-hidden': !addFilterPercent}">
            <label>Процент Больше или равно:
              <span class="tag">{{ checkMinPercent }}</span></label>
            <input class="slider has-output"
                   v-model="checkMinPercent"
                   min="0"
                   max="100"
                   value="0"
                   step="1"
                   type="range" style="width: 100%;">
            <br>
            <label>Процент меньше или равно:
              <span class="tag">{{ checkMaxPercent }}</span></label>
            <input class="slider has-output"
                   v-model="checkMaxPercent"
                   min="0"
                   max="100"
                   value="100"
                   step="1"
                   type="range" style="width: 100%;">
          </div>
          <span class="is-small">Учитывать колонку процент <input type="checkbox" v-model="addFilterPercent"></span>

          <hr>
          <span class="is-small">Filter to status</span>
          <div class="control">
            <label class="radio">
              <input type="radio" name="filter_status" v-on:change="checkStatus" :checked="selectedChecks == ''"
                     value="">
              It doesn't matter!
            </label>
            <label class="radio">
              <input type="radio" name="filter_status" v-on:change="checkStatus" :checked="selectedChecks == '0'"
                     value="0">
              OFF
            </label>
            <label class="radio">
              <input type="radio" name="filter_status" v-on:change="checkStatus" :checked="selectedChecks == '1'"
                     value="1">
              ON
            </label>
          </div>
          <hr>
          <button type="button" class="button is-danger is-fullwidth" v-on:click.prevent="setFilters">Применить</button>
        </div>
      </div>
    </div>
    <div class="column">
      <div class="card">
        <div class="card-content">
          <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth" style="width: 100%;">
            <thead>
            <tr>
              <th width="80">
                <label class="checkbox">
                  <input type="checkbox" v-model="check" v-on:change="checkAll(check)" :value="1">
                  Id
                </label>
              </th>
              <th>Image</th>
              <th>Title</th>
              <th width="140">Sku</th>
              <th width="120">Price</th>
              <th width="120">Cost</th>
              <th width="120">Percent</th>
              <th>Status</th>
              <th class="has-text-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(product, index) in products">
              <tr :key="index">
                <td>
                  <label class="checkbox">
                    <input type="checkbox" v-model="checks" v-on:change="updateChecks(checks)" :value="product.id">
                    {{ product.id }}
                  </label>
                </td>
                <td>
                  <img :src="product.image" class="image is-48x48">
                </td>
                <td>{{ product.title }}</td>
                <td>
                  <div class="field">
                    <div class="control">
                      <input class="input" type="text" placeholder="Product Sku"
                             v-model="product.sku">
                    </div>
                  </div>
                </td>
                <td>
                  <div class="field">
                    <div class="control">
                      <input class="input" type="text" placeholder="Product Cost Percent"
                             v-model="product.price">
                    </div>
                  </div>
                </td>
                <td>
                  <div class="field">
                    <div class="control">
                      <input class="input" type="text" placeholder="Product Cost Percent"
                             v-on:input="changePrice(index)" v-model="product.cost">
                    </div>
                  </div>
                </td>
                <td>
                  <div class="field">
                    <div class="control">
                      <input class="input" type="text" placeholder="Product Cost Percent"
                             v-on:input="changePrice(index)" v-model="product.cost_percentage">
                    </div>
                  </div>
                </td>
                <td>
                  <label class="switch">
                    <input type="checkbox" v-on:change="changeProductStatus(product)" :checked="product.status == '1'">
                    <span class="slider_checkbox"></span>
                  </label>
                </td>
                <td>
                  <div class="field is-grouped is-grouped-right">
                    <p class="control">
                      <a :href="generateProductLink(product.id)" target="_blank" class="button is-info">
                        <font-awesome-icon icon="link"/>
                      </a>
                    </p>
                    <router-link :to="'/product/' + product.id" class="button is-warning">
                      <font-awesome-icon
                          icon="pen-square"/>
                    </router-link>
                  </div>
                </td>
              </tr>
              <tr :key="index + '_' + product.id" v-if="product.options.length" class="tr_details">
                <td colspan="3"></td>
                <td colspan="6">
                  <div class="options" v-if="product.options.length">
                    <div class="options-block" v-for="(option, gi) in product.options" :key="gi">
                      <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                        <tr>
                          <th width="40"><small>Id</small></th>
                          <th><small>{{ option.name }}</small></th>
                          <th width="60"><small>Prefix</small></th>
                          <th width="80"><small>Price</small></th>
                          <th width="80"><small>Cost</small></th>
                          <th width="80"><small>Quantity</small></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(values, vi) in option.values" :key="vi">
                          <td><small>{{ values.product_option_value_id }}</small></td>
                          <td><small>{{ values.name }}</small></td>
                          <td>
                            <div class="field">
                              <div class="control">
                                <input type="text" class="input is-small"
                                       v-on:input="changeOption('price_prefix', product.id, gi, vi)"
                                       :value="values.price_prefix">
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="field">
                              <div class="control">
                                <input type="text" class="input is-small"
                                       v-on:input="changeOption('price', product.id, gi, vi)"
                                       :value="values.price">
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="field">
                              <div class="control">
                                <input type="text" class="input is-small"
                                       v-on:input="changeOption('cost', product.id, gi, vi)"
                                       :value="values.cost">
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="field">
                              <div class="control">
                                <input type="text" class="input is-small"
                                       v-on:input="changeOption('quantity', product.id, gi, vi)"
                                       :value="values.quantity">
                              </div>
                            </div>
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </td>
              </tr>
            </template>
            </tbody>
          </table>

          <pagination
              :current="pagination.current"
              :total="pagination.total"
              :itemsPerPage="pagination.itemsPerPage"
              :onChange="paginationOnChange"
              :step="1">
          </pagination>
          <Cooperate></Cooperate>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import Pagination from 'vue-2-bulma-pagination'
import Cooperate from './Cooperate'
import {mapActions, mapGetters, mapMutations} from 'vuex'
import {createHelpers} from 'vuex-map-fields';

const {mapFields, mapMultiRowFields} = createHelpers({
  getterType: 'products/getField',
  mutationType: 'products/updateField',
});
export default {
  components: {Pagination, Cooperate},
  data() {
    return {
      checks: [],
      check: 0,
      openProductLink: '',
      check_names: [],
      check_categories: []
    }
  },
  computed: {
    ...mapGetters({
      products: 'products/products',
      pagination: 'products/pagination',
      selectedChecks: 'products/checks',
      selectedNames: 'products/checkNames',
      selectedChecksCategories: 'products/checkCategories',
      selectedStatus: 'dataInfo/checkStatus',
      categories: 'dataInfo/categories',
    }),
    ...mapFields([
      'checkMinPercent',
      'checkMaxPercent',
      'addFilterPercent',
    ]),
    ...mapMultiRowFields(['products'])
  },
  methods: {
    ...mapActions({
      loadProducts: 'products/products',
      updateChecks: 'products/checks',
      checksNames: 'products/checksNames',
      checksCategories: 'products/checksCategories',
      checksStatus: 'products/checksStatus',
      loadCategories: 'dataInfo/loadCategories',
    }),
    ...mapMutations({
      changeOptionValue: 'products/changeOptionValue'
    }),
    changeOption(name, productId, optionI, optionValI) {
      this.changeOptionValue({
        name: name,
        id: productId,
        option: optionI,
        optionVal: optionValI,
        value: event.target.value
      })
    },
    changeProductStatus(data) {
      console.log(data.status)
      data.status = data.status == '1' ? '0' : '1'
    },
    changePrice(id) {
      let cost = parseFloat(this.products[id].cost)
      let percent = parseFloat(this.products[id].cost_percentage)
      let price = cost + (cost / 100 * percent)
      this.products[id].price = (price || 0).toFixed(4)
    },
    checkStatus() {
      this.checksStatus(event.target.value)
    },
    writeNames() {
      const result = [...new Set(this.check_names)]
      const name = event.target.value
      if (name) {
        result.push(name)
        event.target.value = ''
      }
      this.check_names = result
    },
    setFilters() {
      this.checksNames(this.check_names)
      this.checksCategories(this.check_categories)
      this.loadProducts({
        page: 1,
        limit: 30
      })

      if (this.$route.params.id != 1) {
        this.$router.push({path: `/page/1`})
      }
    },
    updateChecksCategories() {
      const result = [...new Set(this.check_categories)]
      Array.prototype.slice.call(event.target.children).forEach((item) => {
        if (item.selected) {
          result.push(item.value)
        } else {
          if (result.indexOf(item.value) !== -1) {
            result.splice(result.indexOf(item.value), 1)
          }
        }
      })
      this.check_categories = result
    },
    generateProductLink(product_id) {
      return this.openProductLink.replace(/\{product_id\}/gi, product_id)
    },
    paginationOnChange(page) {
      this.$router.push({path: `/page/${page}`})
      this.loadProducts({
        page: page,
        limit: 30,
      })
      this.check = 0
    },
    checkAll(check) {
      const result = [...new Set(this.checks)]
      if (check) {
        this.products.forEach((product) => {
          if (result.indexOf(product.id) === -1) {
            result.push(product.id)
          }
        })
      } else {
        this.products.forEach((product) => {
          if (result.indexOf(product.id) !== -1) {
            result.splice(result.indexOf(product.id), 1)
          }
        })
      }
      this.checks = result
      this.updateChecks(this.checks)
    }
  },
  mounted() {
    const root = this.$store.state.settings
    this.openProductLink = root.base + 'index.php?route=catalog/product/edit' + root.token + '&product_id={product_id}'
    this.checks = this.selectedChecks
    this.check_names = this.selectedNames
    this.check_categories = this.selectedChecksCategories

    this.loadCategories()
    this.loadProducts({
      page: this.$route.params.id ?? 1,
      limit: 30
    })
  },
}
</script>
<style scoped>
.select select[multiple] option {
  white-space: normal;
  background: #eaeef1;
  border-bottom: .1rem solid #fff;
}

.select select[multiple] option:hover {
  background: aliceblue;
}

.select select[multiple] option:last-child {
  border-bottom: none;
}

/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  display: none;
}

/* The slider */
.slider_checkbox {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider_checkbox:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider_checkbox {
  background-color: #2196F3;
}

input:focus + .slider_checkbox {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider_checkbox:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

tr.tr_details > td {
  background: aliceblue;
  padding-bottom: .9rem !important;
}
</style>