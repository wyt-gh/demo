<template>
  <div>
    <ul>
      <router-link to="/index"
                   tag="li"
                   active-class="color">
        <van-icon name="todo-list-o" />考试
      </router-link>
      <router-link to="/video"
                   tag="li"
                   active-class="color">
        <van-icon name="video-o" />视频
      </router-link>
      <router-link to="/book"
                   tag="li"
                   active-class="color">
        <van-icon name="coupon-o" />书城
      </router-link>
    </ul>
  </div>
</template>

<script>
import { Icon } from 'vant';
export default {

}
</script>

<style scoped>
ul {
  width: 100%;
  height: 5em;
  position: fixed;
  bottom: -1px;
  left: 0;
  display: flex;
  background-color: #fff;
  z-index: 5;
}
li {
  height: 3em;
  flex: 1;
  text-align: center;
  line-height: 3em;
  font-size: 1.6em;
  /* border: 1px solid; */
}
.color {
  color: #1989fa;
  border-top: 2px solid #1989fa;
  background-color: #fff;
  box-shadow: 0px 15px 10px -15px #1989fa;
}
</style>