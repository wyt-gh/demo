<template>
  <ul id="menu">
    <li v-for="(data,index) in dataList"
        :key="index">
      <img src="/static/images/icon/美食.png"
           alt="">
      <p>美食{{data}}</p>
    </li>
  </ul>
</template>

<script>
export default {
  data () {
    return {
      dataList: [1, 2, 3, 4, 5, 6, 7, 8]
    }
  }
}
</script>

<style  scoped>
#menu {
  width: 100%;
  margin: 20px auto;
  /* display: flex; */
}
ul li {
  list-style: none;
  text-align: center;
  height: 75px;
  /* width: 70px; */
  width: 25%;
  font-size: 20px;
  margin: 10px 0px;
  float: left;
}
</style>