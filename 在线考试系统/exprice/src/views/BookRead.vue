<template>
  <div class="read">
    <transition name="van-slide-down">
      <van-nav-bar :style="{ backgroundColor: backColor,fontFamily: typeface}"
                   v-show="show_option"
                   left-text="退出"
                   fixed
                   left-arrow
                   style="box-shadow: 0px 0px 2px #000;"
                   @click-left="back">
        <template #title>
          <b :style="{color: color,fontFamily: typeface}">第{{data.section_num}}章 {{data.section_name}}</b>
        </template>
        <template #right>
          <van-icon name="ellipsis" />
        </template>
      </van-nav-bar>
    </transition>

    <transition name="van-slide-up">
      <van-tabbar v-show="show_option"
                  v-model="active"
                  style="box-shadow: 0px 0px 2px #000;"
                  :style="{ background: bgc }">

        <van-tabbar-item icon="orders-o"
                         @click="show_option=!show_option; showcatalog()"
                         ref="tabbar">目录</van-tabbar-item>
        <van-tabbar-item icon="discount"
                         @click="show_option=!show_option; progress=!progress "
                         ref="tabbar">进度条</van-tabbar-item>
        <van-tabbar-item icon="setting-o"
                         @click="show_option=!show_option; set=!set"
                         ref="tabbar">设置</van-tabbar-item>
        <van-tabbar-item v-if="nightmode"
                         icon="closed-eye"
                         @click="nightmode=!nightmode"
                         ref="tabbar">夜间</van-tabbar-item>
        <van-tabbar-item v-else
                         icon="eye-o"
                         @click="nightmode=!nightmode"
                         ref="tabbar">白天</van-tabbar-item>
      </van-tabbar>
    </transition>

    <!-- 目录 -->
    <van-popup v-model="show"
               position="left"
               round
               :style="{ backgroundColor: backColor,color: color,fontFamily: typeface,height:'100%',width:'80%',padding:'0 0 2em 0', }">
      <van-cell-group>
        <van-cell :style="{backgroundColor: backColor,color: color,fontFamily: typeface }">
          <h2>目录</h2>
        </van-cell>
        <van-cell v-for="(sec,ind) in catalog"
                  :key="ind"
                  :style="{backgroundColor: backColor,color: color,fontFamily: typeface }"
                  @click="toread(sec.section_num)">第{{sec.section_num}}章 {{sec.section_name}}</van-cell>
      </van-cell-group>
    </van-popup>
    <!-- 进度条 -->
    <transition name="van-slide-up">
      <div class="progress"
           :style="{ backgroundColor: backColor,color: color,fontFamily: typeface}"
           v-show="progress">
        <div class="pro_top">
          <van-icon name="arrow-left"
                    style="float:left"
                    @click="last" />
          <van-icon name="arrow"
                    style="float:right"
                    @click="next" />
          <div>
            <p style="margin-bottom:0.5em"
               ref="section_num">第{{num}}章 {{data.section_name}}</p>
            <p style="font-size:2px;margin-top:0;color:gray">{{value}}%</p>
          </div>
        </div>
        <div class="pro_top">
          <van-slider v-model="value"
                      button-size="18px"
                      @change="onChange"
                      @input="change_pro" />
        </div>

      </div>
    </transition>
    <!-- 设置模块 -->
    <transition name="van-slide-up">
      <div v-show="set"
           :style="{ backgroundColor: backColor,color: color,fontFamily: typeface}"
           class="set">
        <div class="font_size">
          <span style="float:left"
                @click="reduce_size">A-</span>
          <span style="float:right"
                @click="add_size">A+</span>
          <div class="size_pro">
            <div>
              <van-slider v-model="size"
                          button-size="18px"
                          :min="1"
                          :max="10" />
            </div>
          </div>
        </div>
        <div class="back_color">
          <div class="back_name">背景色 |</div>
          <ul>
            <li>
              <div style="background-color: #fff"
                   @click="backColor='#fff'"></div>
            </li>
            <li>
              <div style="background-color: #f3ead2"
                   @click="backColor='#f3ead2'"></div>
            </li>
            <li>
              <div style="background-color: #e6f0e6"
                   @click="backColor='#e6f0e6'"></div>
            </li>
            <li>
              <div style="background-color: #e1e1e1"
                   @click="backColor='#e1e1e1'"></div>
            </li>
          </ul>
        </div>

        <div class="typeface">
          <div class="typeface_name">字体 |</div>
          <ul>
            <li>
              <div style="font-family: ''"
                   @click="typeface=''">默认</div>
            </li>
            <li>
              <div style="font-family: '楷体'"
                   @click="typeface='楷体'">楷体</div>
            </li>
            <li>
              <div style="font-family: '宋体'"
                   @click="typeface='宋体'">宋体</div>
            </li>
            <li>
              <div style="font-family: '黑体'"
                   @click="typeface='黑体'">黑体</div>
            </li>

          </ul>
        </div>

        <div class="font_family">

        </div>
      </div>
    </transition>

    <div v-if="data"
         class="all"
         ref="all"
         :style="{ backgroundColor: backColor,color: color,fontFamily: typeface, fontSize: size + 5 + 'px'}">
      <h1 style="text-align:center">第{{data.section_num}}章 {{data.section_name}}</h1>
      <div class="content"
           @click="module_show"
           v-html="data.section_content"></div>
      <van-divider :style="{ color: '#1989fa', borderColor: '#1989fa',fontFamily: typeface, padding: '0 16px' }">
        本章完
      </van-divider>
      <div class="bottom">
        <span>
          <van-button plain
                      block
                      hairline
                      type="info"
                      :style="{ backgroundColor: backColor,fontFamily: typeface}"
                      @click="last()">上一章</van-button>
        </span>
        <span>
          <van-button plain
                      block
                      hairline
                      type="info"
                      :style="{ backgroundColor: backColor,fontFamily: typeface}"
                      @click="showcatalog">
            <van-icon name="label-o" />目录
          </van-button>
        </span>
        <span>
          <van-button plain
                      block
                      hairline
                      type="info"
                      :style="{ backgroundColor: backColor,fontFamily: typeface}"
                      @click="next()">下一章</van-button>
        </span>
      </div>
    </div>
    <div v-else
         class="empty"
         @click="show_option = !show_option; progress = false;">
      <van-empty description="加载中...."
                 style="height:90vh" />
    </div>

  </div>
