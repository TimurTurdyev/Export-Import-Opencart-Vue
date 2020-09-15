<template>
  <div class="card">
    <div class="card-content">
      <div class="columns">
        <div class="column is-8">
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
        </div>
        <div class="column">
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
      </div>
      <Cooperate></Cooperate>
    </div>
  </div>
</template>

<script>
import {mapActions} from 'vuex'
import {createHelpers} from 'vuex-map-fields';
import Cooperate from './Cooperate'
// `fooModule` is the name of the Vuex module.
const {mapFields} = createHelpers({
  getterType: 'product/getField',
  mutationType: 'product/updateField',
});

export default {
  components: {Cooperate},
  computed: {
    ...mapFields([
      'name',
      'meta_h1',
      'meta_title',
      'meta_description',
      'description',
      'model',
      'price',
      'cost',
      'quantity',
      'status'
    ]),
  },
  methods: {
    ...mapActions({
      loadProduct: 'product/loadProduct',
    }),
  },
  mounted() {
    this.loadProduct(this.$route.params.id)
  }
}
</script>