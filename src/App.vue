<template>
  <div id="app">
    <router-view></router-view>
    <Tabbar v-show="Tabbar_isshow"></Tabbar>
  </div>

</template>

<script>
import Tabbar from '@/components/Tabbar'
import { mapState } from 'vuex'

export default {
  components: {
    Tabbar,
  },
  computed: {
    ...mapState(["Tabbar_isshow"]),
  }
}
</script>

<style>
* {
  padding: 0;
  margin: 0;
}
#app {
  width: 100%;
  height: 100%;
}
</style>