</template>

<script>
import { TABBARSHOW } from '@/type'
import axios from 'axios'
import { Toast, Button, Icon, Divider, NavBar, Empty, Slider, Tabbar, TabbarItem } from 'vant'

export default {
  data () {
    return {
      book_id: '',  //书籍id
      student_id: '', //学生id
      catalog: [],  //目录数据
      data: '', //当前章节数据

      show_option: false,  //显示选项栏
      show: false,  //显示目录
      progress: false,  //显示进度条
      set: false, //显示设置功能块
      nightmode: false, //夜间模式
      backColor: 'white',  //背景色
      color: 'black',
      typeface: '', //字体样式
      active: 0, //底部选项栏

      num: '',  //当前第几章
      value: 0,  //进度条的值
      size: 5, //字体大小
      bgc: ''
    }
  },
  watch: {
    nightmode (newn, oldn) {
      if (newn) {
        this.backColor = 'black';
        this.color = 'white';
      } else {
        this.color = 'black';
        this.backColor = 'white';
      }
    },
    backColor (newV, oldV) {
      this.$refs.tabbar.$el.style.backgroundColor = `${newV} !important`
      this.bgc = `${newV} !important`
    }
  },
  methods: {
    back () { this.$router.back(-1) },
    toread (num) {
      this.getbookcontent(num);
      this.num = num;
      this.show = false;
    },
    getbookcontent (num) {
      axios({
        method: 'post',
        url: '/api/book/bookread',
        data: {
          book_id: this.book_id,
          num: num,
          student_id: this.student_id
        }
      }).then(res => {
        this.data = res.data.data;
        this.num = res.data.data.section_num;
        if (res.data.code == 1) {
          this.getcatalog();
          //返回顶部
          document.body.scrollTop = 0
          document.documentElement.scrollTop = 0
        }
      })
    },
    last () {
      if (this.num - 1 <= 0) {
        Toast('当前为第一章!');
        return false;
      }
      this.num--;
      this.getbookcontent(this.num)
    },
    next () {
      if (this.num >= this.catalog.length) {
        Toast('恭喜你,阅读完所有章节!');
        this.$router.back(-1)
      }
      this.num++;
      this.getbookcontent(this.num)
    },
    showcatalog () {
      this.show = !this.show;
      if (this.catalog.length === 0) {
        this.getcatalog();
      }
    },
    getcatalog () {
      axios({
        method: 'post',
        url: '/api/book/catalog',
        data: {
          book_id: this.data.book_id,
        }
      }).then(res => {
        this.catalog = res.data;
        this.value = Math.ceil((this.num - 1) / res.data.length * 100 + 1); //初始化进度条%
        this.$refs.section_num.innerHTML = '第' + this.num + '章 ' + this.catalog[this.num - 1].section_name;
      })
    },
    onChange (value) {
      //进度条拖动结束时
      let n = Math.floor((this.catalog.length) * (value / 100));
      this.num = parseInt(this.catalog[n]['section_num']);
      this.getbookcontent(this.num);
    },
    change_pro (value) {
      //进度条拖动时
      let n = Math.floor((this.catalog.length) * (value / 100));
      this.num = this.catalog[n]['section_num'];
      this.$refs.section_num.innerHTML = '第' + this.num + '章 ' + this.catalog[n]['section_name'];
    },
    reduce_size () {
      if (parseInt(this.size - 1) >= 1) { this.size-- }
    },
    add_size () {
      if (parseInt(this.size + 1) <= 10) { this.size++ }
    },
    module_show () {
      this.show_option = !this.show_option;
      this.progress = false;
      this.set = false;
    },
  },
  mounted () {
    this.$store.commit(TABBARSHOW, false)
    this.book_id = this.$route.params.id;
    this.num = this.$route.params.num;
    this.student_id = JSON.parse(localStorage.getItem('admin'))['id'];
    this.getbookcontent(this.num);

  }
}
</script>
<style >
</style>
<style scoped>
.van-tabbar-item--active {
  color: #1989fa;
  background-color: transparent;
}
.black {
  background-color: black;
}
.white {
  background-color: white;
}
.brown {
  background-color: #f3ead2;
}
.green {
  background-color: #e6f0e6;
}
.gray {
  background-color: #e1e1e1;
}
</style>

