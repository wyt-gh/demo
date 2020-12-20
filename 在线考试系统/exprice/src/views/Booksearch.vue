<template>
  <div id="main">
    <!-- 搜索框 -->
    <div class="search">
      <form action="/">
        <van-search v-model="value"
                    fixed
                    show-action
                    placeholder="请输入搜索关键词"
                    background="#39a9ed"
                    @search="onSearch"
                    @cancel="onCancel"
                    @input="onChange(value)"
                    @clear="clear" />
      </form>
    </div>
    <!-- 搜索信息补全 -->
    <transition name="van-fade">
      <div class="complete">
        <p v-for="(da,ind) in newsearch"
           :key="ind"
           @click="onSearch(da.book_name)">
          <van-icon name="search" />
          <span class="custom-title">{{da.book_name}}</span>
        </p>
      </div>
    </transition>
    <!-- 历史记录 -->
    <transition name="van-fade">
      <div class="hismain"
           v-if="history.length">
        <van-nav-bar left-text="历史记录"
                     style="height:4em"
                     @click-right="clear_history()">
          <template #right>
            <van-icon name="delete"
                      size="18" />
          </template>
        </van-nav-bar>
        <ul class="history">
          <li v-for="(val,ind) in history"
              :key="ind">
            <div @click="onSearch(val.search_content)">{{val.search_content}}</div>
          </li>
        </ul>
        <div style="clear:both"></div>
      </div>
    </transition>
    <!-- 搜索到的书籍数据列表 -->
    <div class="list">
      <van-divider hairline
                   dashed
                   :style="{ color: '#1989fa', borderColor: '#1989fa', padding: '0 16px' }">
        {{list.length}} 条内容
      </van-divider>
      <van-list v-model="loading"
                :finished="finished">
        <van-cell v-for="(data,index) in list"
                  :key="index">
          <div class="info"
               @click="todetail(data.id,data.subject_id)">
            <img :src="data.cover_img"
                 style="width:5.5em;height:7em;margin-left:0.5em">
            <div class="right_info">
              <p class="bookname van-ellipsis">{{data.book_name}}</p>
              <p class="fs07">{{data.username }} 著 • <span class="bold7">{{data.grade}}</span> 分</p>
              <p class="fs07">标签: {{data.title}}</p>
              <p class="synopsis">简介: {{data.synopsis}}</p>
            </div>
          </div>
        </van-cell>
      </van-list>
    </div>
    <van-overlay :show="overlay"
                 @click="overlay = false" />
  </div>
</template>

<script>
import { TABBARSHOW } from '@/type'
import { Search, List, Toast, Cell, CellGroup, Icon, Grid, GridItem, NavBar, Divider } from 'vant';
import axios from 'axios'

export default {
  data () {
    return {
      value: '',
      admin_id: '', //学生id
      list: [],
      loading: false,
      finished: true,
      newsearch: [],  //搜索内容补全
      history: [], //历史记录
      overlay: false,
    }
  },
  mounted () {
    this.$store.commit(TABBARSHOW, false);
    this.admin_id = JSON.parse(localStorage.getItem('admin'))['id'];
    this.gethistory(this.admin_id);
  },
  methods: {
    gethistory () {
      axios({
        method: 'post',
        url: '/api/book/search_history',
        data: {
          student_id: this.admin_id
        }
      }).then(res => {
        this.history = res.data;
      })
    },
    addhistory (val) {
      axios({
        method: 'post',
        url: '/api/book/addhistory',
        data: {
          student_id: this.admin_id,
          search_content: val
        }
      })
    },
    clear_history () {
      axios({
        method: 'post',
        url: '/api/book/clear_history',
        data: {
          student_id: this.admin_id
        }
      }).then(res => {
        if (res.data.code) {
          this.history = [];  //清空
          Toast('清理完成');
        }
      })
    },
    onSearch (val) {
      //搜索
      if (!val) { Toast('搜索内容不能为空!'); return false }
      axios({
        method: 'post',
        url: '/api/book/search',
        data: {
          book_name: val
        }
      }).then(res => {
        this.addhistory(val); //添加历史记录
        this.newsearch = [];  //清空
        this.overlay = false;
        this.finished = false;
        setTimeout(() => {
          this.finished = true;
          this.list = res.data;
        }, 500);
      })
    },
    onCancel () {
      this.$router.back(-1)
    },
    onChange (value) {
      if (!value) {
        this.newsearch = [];  //清空
        this.overlay = false; //隐藏遮罩层
        return false;
      }
      this.overlay = true;    //显示遮罩层
      axios({
        method: 'post',
        url: '/api/book/complete',
        data: {
          book_name: value
        }
      }).then(res => {
        this.newsearch = res.data;
        this.list = [];
        // console.log(this.newsearch)
      })
    },
    clear () {
      this.list = [];
      this.newsearch = [];
    },
    todetail (id, subject_id) {
      this.$router.push({
        path: `/bookdetail/${id}/${subject_id}`,
      })
    }
  },
}
</script>

<style scoped>
#main {
  padding-top: 5em;
}
.search {
  width: 100%;
  height: 5em;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 3;
}
.complete {
  width: 100%;
  background-color: #fff;
  position: fixed;
  top: 5em;
  left: 0;
  z-index: 2;
}
.complete p {
  border-bottom: 1px solid #39a9ed;
  width: 95%;
  height: 3.5em;
  line-height: 3.5em;
  font-size: 1.5em;
  margin-left: 5%;
  margin-top: 0;
  margin-bottom: 0;
}
.custom-title {
  margin-right: 4px;
  vertical-align: middle;
}
.hismain {
  width: 100%;
}
.history {
  margin-top: 1em auto 0 auto;
}
.history li {
  float: left;
  margin: 0.5em 1em;
}
.history li div {
  border: 1px solid #39a9ed;
  padding: 1px 4px 1px 4px;
  text-align: center;
  line-height: 2em;
  /* width: 5em; */
  height: 2em;
  margin: 0;
  font-size: 1.2em;
}
.info {
  /* border: 1px solid; */
  width: 100%;
  height: 8em;
  border-bottom: 0.1px solid #39a9ed;
  position: relative;
}
.bookname {
  margin: 3px 0;
  font-size: 1.4em;
  font-weight: 700;
  color: #39a9ed;
  font-family: "楷体";
}
.right_info {
  width: 70%;
  height: 100%;
  /* border: 1px solid; */
  position: absolute;
  right: 0;
  top: 0;
}
.right_info p {
  margin: 0.5px 0;
}
.synopsis {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>

<style scoped>
.border {
  border: 1px solid #39a9ed;
}
.backgroundcolor {
  background-color: #39a9ed;
}
.fs07 {
  font-size: 0.7em;
}
.bold7 {
  font-weight: 700;
}
</style>