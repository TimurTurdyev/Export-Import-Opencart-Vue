<template>
  <div class="table-container">
    <table class="table is-hoverable" style="width: 100%;">
      <thead>
      <tr>
        <th v-for="(key, index) in tableHead" :key="index">
          [{{index}}]{{key.charAt(0).toUpperCase() + key.slice(1)}}
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(row, index) in tableBody" :key="index">
        <td v-for="(value, key) in row" :key="key">
          {{value}}
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'Table',
  props: {
    tableHead: Object,
    tableBody: Object
  },
  mounted() {
    console.log(1111)
  },
}
</script>