<template>
  <div>
    <div class="content">
      <div class="columns is-vcentered">
        <div class="column is-8">
          <h1 class="is-pulled-left">Редактирование товара</h1>
        </div>
        <div class="column has-text-right">
          <button type="button" class="button is-warning">Сохранить</button>
          <button type="button" class="button">Вернуться</button>
        </div>
      </div>
    </div>
    <hr>
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
