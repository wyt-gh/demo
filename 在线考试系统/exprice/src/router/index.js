import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/Login'
import Change_pw from '@/components/Change_pw'
import Index from '@/views/Index'
import Exam from '@/views/Exam'
import Select from '@/views/Select'
import FalseExam from '@/views/select/FalseExam'
import RankingList from '@/views/select/RankingList'
import Exercises from '@/views/Exercises'
import Video from '@/views/Video'
import VideoDetail from '@/views/VideoDetail'

import Book from '@/views/Book'
import BookSearch from '@/views/BookSearch'
import BookDetail from '@/views/BookDetail'
import BookRack from '@/views/BookRack'
import BookRead from '@/views/BookRead'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '*',
      redirect: '/index',
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/change_pw',
      name: 'Change_pw',
      component: Change_pw
    },
    {
      path: '/index',
      name: 'Index',
      component: Index
    },
    {
      path: '/exam/:subject_name',
      name: 'Exam',
      component: Exam
    },
    {
      path: '/select',
      name: 'Select',
      component: Select
    },
    {
      path: '/falseexam',
      name: 'FalseExam',
      component: FalseExam
    },
    {
      path: '/rankinglist',
      name: 'RankingList',
      component: RankingList
    },
    {
      path: '/exercises',
      name: 'Exercises',
      component: Exercises
    },
    {
      path: '/video',
      name: 'Video',
      component: Video
    },
    {
      path: '/videodetail/:active/:index',
      name: 'VideoDetail',
      component: VideoDetail
    },
    {
      path: '/booksearch',
      name: 'BookSearch',
      component: BookSearch
    },
    {
      path: '/bookdetail/:id/:subject_id',
      name: 'BookDetail',
      component: BookDetail
    },
    {
      path: '/book',
      name: 'Book',
      component: Book,
    },
    {
      path: '/bookrack',
      name: BookRack,
      component: BookRack,
    },
    {
      path: '/bookread/:id/:num',
      name: 'BookRead',
      component: BookRead
    },
  ]
})
import { Notify } from 'vant';
//验证是否登录
router.beforeEach((to, from, next) => {
  let is_login = JSON.parse(localStorage.getItem('is_login'));
  let now_time = new Date().getTime() / 1000;
  let login_time = now_time - is_login; //限时
  let time = 60 * 60 * 24;
  //to.path 目标路由
  //next 必须有否则程序不会继续执行
  if (to.path == '/change_pw') {
    return next()
  }
  if (to.path !== '/login' && !is_login) {
    Notify({ type: 'danger', message: '请先登录' });
    return next('/login')
  }
  if (to.path !== '/login' && login_time > time) {
    localStorage.clear();
    Notify({ type: 'danger', message: '登录超时,请重新登录' });
    return next('/login')
  }
  next()
})

export default router