<template>
  <div id="app">
    <router-view />
    <Tabbar></Tabbar>
  </div>

</template>

<script>
import Tabbar from '@/components/Tabbar'

export default {
  components: {
    Tabbar,
  }
}
</script>

<style>
* {
  padding: 0;
  margin: 0;
}
body {
}
</style>
