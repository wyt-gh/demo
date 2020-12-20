<template>
  <ul class="option">
    <li>
      <van-icon name="apps-o"
                color="#39a9ed"
                size="20" /><br>分类
    </li>
    <li>
      <van-icon name="medal-o"
                color="#39a9ed"
                size="20" /><br>排行
    </li>
    <li>
      <van-icon name="vip-card-o"
                color="#39a9ed"
                size="20" /><br>会员
    </li>
    <li>
      <van-icon name="cash-on-deliver"
                color="#39a9ed"
                size="20" /><br>免费
    </li>
    <li>
      <van-icon name="passed"
                color="#39a9ed"
                size="20" /><br>完结
    </li>
  </ul>
</template>

<script>
import { Icon } from 'vant';
export default {

}
</script>

<style scoped>
.option {
  /* border: 1px solid; */
  width: 90%;
  height: 4em;
  display: flex;
  margin: 1.5em auto;
}
.option li {
  flex: 1;
  text-align: center;
  font-size: 15px;
}
</style>