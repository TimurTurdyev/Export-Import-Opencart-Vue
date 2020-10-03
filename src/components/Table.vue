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
          <div class="field">
            <div class="control is-small">
              <textarea class="textarea is-small"
                        v-on:input="change(index, key)"
                        v-text="value"></textarea>
            </div>
          </div>
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
    tableBody: Object,
    onChange: Function
  },
  data() {
    return {
      tableBodyData: [],
      pagination: {
        current: 1,
        total: 0,
        itemsPerPage: 5,
      }
    }
  },
  methods: {
    change(index, key) {
      this.onChange(index, key, event.target.value)
    }
  }
}
</script>