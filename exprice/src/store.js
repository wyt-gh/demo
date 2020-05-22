import Vue from 'vue'
import Vuex from 'vuex' //状态管理
import axios from 'axios'
import { TABBAR_STATE, DELETE_SHOPPING } from '@/type'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    Goods_dataList: [], //商品数据
    Tabbar_isshow: true,  //导航栏  
    shopping: [], //购物车内的商品
    UserInfo: [], //为空说明用户没有登录,登录后将信息储存在这个数组中
  },
  actions: {
    GetGoodsAction (state) {
      axios({
        url: "http://www.tp5.com/index/index/index",
        method: "get"
      }).then(res => {
        // console.log(typeof (res.data))
        // console.log(res.data)
        this.state.Goods_dataList = res.data
        // console.log(this.state.Goods_dataList)

      })
    },
    GetShoppingAction (state) {
      axios({
        url: "http://www.tp5.com/index/index/showShopping",
        method: "get"
      }).then(res => {
        // console.log(typeof (res.data))
        // console.log(res.data)
        this.state.shopping = res.data
      })
    }
  },
  mutations: {
    [TABBAR_STATE] (state, payload) {
      state.Tabbar_isshow = payload
    },
    [DELETE_SHOPPING] (state, payload) {
      state.shopping = state.shopping.filter(item => {
        let check = false
        payload.forEach(element => {
          if (element == item) {
            check = true
          }
        })
        if (check == false) {
          return item
        }
      })
    }
  }
})