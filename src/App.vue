<template>
  <div id="app" class="min_height">
    <navbar/>
    <div class="container is-fluid">
      <br>
      <router-view/>
      <br>
    </div>
  </div>
</template>

<script>
import navbar from '@/components/Navbar';

export default {
  components: {
    navbar
  }
}
</script>
<style>
#app {
  position: relative;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
  font-size: 16px;
  line-height: 1.5;
  height: 100%;
  background: #ECF0F3;
}
.min_height {
  min-height: 100vh;
}
</style>