<template>
  <div id="main">
    <ul>
      <li v-for="(data,index) in shopping_count"
          :key="index">
        <input type="checkbox"
               name="checkbox"
               ref='checkbox'
               :value="data.Id">
        <img :src="data.image"
             alt=""
             width="100px">
        <p class="goods_name">{{data.name}}</p>
        <p>数量: {{data.number}} 件</p>

      </li>
    </ul>
    <div id="operat">
      <mt-button type="danger"
                 size="small"
                 @click="operatShopping('delete')">删除</mt-button>
      <mt-button type="primary"
                 size="small"
                 @click="operatShopping('close')">结算</mt-button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { DELETE_SHOPPING } from '@/type'

export default {
  methods: {
    operatShopping (type) {
      let checkbox = []
      this.$refs.checkbox.map(item => item.checked == true ? checkbox.push(item.value) : '')
      //这里要转成字符串,不然数据会有问题
      checkbox = checkbox.join(',')
      axios({
        method: 'post',
        url: 'http://www.tp5.com/index/index/operatShopping',
        data: {
          operatArr: checkbox,
          type: type
        }
      }).then((res) => {
        checkbox = checkbox.split(",")
        // console.log(checkbox)
        this.$store.commit(DELETE_SHOPPING, checkbox)
      })

    }
  },
  mounted () {
    if (this.$store.state.Goods_dataList.length === 0) {
      this.$store.dispatch("GetGoodsAction")
      // console.log(this.$store.state.Goods_dataList)
    }
    this.$store.dispatch("GetShoppingAction")
  },
  computed: {
    shopping_count () {
      let dataList = []
      this.$store.state.Goods_dataList.map(item => {
        this.$store.state.shopping.forEach(element => {
          // console.log(element)
          // console.log(item.Id)
          if (item.Id == element) {
            dataList.push(item)
          }
        })
      })
      return dataList
    }
  }
}
</script>

<style lang="" scoped>
#main {
  width: 100%;
  margin-bottom: 90px;
}
#main ul li {
  width: 100%;
  height: 150px;
  border-bottom: 1px solid;
  /* padding: 25px 50px; */
}
img {
  margin: 25px 50px;
  float: left;
}
.goods_name {
  margin: 25px 50px;
}
#operat {
  width: 100%;
  height: 40px;
  position: fixed;
  left: 0;
  bottom: 50px;
  background: rgb(245, 237, 237);
}
#operat button {
  float: right;
  margin: auto 10px;
}
</style>