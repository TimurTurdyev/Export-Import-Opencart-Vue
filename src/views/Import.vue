<template>
  <div class="content">
    <div class="columns is-vcentered">
      <div class="column is-8">
        <h1 class="is-pulled-left">Импорт товаров</h1>
      </div>
      <div class="column buttons has-text-right">
        <button type="button" class="button is-success" :disabled="!load" v-on:click.prevent="loadData">Load</button>
        <button type="button" class="button is-warning" v-on:click.prevent="submitFile()">Sent</button>
      </div>
    </div>
    <div class="card">
      <div class="card-content">
        <div class="field">
          <div class="file is-info has-name">
            <label class="file-label">
              <input class="file-input" type="file" v-on:change="handleFileUpload()">
              <span class="file-cta">
            <span class="file-icon">
              <i class="fas fa-upload"></i>
            </span>
            <span class="file-label">
              File xlsx…
            </span>
          </span>
              <span class="file-name" v-if="file_name">{{ file_name }}</span>
              <span class="file-name" v-else>Файл не загружен</span>
            </label>
          </div>
        </div>
        <Message
            :message="message"
            :isClass="isClass"
        ></Message>
        <Table
            :tableHead="tableHead"
            :tableBody="tableBody"
            :onChange="onChange"
        ></Table>
        <Cooperate></Cooperate>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Message from "@/components/Message";
import Table from "@/components/Table";
import Cooperate from '@/components/Cooperate'

export default {
  name: 'Import',
  components: {Table, Message, Cooperate},
  data() {
    return {
      file: '',
      file_name: '',
      load: false,
      message: '',
      isClass: '',
      tableHead: [],
      tableBody: []
    }
  },
  methods: {
    onChange(index, key, value) {
      // console.log(index, key, value)
      this.tableBody[index][key] = value;
    },
    loadData() {
      const root = this.$store.state.settings
      const data = []
      for(let i = 2; i < 50; i++) {
        const value = {}
        const row = this.tableBody[i];
        if (!row) break;
        for (const key in row) {
          value[this.tableHead[key]] = row[key];
        }

        data.push(value)
      }
      axios.post(`${root.base}index.php?route=beardedcode/import/load${root.token}`,
          {
            productsData: data
          }
      ).then((response) => {
        console.log(response)

      }).catch(function () {
        console.log('FAILURE!!');
      });
    },
    submitFile() {
      const root = this.$store.state.settings
      let formData = new FormData();
      formData.append('file', this.file);
      axios.post(`${root.base}index.php?route=beardedcode/import${root.token}`,
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
      ).then((response) => {
        console.log(response)
        this.message = response.data['success']
        this.isClass = 'success'
        if (response.data['code']) {
          const getData = async () => {
            const responseCode = await
                axios.get(`${root.base}index.php?route=beardedcode/import/getUpload${root.token}&code=${response.data['code']}`)
            return responseCode.data;
          };

          getData().then((resolve) => {
            console.log(resolve)
            this.tableHead = resolve['head']
            this.tableBody = resolve['data']
            this.load = true
          })
        }
      }).catch(function () {
        console.log('FAILURE!!');
      });
    },
    handleFileUpload() {
      console.log(event)
      this.file = event.target.files[0]
      this.file_name = event.target.files[0].name
    }
  }
}
</script>