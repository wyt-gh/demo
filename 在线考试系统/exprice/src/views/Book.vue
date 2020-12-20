<template>
  <div class="shop">
    <van-nav-bar title="书城"
                 fixed
                 placeholder
                 @click-left="tobookrack"
                 @click-right="tosearch"
                 class="bot_bor">

      <template #left>
        <van-icon name="goods-collect-o"
                  size="25px" />
      </template>
      <template #right>
        <van-icon name="search"
                  size="22px" />
      </template>
    </van-nav-bar>
    <!-- 轮播图 -->
    <BookSwiper></BookSwiper>
    <!-- 导航栏 -->
    <BookOption></BookOption>
    <!-- 推荐书籍 -->
    <div class="main">
      <div class="box">
        <!-- 标题 -->
        <van-cell>
          <template #title>
            <span class="fs">热门推荐</span>
            <van-tag type="danger">热</van-tag>
          </template>
          <span @click="changehot">
            <van-icon name="replay" />换一换
          </span>
        </van-cell>
        <!-- 内容 -->
        <van-grid v-if="$store.state.Hot"
                  :column-num="4">
          <van-grid-item v-for="(da,ind) in $store.state.Hot"
                         :key="ind"
                         @click="toread(da.id,da.subject_id)"
                         id="bookbox"
                         style="padding:0">
            <img class="img"
                 :src="da.cover_img"
                 alt="未加载">
            <p>{{da.book_name}}</p>
          </van-grid-item>
        </van-grid>
      </div>
      <div class="box">
        <!-- 标题 -->
        <van-cell>
          <template #title>
            <span class="fs">新书推荐</span>
          </template>
          <span @click="changenew">
            <van-icon name="replay" />换一换
          </span>
        </van-cell>
        <!-- 内容 -->
        <van-grid v-if="$store.state.New"
                  :column-num="4">
          <van-grid-item v-for="(da,ind) in $store.state.New"
                         :key="ind"
                         @click="toread(da.id,da.subject_id)"
                         id="bookbox">
            <img class="img"
                 :src="da.cover_img"
                 alt="未加载">
            <p>{{da.book_name}}</p>
          </van-grid-item>
        </van-grid>
      </div>
      <div class="box">
        <!-- 标题 -->
        <van-cell>
          <template #title>
            <span class="fs">免费推荐</span>
            <van-tag type="success ">免费</van-tag>
          </template>
          <span @click="changefree">
            <van-icon name="replay" />换一换
          </span>
        </van-cell>
        <!-- 内容 -->
        <van-grid v-if="$store.state.Free"
                  :column-num="4">
          <van-grid-item v-for="(da,ind) in $store.state.Free"
                         :key="ind"
                         @click="toread(da.id,da.subject_id)"
                         id="bookbox">
            <img class="img"
                 :src="da.cover_img"
                 alt="未加载">
            <p>{{da.book_name}}</p>
          </van-grid-item>
        </van-grid>
      </div>
      <div class="box">
        <!-- 标题 -->
        <van-cell>
          <template #title>
            <span class="fs">完本推荐</span>
            <van-tag type="primary ">完本</van-tag>
          </template>
          <span @click="changeend">
            <van-icon name="replay" />换一换
          </span>
        </van-cell>
        <!-- 内容 -->
        <van-grid v-if="$store.state.End"
                  :column-num="4">
          <van-grid-item v-for="(da,ind) in $store.state.End"
                         :key="ind"
                         @click="toread(da.id,da.subject_id)"
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
</template>

<script>
import axios from 'axios'
import { NavBar, Cell, CellGroup, Grid, GridItem } from 'vant';
import { TABBARSHOW } from '@/type'
import BookSwiper from './book/BookSwiper'
import BookOption from './book/BookOption'

export default {
  components: {
    BookSwiper,
    BookOption
  },
  data () {
    return {
    }
  },
  methods: {
    tosearch () {
      this.$router.push('/booksearch');
    },
    tobookrack () {
      this.$router.push('/bookrack');
    },
    changehot () {
      this.$store.dispatch('GetHotBook');
    },
    changenew () {
      this.$store.dispatch('GetNewBook');
    },
    changefree () {
      this.$store.dispatch('GetFreeBook');
    },
    changeend () {
      this.$store.dispatch('GetEndBook');
    },
    toread (id, subject_id) {
      this.$router.push({
        path: `bookdetail/${id}/${subject_id}`
      })
    }
  },
  mounted () {
    this.$store.commit(TABBARSHOW, true);
    if (this.$store.state.Hot.length === 0) { this.$store.dispatch('GetHotBook'); }
    if (this.$store.state.New.length === 0) { this.$store.dispatch('GetNewBook'); }
    if (this.$store.state.Free.length === 0) { this.$store.dispatch('GetFreeBook'); }
    if (this.$store.state.End.length === 0) { this.$store.dispatch('GetEndBook'); }
  }
}
</script>

<style scoped>
.shop {
  width: 100%;
}
.bot_bor {
  border-bottom: 1px solid #39a9ed;
}
.main {
  margin: 0 auto 5em 0;
}
.box {
  margin: 0.5em auto;
}
#bookbox {
  /* margin: 0em 0; */
  padding: -5px 0px;
}
.img {
  width: 7em;
  height: 9em;
  border-radius: 5px;
}
.fs {
  font-size: 1.3em;
  font-weight: bold;
  border-left: 5px solid #39a9ed;
  /* border-radius: 5px; */
  padding-left: 2px;
}
.main p {
  margin-bottom: 0;
}
</style>

