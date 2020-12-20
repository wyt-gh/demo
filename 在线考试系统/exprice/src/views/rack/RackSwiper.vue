<template>
  <div class="swiper">
    <!-- <van-cell> -->
    <!-- <div style="position:relative"> -->
    <van-swipe style="height: 8.5em;width:60%;position:absolute;top:0.5em;left:1em"
               :autoplay="2000"
               duration="1500"
               vertical>
      <van-swipe-item v-for="(da,ind) in imglist"
                      :key="ind">
        <img :src="da">
      </van-swipe-item>
    </van-swipe>
    <span class="box">
      <p class="p">签到</p>
      <p>免广告20分钟</p>
    </span>
    <!-- </div> -->
    <!-- </van-cell> -->
  </div>
</template>

<script>
import { Swipe, SwipeItem, Cell, CellGroup } from 'vant';
export default {
  data () {
    return {
      imglist: ["https://bossaudioandcomic-1252317822.image.myqcloud.com/activity/document/d180d4bb40b8ed26a57bd59dec35e200.jpg",
        "https://bossaudioandcomic-1252317822.image.myqcloud.com/activity/document/9ce49e3bdbab2af614a296bd7b9e3091.jpg",


      ],
    }
  }
}
</script>

<style scoped>
.swiper {
  position: relative;
  /* border: 1px solid; */
  width: 100%;
  height: 8.5em;
  overflow: hidden;
}
img {
  width: 100%;
  height: auto;
  border-radius: 10px;
}
.box {
  /* border: 1px solid black; */
  width: 30%;
  height: 7.5em;
  /* padding: 0 5%; */
  border-radius: 10px;
  position: absolute;
  top: 0.5em;
  right: 1em;
  text-align: center;
  background: url("//bpic.588ku.com//back_origin_min_pic/19/07/03/3d1bb6c1fe5e76a395c78f5f92393bc2.jpg!/fw/750/quality/99/unsharp/true/compress/true")
    no-repeat;
  background-size: 100% 100%;
  color: aliceblue;
}
.p {
  margin: 3px auto 0 auto;
  font-size: 1.2em;
}
</style>