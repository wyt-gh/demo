<template>
  <div id="main">
    <img :src="dataList.image"
         alt=""
         width="100%">
    <p>现价: {{dataList.now}} 元</p>
    <p>原价: {{dataList.before}} 元</p>
    <p>数量: {{dataList.number}} 件</p>

    <div id="shop">
      <ul>
        <li @click="addShopping(dataList.Id)">加入购物车</li>
        <li>立即购买</li>
      </ul>
    </div>
  </div>
</template>

<script>
import { TABBAR_STATE, ADD_SHOPPING } from '@/type'
import { MessageBox } from 'mint-ui'
import axios from 'axios'

export default {
  name: 'Id',
  data () {
    return {
      dataList: []
    }
  },
  methods: {
    addShopping (id) {
      MessageBox.confirm('确定加入购物车?').then(action => {
        axios({
          method: "post",
          url: "http://www.tp5.com/index/index/addShopping",
          data: {
            Id: id
          }
        }).then(res => '')
      }).catch(err => '')
    }
  },
  mounted () {
    // console.log(JSON.parse(this.$route.params.data))
    this.dataList = JSON.parse(this.$route.params.data)
    this.$store.commit(TABBAR_STATE, false)

  },
  beforeDestroy () {
    this.$store.commit(TABBAR_STATE, true)
  }
}
</script>

<style  scoped>
#shop {
  width: 100%;
  height: 50px;
  position: fixed;
  left: 0;
  bottom: 0;
  background-color: #f9f9f9;
}
#shop ul {
  display: flex;
}
#shop ul li {
  list-style: none;
  flex: 1;
  text-align: center;
  line-height: 50px;
}
</style>