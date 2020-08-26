<template>
  <div>
    <div class="content">
      <div class="columns is-vcentered">
        <div class="column is-8">
          <h1 class="is-pulled-left">Экспорт товаров</h1>
        </div>
        <div class="column has-text-right">
          <button type="button" class="button is-warning">Export</button>
        </div>
      </div>
    </div>
    <Products/>
  </div>
</template>

<script>
// @ is an alias to /src
import Products from '@/components/Products.vue'

export default {
  name: 'Export',
  components: {
    Products
  },
  mounted() {
    console.log(this.$store)
  }
}
</script>
