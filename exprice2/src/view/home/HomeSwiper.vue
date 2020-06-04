<template>
  <mt-swipe :auto="4000"
            id="swiper">
    <mt-swipe-item v-for="(data,index) in dataList"
                   :key="index">
      <img :src="'/static/images/bannar/'+data+'.jpg'"
           alt=""
           height="100%"
           width="100%">
    </mt-swipe-item>
  </mt-swipe>
</template>

<script>
import { Swipe, SwipeItem } from 'mint-ui';
export default {
  data () {
    return {
      dataList: [1, 2, 3, 4, 5, 6]
    }
  }
}
</script>

<style scoped>
#swiper {
  height: 200px;
  width: 100%;
}
</style>