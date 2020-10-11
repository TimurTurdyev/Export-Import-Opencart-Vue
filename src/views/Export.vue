<template>
  <div>
    <div class="content">
      <div class="columns is-vcentered">
        <div class="column is-8">
          <h1 class="is-pulled-left">Экспорт товаров</h1>
        </div>
        <div class="column has-text-right">
          <div class="field is-grouped is-grouped-right">
            <p class="control">
              <button type="button" class="button is-success" v-on:click.prevent="saveProducts"><font-awesome-icon
                  icon="save" /></button>
            </p>
            <p class="control">
              <button type="button" class="button is-warning" v-on:click.prevent="exportProducts"><font-awesome-icon
                  icon="file-download" /></button>
            </p>
          </div>
        </div>
      </div>
      <article v-if="download_file_path" class="message is-small is-success">
        <div class="message-body">
          <button class="delete is-pulled-right" aria-label="delete"
                  v-on:click.prevent="download_file_path = ''"></button>
          <div>Файл успешно сформирован! Скачать можно <a :href="download_file_path"><strong>по
            ссылке</strong></a>.
          </div>
        </div>
      </article>

      <article v-if="message" class="message is-small is-success">
        <div class="message-body">
          <button class="delete is-pulled-right" aria-label="delete"
                  v-on:click.prevent="message = ''"></button>
          <div>{{message}}
          </div>
        </div>
      </article>

    </div>
    <Products/>
  </div>
</template>

<script>
// @ is an alias to /src
import Products from '@/components/Products.vue'
import axios from "axios";
// import FileDownload from "js-file-download"

export default {
  name: 'Export',
  components: {
    Products
  },
  data() {
    return {
      message: '',
      download_file_path: ''
    }
  },
  methods: {
    saveProducts() {
      const root = this.$store.state.settings
      const products = this.$store.getters['products/products']

      axios.post(`${root.base}index.php?route=beardedcode/product/prices${root.token}`, products)
          .then((response) => {
            console.log(response.data)
            if (response.data['success']) {
              this.message = response.data['success']
            }
          })
          .catch((reject) => {
            console.log(reject)
          })
    },
    exportProducts() {
      const root = this.$store.state.settings
      const products = this.$store.getters['products/checks']

      axios.post(`${root.base}index.php?route=beardedcode/export${root.token}`, products)
          .then((response) => {
            console.log(response.data)
            if (response.data['download_file_path']) {
              this.download_file_path = response.data['download_file_path']
            }
            // FileDownload(response.data, 'report.csv');
          })
          .catch((reject) => {
            console.log(reject)
          })
    }
  },
  mounted() {
    console.log(this.$store)
  }
}
</script>
