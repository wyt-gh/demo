<template>
  <div class="swiper">
    <van-swipe class="my-swipe"
               :autoplay="3000"
               :height="100">
      <van-swipe-item v-for="(da,ind) in img"
                      :key="ind">
        <img :src="da"
             alt="加载失败!">
      </van-swipe-item>
    </van-swipe>
  </div>
</template>

<script>
import { Swipe, SwipeItem } from 'vant';
export default {
  data () {
    return {
      img: [
        "//img10.360buyimg.com/cms/jfs/t1/92462/9/15619/59491/5e744dadE05349cc9/bceda5a279f54a02.jpg!q70.dpg.webp",
        "//img14.360buyimg.com/cms/jfs/t1/98725/26/15749/49485/5e744e03E4f7dea7c/d5faebacb23260a4.jpg!q70.dpg.webp",
        "//img30.360buyimg.com/cms/jfs/t1/115999/10/784/182169/5e906e71Ed3297a5c/70f2cc7747e8518a.jpg!q70.dpg.webp",
        "//img10.360buyimg.com/cms/jfs/t1/127915/7/16363/56639/5f978727E3355bb5a/e6c7bda2a962e588.jpg!q70.dpg.webp",
        "//img14.360buyimg.com/cms/jfs/t1/145804/13/12232/155347/5f9783faEe5e059c3/3bec33c7b5538c10.jpg!q70.dpg.webp"
      ]
    }
  }
}
</script>

<style scoped>
.swiper {
  margin: 1em 0;
}
.my-swipe .van-swipe-item {
  color: #fff;
  font-size: 20px;
  line-height: 150px;
  text-align: center;
}
img {
  width: 17em;
  height: 5em;
  border-radius: 10px;
}
</style>