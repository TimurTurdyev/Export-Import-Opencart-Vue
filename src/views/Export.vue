<template>
  <div>
    <div class="content">
      <h1>Экспорт товаров</h1>
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
