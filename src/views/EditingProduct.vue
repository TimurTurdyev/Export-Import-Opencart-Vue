<template>
  <div>
    <div class="content">
      <div class="columns is-vcentered">
        <div class="column is-8">
          <h2 class="is-pulled-left">Редактирование товара</h2>
        </div>
        <div class="column">
          <div class="field is-grouped is-grouped-right">
            <p class="control">
              <a :href="openProductLink" target="_blank" class="button is-info">Открыть</a>
            </p>
            <p class="control">
              <button type="button" class="button is-warning" v-on:click.prevent="changeProductData">Сохранить</button>
            </p>
            <p class="control">
              <button type="button" class="button" v-on:click.prevent="$router.back()">Назад</button>
            </p>
          </div>
        </div>
      </div>
    </div>
    <Message
        :message="message"
        :isClass="isClass"
    ></Message>
    <Product/>
  </div>
</template>

<script>
// @ is an alias to /src
import Product from '@/components/Product.vue'
import Message from "@/components/Message";
import {mapActions} from "vuex";

export default {
  name: 'ProductEdit',
  components: {
    Product,
    Message
  },
  data() {
    return {
      message: '',
      isClass: '',
      openProductLink: ''
    }
  },
  methods: {
    ...mapActions({
      update: 'product/update'
    }),
    async changeProductData() {
      let message = await this.update();
      console.log(message)
      this.message = message.info
      this.isClass = message.isClass
    }
  },
  mounted() {
    const root = this.$store.state.settings
    this.openProductLink = root.base + 'index.php?route=catalog/product/edit&product_id=' +
        this.$route.params.id + root.token
  }
}
</script>
