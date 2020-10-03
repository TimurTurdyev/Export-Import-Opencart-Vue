<template>
  <nav class="navbar is-white">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item brand-text" href="#">
          Bulma Admin
        </a>
        <div class="navbar-burger burger">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div class="navbar-menu">
        <div class="navbar-start">
          <router-link to="/" class="navbar-item">Export</router-link>
          <router-link to="/import" class="navbar-item">Import</router-link>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
  props: {
    nav_links: Array
  }
}
</script>
<style>
nav.navbar {
  border-top: 4px solid #276cda;
  margin-bottom: 1rem;
}
</style>