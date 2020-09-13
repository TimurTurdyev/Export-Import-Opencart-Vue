<template>
  <article v-if="message" class="message is-small" :class="'is-' + isClass">
    <div class="message-body">
      <button class="delete is-pulled-right" aria-label="delete" v-on:click.prevent="removeMessage"></button>
      <div>{{ message }}</div>
    </div>
  </article>
</template>

<script>
export default {
  name: 'Message',
  props: {
    isClass: String,
    message: String
  },
  methods: {
    removeMessage() {
      console.log(this.$parent)
      this.$parent.message = ''
      this.$parent.isClass = ''
    }
  }
}
</script>