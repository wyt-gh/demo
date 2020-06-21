<template>
  <ul>
    <router-link to="/home"
                 tag="li"
                 active-class="color"
                 class="iconfont iconfaxian-copy">首页
    </router-link>
    <router-link to="/list"
                 tag="li"
                 active-class="color"
                 class="iconfont iconiconfront-">列表
    </router-link>
    <router-link to="/shopping"
                 tag="li"
                 active-class="color"
                 class="iconfont iconqicheqianlian-">购物车
    </router-link>
    <router-link to="/person"
                 tag="li"
                 active-class="color"
                 class="iconfont iconwode">我的
    </router-link>
  </ul>
</template>

<style scoped>
ul {
  width: 100%;
  height: 40px;
  display: flex;
  background-color: white;
  position: fixed;
  left: 0;
  bottom: 0;
  text-align: center;
  line-height: 40px;
  border-top: 1px solid #f4f4f4;
}

li {
  list-style: none;
  flex: 1;
  font-size: 20px;
}
.color {
  color: red;
}
</style>