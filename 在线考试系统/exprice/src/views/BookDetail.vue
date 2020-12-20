<template>
  <div id="main">
    <van-nav-bar left-arrow
                 fixed
                 title="书籍详情"
                 placeholder
                 @click-left="onClickLeft"
                 style="border-bottom:1px solid #1989fa" />
    <van-cell>
      <div class="info_top">
        <div style="width:34%;height:10em;text-align:center"><img :src="data.cover_img"
               class="cover_img"></div>
        <div class="tr_info">
          <p class="name">{{data.book_name}}</p>
          <p>{{data.username}} 著</p>
          <p>{{data.title}}</p>
          <p class="it_bottom"><b class="fs">{{data.grade}}</b> 分 <b class="fs">{{data.read}}</b> 阅读</p>
          <span class="it_bottom"
                style="right:0">
            <van-button icon="label-o"
                        type="info"
                        plain
                        size="mini"
                        @click="showcatalog">
              目录
            </van-button>
          </span>
        </div>
      </div>
    </van-cell>
    <!-- 详情 -->
    <van-cell>
      <div class="synopsis van-multi-ellipsis--l3">
        简介: {{data.synopsis}}
      </div>
    </van-cell>
    <!-- 目录 -->
    <van-popup v-model="show"
               position="left"
               round
               :style="{ height:'100%',width:'70%',padding:'0 0 2em 0'}">
      <van-cell-group>
        <van-cell>
          <h2>目录</h2>
        </van-cell>
        <van-cell v-for="(sec,ind) in catalog"
                  :key="ind"
                  @click="toread(sec.section_num)">第{{sec.section_num}}章 {{sec.section_name}}</van-cell>
      </van-cell-group>
    </van-popup>

    <div class="tjmain">
      <div class="box">
        <!-- 标题 -->
        <van-cell>
          <template #title>
            <span class="titlefs">同类推荐</span>
          </template>
          <span @click="changesame(data.subject_id)">
            <van-icon name="replay" />换一换
          </span>
        </van-cell>
        <!-- 内容 -->
        <div class="recommend">
          <van-grid v-if="SameSubject"
                    :column-num="4">
            <van-grid-item v-for="(da,ind) in SameSubject"
                           :key="ind"
                           @click="todetail(da)"
                           id="bookbox">
              <img class="img"
                   :src="da.cover_img"
                   alt="未加载">
              <p>{{da.book_name}}</p>
            </van-grid-item>
          </van-grid>
        </div>
      </div>
    </div>
    <!-- 底部 -->
    <van-cell class="book_option">
      <div style="width:30%;position:absolute;left:0;bottom:0;">
        <van-button v-if="bookrack"
                    plain
                    hairline
                    block
                    type="info"
                    @click="add_bookrack">
          已加入
        </van-button>
        <van-button v-else
                    plain
                    hairline
                    block
                    type="info"
                    @click="add_bookrack">
          加入书架
        </van-button>
      </div>
      <div style="width:70%;position:absolute;right:0;bottom:0">
        <van-button type="info"
                    block
                    @click="toread()">免费阅读全文</van-button>
      </div>

    </van-cell>

  </div>
</template>

<script>
import axios from 'axios';
import { TABBARSHOW } from '@/type'
import { NavBar, Cell, CellGroup, Button, Toast, Grid, GridItem, Overlay, Icon } from 'vant';

