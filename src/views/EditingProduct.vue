<template>
  <div>
    <div class="content">
      <div class="columns is-vcentered">
        <div class="column is-8">
          <h1 class="is-pulled-left">Редактирование товара</h1>
        </div>
        <div class="column">
          <div class="field is-grouped is-grouped-right">
            <p class="control">
              <button type="button" class="button is-warning">Сохранить</button>
            </p>
            <p class="control">
              <button type="button" class="button" v-on:click.prevent="$router.back()">Вернуться</button>
            </p>
          </div>
        </div>
      </div>
    </div>
    <Product/>
  </div>
</template>

<script>
// @ is an alias to /src
import Product from '@/components/Product.vue'

export default {
  name: 'ProductEdit',
  components: {
    Product
  }
}
</script>
