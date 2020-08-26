<template>
  <div>
    <div class="columns">
      <div class="column is-8">
        <div class="field">
          <label class="label">Name</label>
          <div class="control">
            <input class="input" type="text" placeholder="Text Name">
          </div>
        </div>

        <div class="field">
          <label class="label">Meta H1</label>
          <div class="control">
            <input class="input" type="text" placeholder="Text Meta H1">
          </div>
        </div>

        <div class="field">
          <label class="label">Meta Title</label>
          <div class="control">
            <input class="input" type="text" placeholder="Text Meta Title">
          </div>
        </div>

        <div class="field">
          <label class="label">Meta Description</label>
          <div class="control">
            <input class="input" type="text" placeholder="Text Meta Description">
          </div>
        </div>

        <div class="field">
          <label class="label">Body</label>
          <div class="control">
            <textarea class="textarea" placeholder="Body"></textarea>
          </div>
        </div>
      </div>
      <div class="column">

        <div class="card">
          <div class="card-image">
            <figure class="image is-4by3">
              <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
            </figure>
          </div>
          <div class="card-content">
            <div class="media">
              <div class="media-left">
                <figure class="image is-48x48">
                  <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
                </figure>
              </div>
              <div class="media-content">
                <p class="title is-4">John Smith</p>
                <p class="subtitle is-6">@johnsmith</p>
              </div>
            </div>

            <div class="content">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              Phasellus nec iaculis mauris. <a>@bulmaio</a>.
              <a href="#">#css</a> <a href="#">#responsive</a>
              <br>
              <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'

export default {
  computed: {
    ...mapGetters({
      product: 'product/product',
    }),
  },
  methods: {
    ...mapActions({
      loadProduct: 'product/product'
    })
  },
  mounted() {
    console.log(this.$route.params.id)
    this.loadProduct(this.$route.params.id)
  }
}
</script>