export default {
  data () {
    return {
      data: '',
      show: false,
      catalog: [],  //目录
      student_id: '', //学生id
      bookrack: 0,  //是否加入书架
      SameSubject: [],  //相同类型的书籍
    }
  },
  methods: {
    todetail (data) {
      this.getDetail(data.id);
      this.isbookrack(data.id);
      this.getSameBook(data.subject_id, data.id)
    },
    getDetail (id) {  //获取书籍信息
      axios({
        method: 'post',
        url: '/api/book/detail',
        data: {
          book_id: id,
          student_id: this.student_id,
        }
      }).then(res => {
        this.data = res.data;
      })
    },
    isbookrack (id) {
      axios({
        method: 'post',
        url: '/api/book/isbookrack',
        data: {
          book_id: id,
          student_id: this.student_id,
        }
      }).then(res => {
        this.bookrack = res.data;
      })
    },
    onClickLeft () {  //退出
      this.$router.back(-1)
    },
    add_bookrack () { //添加书架
      if (this.bookrack) {
        axios({
          method: 'post',
          url: '/api/book/del_bookbrak',
          data: {
            ids: this.data.id,
            student_id: this.student_id
          }
        }).then(res => {
          Toast.success(res.data.msg);
          this.bookrack = 0;
        })
      } else {
        axios({
          method: 'post',
          url: '/api/book/addbookrack',
          data: {
            id: this.data.id,
            student_id: this.student_id
          }
        }).then(res => {
          Toast.success(res.data.msg);
          this.bookrack = 1;
        })
      }
    },
    searchmemo () {
      axios({
        method: 'post',
        url: '/api/book/memo',
        data: {
          student_id: this.student_id,
          book_id: this.data.id
        }
      }).then(res => {
        let id = this.data.id;
        let num = res.data.memo;
        this.$router.push({
          path: `/bookread/${id}/${num}`,
        })
      })
    },
    toread (num) {
      //先查询是否有章节内容,没有不让阅读
      axios({
        method: 'post',
        url: '/api/book/select_section',
        data: {
          book_id: this.data.id
        }
      }).then(res => {
        if (!res.data.code) {
          Toast('该书籍暂无任何章节!')
          return false;
        } else {
          if (!num) {
            this.searchmemo();
            return false;
          }
          let id = this.data.id;
          this.$router.push({
            path: `/bookread/${id}/${num}`,
          })
        }
      })

    },
    showcatalog () {
      this.show = !this.show;
      axios({
        method: 'post',
        url: '/api/book/catalog',
        data: {
          book_id: this.data.id,
        }
      }).then(res => {
        this.catalog = res.data;
      })
    },
    getSameBook (subject_id, book_id) {
      if (!book_id) {
        var book_id = this.$route.params.id;
      }
      axios({
        method: 'post',
        url: '/api/book/samebook',
        data: {
          subject_id: subject_id,
          book_id: book_id
        }
      }).then(res => {
        this.SameSubject = res.data;
      })
    },
    changesame () {
      this.getSameBook(this.data.subject_id, this.data.id);
    },
  },
  mounted () {
    this.$store.commit(TABBARSHOW, false)
    let id = this.$route.params.id;
    let subject_id = this.$route.params.subject_id;
    this.student_id = JSON.parse(localStorage.getItem('admin'))['id'];
    this.getDetail(id);
    this.isbookrack(id);
    this.getSameBook(subject_id)
  }
}
</script>

<style scoped>
#main {
  margin-bottom: 8em;
}
.info_top {
  width: 100%;
  height: 10em;
  position: relative;
}
.tr_info {
  width: 60%;
  height: 100%;
  position: absolute;
  top: 0;
  right: 0;
  font-size: 1em;
}
.name {
  font-size: 1.5em;
  font-weight: bold;
}
.tr_info p {
  font-family: "楷体";
  margin: 0 auto 2px 0;
}
.synopsis {
  width: 100%;
  /* height: 5em; */
  text-indent: 2em;
  padding: 1% 0;
  border-top: 1px solid #1989fa;
  border-bottom: 1px solid #1989fa;
  /* max-width: 400px; */
  /* display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
  overflow: hidden; */
}
.book_option {
  width: 100%;
  height: 5em;
  position: fixed;
  bottom: 0;
  left: 0;
}
.fs {
  font-size: 1.5em;
}
.it_bottom {
  position: absolute;
  bottom: 0;
}
.tjmain {
  margin: 0 auto 5em 0;
}
.box {
  margin: 0.5em auto;
}
#bookbox {
  /* margin: 0em 0; */
  padding: -5px 0px;
}
.recommend p {
  margin-bottom: 0;
}
.cover_img {
  width: 90%;
  height: 100%;
}
.img {
  width: 7em;
  height: 9em;
  border-radius: 5px;
}
.titlefs {
  font-size: 1.3em;
  font-weight: bold;
}
</style>