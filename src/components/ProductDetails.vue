<template>
  <tr>
      <td colspan="6">{{product}}</td>
  </tr>
</template>

<script>
export default {
  name: 'ProductDetails',
  props: {
    product: Object,
  }
}
</script>