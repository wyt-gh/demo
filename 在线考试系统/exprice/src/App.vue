<template>
  <div id="app">
    <router-view />
    <tabbar v-show="$store.state.TabbarShow"></tabbar>
  </div>
</template>

<script>
import tabbar from "@/components/Tabbar"

export default {
  name: 'App',
  components: {
    tabbar,
  },
}
</script>

<style>
body {
  font-size: 62.5%;
  /* opacity: 0.7; */
}
</style>
