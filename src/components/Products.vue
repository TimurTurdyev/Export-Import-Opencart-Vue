<template>
  <div>
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
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="product in products" :key="product.id">
        <td>
          <label class="checkbox">
            <input type="checkbox" v-model="checks" v-on:change="updateChecks(checks)" :value="product.id">
            {{ product.id }}
          </label>
        </td>
        <td><img src="https://i.picsum.photos/id/83/200/200.jpg?hmac=PWpSDFTveI1bSJjmrf_vnw4ipqEELicSPpDf8jb89FI"
        class="image is-48x48"></td>
        <td>{{ product.title }}</td>
        <td>00kn</td>
        <td>500</td>
        <td>
          <div class="buttons">
            <button class="button is-info">Info</button>
            <router-link :to="'product/' +  product.id" class="button is-success">Edit
            </router-link>
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
  </div>
</template>

<script>
import Pagination from 'vue-2-bulma-pagination'
import {mapActions, mapGetters} from 'vuex'

export default {
  components: {Pagination},
  data() {
    return {
      checks: [],
      check: 0
    }
  },
  computed: {
    ...mapGetters({
      products: 'products/products',
      pagination: 'products/pagination',
      selectedChecks: 'products/checks'
    }),
  },
  methods: {
    ...mapActions({
      loadProducts: 'products/products',
      updateChecks: 'products/checks',
    }),
    paginationOnChange(page) {
      this.loadProducts(page)
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
    this.checks = this.selectedChecks
    this.loadProducts()
  }
}
</script>