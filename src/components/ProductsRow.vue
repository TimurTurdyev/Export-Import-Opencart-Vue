<template>
  <div>

  </div>
</template>

<script>
export default {
  name: 'ProductsRow',
  props: {
    product: Object,
    checks: {
      type: Array,
    },
    updateChecks: {
      type: Function
    },
    toggleProduct: {
      type: Function
    }
  }
}
</script>