<style scoped>
.empty {
  height: 100vh;
}
.option_bottom {
  background-color: #fff;
  z-index: 3;
  width: 100%;
  height: 5em;
  display: flex;
  position: fixed;
  left: 0;
  bottom: -1px;
}
.option_bottom li {
  flex: 1;
  text-align: center;
  height: 5em;
  line-height: 5em;
  color: #1989fa;
}
.progress {
  z-index: 4;
  width: 100%;
  height: 12em;
  position: fixed;
  left: 0;
  bottom: 0em;
  background-color: #fff;
  box-shadow: 0px 0px 2px black;
}
.pro_top {
  line-height: 0.5em;
  width: 90%;
  margin: 2em auto 2em auto;
  text-align: center;
  font-size: 1.4em;
}
.set {
  z-index: 4;
  width: 100%;
  height: 15em;
  position: fixed;
  left: 0;
  bottom: 0em;
  background-color: #fff;
  box-shadow: 0px 0px 3px #000;
}
.font_size {
  /* border-bottom: 1px solid; */
  width: 100%;
  height: 5em;
  line-height: 5em;
  text-align: center;
}
.font_size span {
  font-size: 2em;
  width: 20%;
  text-align: center;
}
.size_pro {
  width: 60%;
  height: 5em;
  line-height: 5em;
  text-align: center;
  margin: 0 auto;
  position: relative;
}
.size_pro div {
  width: 100%;
  height: 2px;
  position: absolute;
  top: 12px;
  left: 0;
}
.back_color {
  width: 100%;
  height: 5em;
  /* border: 1px solid; */
}
.back_name {
  float: left;
  width: 20%;
  height: 3.5em;
  text-align: center;
  line-height: 3.5em;
  font-size: 1.5em;
  /* border: 1px solid; */
}
.back_color ul {
  /* border: 1px solid; */
  width: 80%;
  height: 5em;
  margin: 0 0 0 auto;
  display: flex;
}
.back_color ul li {
  flex: 1;
  height: 5em;
  /* border: 1px solid; */
}
.back_color ul li div {
  margin: 1em auto;
  width: 5em;
  height: 3em;
  border-radius: 5px;
  border: 1px solid;
}

.typeface {
  width: 100%;
  height: 5em;
  /* border: 1px solid; */
  position: relative;
}
.typeface_name {
  position: absolute;
  left: 0;
  top: 0;
  width: 20%;
  height: 3.5em;
  text-align: center;
  line-height: 3.5em;
  font-size: 1.5em;
  /* border: 1px solid; */
}
.typeface ul {
  /* border: 1px solid; */
  width: 80%;
  height: 5em;
  position: absolute;
  right: 0;
  top: 0;
  display: flex;
}

.typeface ul li {
  flex: 1;
  height: 5em;
  /* border: 1px solid; */
}
.typeface ul li div {
  margin: 1em auto;
  width: 3em;
  height: 3em;
  text-align: center;
  line-height: 1.3em;
  font-size: 1.7em;
  /* border-radius: 25px;
  border: 1px solid; */
}

.read {
  width: 100%;
  height: 100%;
}
.all {
  padding-top: 0.9em;
}
.content {
  width: 90%;
  /* padding: 0 5%; */
  margin: 0em auto;
  font-size: 1.5em;
  word-break: break-all;
}
.bottom {
  width: 100%;
  display: flex;
}
.bottom span {
  flex: 1;
}
</style>

<style>
.content img {
  width: 100%;
  height: auto;
  display: block;
}
</style>

