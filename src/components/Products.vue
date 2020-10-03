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
          <table class="table is-hoverable" style="width: 100%;">
            <thead>
            <tr>
              <th>
                <label class="checkbox">
                  <input type="checkbox" v-model="check" v-on:change="checkAll(check)" :value="1">
                  Id
                </label>
              </th>
              <th>Image</th>
              <th>Title</th>
              <th>Sku</th>
              <th>Price</th>
              <th>Status</th>
              <th class="has-text-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(product) in products" :key="product.id">
              <td>
                <label class="checkbox">
                  <input type="checkbox" v-model="checks" v-on:change="updateChecks(checks)" :value="product.id">
                  {{ product.id }}
                </label>
              </td>
              <td><img :src="product.image"
                       class="image is-48x48"></td>
              <td>{{ product.title }}</td>
              <td>{{ product.sku }}</td>
              <td>{{ product.price }}</td>
              <td>
                <span class="tag is-success" v-if="product.status == 1">ON</span>
                <span class="tag is-danger" v-else>OFF</span>
              </td>
              <td>
                <div class="field is-grouped is-grouped-right">
                  <p class="control">
                    <a :href="generateProductLink(product.id)" target="_blank" class="button is-info">Open</a>
                  </p>
                  <router-link :to="'/product/' + product.id" class="button is-warning">Info</router-link>
                </div>
              </td>
            </tr>
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
import {mapActions, mapGetters} from 'vuex'

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
</style>