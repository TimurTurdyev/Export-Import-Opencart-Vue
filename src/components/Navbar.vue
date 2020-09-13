<template>
  <aside>
  <ul class="menu-list">
    <li><router-link to="/">Export</router-link></li>
    <li><router-link to="/import">Import</router-link></li>
  </ul>
  </aside>
</template>

<script>
export default {
  name: 'Navbar',
  props: {
    nav_links: Array
  }
}
</script>