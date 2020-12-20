import Vue from 'vue'
import Vuex from 'vuex'

import axios from 'axios'
import { Toast } from 'vant'
import Router from '@/router' //在vuex里使用路由跳转需要导入
import { TABBARSHOW } from '@/type'
Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    TabbarShow: true, //导航栏是否显示
    UserInfo: [],
    Subject: [], //科目列表
    FalseTopicList: [], //错题卡
    Single: [{}],
    Selection: [],
    Judge: [{}],
    Sin_sel_len: 0,
    Sin_sel_jud_len: 0,
    SubRanking: [], //科目排行榜
    BookRack: '',  //书架
    // 书城推荐
    Hot: [],  //最热
    New: [],  //最新
    Free: [], //免费
    End: [],   //完本
    //视频
    VideoSource: [],  //视频资源
  },
  actions: {
    GetUserInfo (state) {
      let data = JSON.parse(localStorage.getItem('admin'));
      axios({
        method: "post",
        url: "api/index/login",
        data: {
          data: data
        }
      }).then(res => {
        this.state.UserInfo = res.data;
      }).catch(err => {
        console.log(err);
      });
    },
    GetSubject () {
      let admin = JSON.parse(localStorage.getItem('admin'));

      axios({
        method: 'post',
        url: "api/index/subject",
        data: {
          major_id: admin['major_id']
        }
      }).then(res => {
        if (res.data) {
          //请求成功
          this.state.Subject = res.data;
          this.state.Subject = this.state.Subject.map(val => {
            return val['subject_name'];
          })
        }
      }).catch(err => {
        console.log(err);
      })
    },
    GetFalseTopicList () {
      let admin = JSON.parse(localStorage.getItem('admin'));
      let now_subject_name = JSON.parse(localStorage.getItem('now_subject_name'));
      axios({
        method: 'post',
        url: "/api/index/select_falseexam",
        data: {
          major_id: admin['major_id'],
          subject_name: now_subject_name,
          id_card: admin.id_card
        }
      }).then(res => {
        if (res.data.length == 0) {
          Toast('恭喜您,没有错题!');
          Router.push('/select');
        } else {
          this.state.FalseTopicList = res.data;
          this.state.Single = this.state.FalseTopicList.single;
          this.state.Selection = this.state.FalseTopicList.selection;
          this.state.Judge = this.state.FalseTopicList.judge;
          this.state.Sin_sel_len = this.state.Single.length + this.state.Selection.length;
          this.state.Sin_sel_jud_len = this.state.Sin_sel_len + this.state.Judge.length;
        }
      }).catch(err => {
        console.log(err)
      })
    },
    GetSubRanking () {
      let admin = JSON.parse(localStorage.getItem('admin'));
      let subject_name = JSON.parse(localStorage.getItem('now_subject_name'));
      axios({
        method: 'post',
        url: '/api/index/sub_rankinglist',
        data: {
          major_id: admin['major_id'],
          subject_name: subject_name,
        }
      }).then(res => {
        // console.log(res.data)
        this.state.SubRanking = res.data;
      }).catch(err => {
        console.log(err)
      })
    },
    GetBookRack () {
      let admin = JSON.parse(localStorage.getItem('admin'));
      axios({
        method: 'post',
        url: '/api/book/getbookrack',
        data: {
          student_id: admin['id']
        }
      }).then(res => {
        if (res.data) {
          this.state.BookRack = res.data;
        }
      })
    },
    GetHotBook () {
      axios({
        method: 'post',
        url: '/api/book/hotbook',
      }).then(res => {
        this.state.Hot = res.data;
      })
    },
    GetNewBook () {
      axios({
        method: 'post',
        url: '/api/book/newbook',
      }).then(res => {
        this.state.New = res.data;
      })
    },
    GetFreeBook () {
      axios({
        method: 'post',
        url: '/api/book/freebook',
      }).then(res => {
        this.state.Free = res.data;
      })
    },
    GetEndBook () {
      axios({
        method: 'post',
        url: '/api/book/endbook',
      }).then(res => {
        this.state.End = res.data;
      })
    },
  },
  mutations: {
    choose_subject (state, val) {
      state.NowSubject = val
    },
    [TABBARSHOW] (state, played) {
      state.TabbarShow = played;
    }
  }
})

export default store