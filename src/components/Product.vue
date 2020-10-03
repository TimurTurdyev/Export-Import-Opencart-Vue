<template>
  <div class="card">
    <div class="card-content">
      <div class="columns">
        <div class="column is-4">
          <div class="field">
            <label class="label">Name</label>
            <div class="control">
              <input class="input" type="text" placeholder="Text Name" v-model="name">
            </div>
          </div>
          <div class="field">
            <label class="label">Meta H1</label>
            <div class="control">
              <input class="input" type="text" placeholder="Text Meta H1" v-model="meta_h1">
            </div>
          </div>

          <div class="field">
            <label class="label">Meta Title</label>
            <div class="control">
              <input class="input" type="text" placeholder="Text Meta Title"
                     v-model="meta_title">
            </div>
          </div>

          <div class="field">
            <label class="label">Meta Description</label>
            <div class="control">
              <input class="input" type="text" placeholder="Text Meta Description"
                     v-model="meta_description">
            </div>
          </div>
          <div class="field">
            <label class="label">Body</label>
            <div class="control">
              <textarea class="textarea" placeholder="Body" v-model="description"></textarea>
            </div>
          </div>
        </div>
        <div class="column is-4">
          <div class="field">
            <label class="label">Model</label>
            <div class="control">
              <input class="input" type="text" placeholder="Product Model"
                     v-model="model">
            </div>
          </div>

          <div class="columns">
            <div class="column">
              <div class="field">
                <label class="label">Price</label>
                <div class="control">
                  <input class="input" type="text" placeholder="Product Price"
                         v-model="price">
                </div>
              </div>
            </div>
            <div class="column">
              <div class="field">
                <label class="label">Cost</label>
                <div class="control">
                  <input class="input" type="text" placeholder="Product Cost"
                         v-model="cost">
                </div>
              </div>
            </div>
          </div>

          <div class="field">
            <label class="label">Discount</label>
            <div class="control">
              <textarea class="textarea is-small" type="text" placeholder="Product Discount"
                        v-model="product_discount"></textarea>
            </div>
          </div>

          <div class="field">
            <label class="label">Quantity</label>
            <div class="control">
              <input class="input" type="text" placeholder="Product Quantity"
                     v-model="quantity">
            </div>
          </div>
          <div class="field">
            <label class="label">Status</label>
            <div class="select is-fullwidth">
              <select v-model="status">
                <option value="0">Off</option>
                <option value="1">On</option>
              </select>
            </div>
          </div>

        </div>
        <div class="column">
          <div class="table_scroll">
            <table class="table is-fullwidth is-scrollable">
              <thead>
              <tr>
                <th style="width: 2.5rem;">
                  #
                </th>
                <th style="width: 4rem;">Main</th>
                <th style="width: 4rem;">Id</th>
                <th>Name</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(category) in categories" :key="category.category_id">
                <td>
                    <label class="checkbox">
                      <input type="checkbox"
                             v-model="product_to_category"
                             :value="category.category_id">
                    </label>
                </td>
                <td>
                  <label class="radio">
                    <input type="radio"
                           name="main_category_id"
                           v-on:change="changeMainCategory(category.category_id)"
                           :checked="category.category_id == main_category_id"
                           :value="category.category_id">
                  </label>
                </td>
                <td style="width: 4rem;">{{ category.category_id }}</td>
                <td v-html="category.name"></td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <Cooperate></Cooperate>
    </div>
  </div>
</template>

<script>
import Cooperate from './Cooperate'
import {mapActions, mapGetters} from 'vuex'
import {createHelpers} from 'vuex-map-fields';

const {mapFields} = createHelpers({
  getterType: 'product/getField',
  mutationType: 'product/updateField',
});

export default {
  components: {Cooperate},
  props: {
    id: String
  },
  computed: {
    ...mapFields([
      'main_category_id',
      'product_description.name',
      'product_description.meta_h1',
      'product_description.meta_title',
      'product_description.meta_description',
      'product_description.description',
      'product.model',
      'product.sku',
      'product.price',
      'product.cost',
      'product.quantity',
      'product.status',
      'product_to_category',
      'product_discount',
    ]),
    ...mapGetters({
      categories: 'dataInfo/categories'
    })
  },
  methods: {
    ...mapActions({
      loadProduct: 'product/loadProduct',
      loadCategories: 'dataInfo/loadCategories',
    }),
    changeMainCategory(category_id) {
      let product_to_category = [category_id];
      this.product_to_category.forEach((item) => {
        if (Number(item) !== Number(category_id)) {
          product_to_category.push(item)
        }
      })
      this.main_category_id = category_id
      this.product_to_category = product_to_category
    }
  },
  mounted() {
    this.loadProduct(this.$route.params.id)
    this.loadCategories()
  }
}
</script>
<style scoped>
.table.is-scrollable {
  position: relative;
}

.table.is-scrollable tbody {
  overflow-y: scroll;
  width: auto;
  position: absolute;
  max-height: 20rem;
}
</style>