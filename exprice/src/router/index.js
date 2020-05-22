import Vue from 'vue'
import Router from 'vue-router'

import Home from '@/view/Home'
import Find from '@/view/Find'
import Order from '@/view/Order'
import Myself from '@/view/Myself'
import GoodsInfo from '@/view/home/GoodsInfo'
import Login from '@/view/myself/Login'

Vue.use(Router)

const router = new Router({
  mode: "history",
  routes: [
    {
      path: '*',
      redirect: "/home"
    },
    {
      path: '/home',
      name: '/home',
      component: Home
    },
    {
      path: '/find',
      name: '/Find',
      component: Find
    },
    {
      path: '/order',
      name: '/Order',
      component: Order
    },
    {
      path: '/myself',
      name: '/Myself',
      component: Myself
    },
    {
      path: '/goodsinfo/:data',
      name: 'GoodsInfo',
      component: GoodsInfo
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
  ]
})

// router.beforeEach((to, from, next) => {
//   if (true) {
//     store.commit(TABBAR_STATE, false)
//     // next('/myself')
//   } else {
//     next('/login')
//   }
// })


export default router