<template>
  <ul id="tab">
    <router-link to="/home"
                 tag="li"
                 active-class='active'
                 class="iconfont iconziyuan">首页</router-link>
    <router-link to="/find"
                 tag="li"
                 active-class='active'
                 class="iconfont iconsearch">发现</router-link>
    <router-link to="/order"
                 tag="li"
                 active-class='active'
                 class="iconfont icondingdan">购物车</router-link>
    <router-link to="/myself"
                 tag="li"
                 active-class='active'
                 class="iconfont iconwode">我的</router-link>

  </ul>

</template>


<style >
#tab {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 50px;
  display: flex;
  background-color: #f9f9f9;
}
#tab li {
  list-style: none;
  flex: 1;
  text-align: center;
  line-height: 50px;
  font-size: 20px;
}
.active {
  color: red;
}
</style>