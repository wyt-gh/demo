// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'



//引入阿里巴巴图标库
import '@/assets/icon/iconfont.css'
//引入手机端组件库
import MintUI from 'mint-ui'
import 'mint-ui/lib/style.css'
//引入pc端组件库
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

Vue.config.productionTip = false
Vue.use(MintUI)
Vue.use(ElementUI)

new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
