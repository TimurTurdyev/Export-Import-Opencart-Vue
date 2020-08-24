<template>
  <div id="app">
    <div class="sidebar-bg"></div>
    <div class="container">
      <div class="columns min_height">
        <div class="column is-one-fifth has-background-light">
          <br>
          <navbar/>
        </div>
        <div class="column has-background-white">
          <br>
          <router-view/>
        </div>
      </div>
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
}
.min_height {
  min-height: 100vh;
}
.sidebar-bg {
  position: absolute;
  bottom: 0;
  right: 50%;
  left: 0;
  top: 0;
  background: #f5f5f5;
}
</style>