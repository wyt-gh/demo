import Vue from 'vue'
import Router from 'vue-router'

import Home from '@/view/Home'
import List from '@/view/List'
import Shopping from '@/view/Shopping'
import Person from '@/view/Person'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '*',
      name: 'Home',
      component: Home
    },
    {
      path: '/home',
      name: 'Home',
      component: Home
    },
    {
      path: '/list',
      name: 'List',
      component: List
    },
    {
      path: '/shopping',
      name: 'Shopping',
      component: Shopping
    },
    {
      path: '/person',
      name: 'Person',
      component: Person
    },
  ]
})
