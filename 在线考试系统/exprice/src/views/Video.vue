<template>
  <div class="videoPage">
    <van-search v-model="value"
                shape="round"
                :style="{ width: 100 + '%',zIndex:3, position:'fixed', left:0, top:0}"
                clearable
                show-action
                placeholder="请输入搜索关键词"
                @search="onSearch">
      <template #left
                class="fs2">
        <img src="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2456189512,1128783987&fm=26&gp=0.jpg"
             style="width:5em;height:2.5em;border-radius:5px"
             alt="">
      </template>
      <template #action
                class="fs2">
        <div @click="onSearch">搜索</div>
      </template>
    </van-search>

    <van-tabs v-model="active"
              sticky
              swipeable
              color="#39a9ed"
              title-active-color="#39a9ed"
              @change="navSelect"
              animated
              offset-top="50px">
      <van-tab v-for="(data,index) in videoId"
               :key="index"
               class="list">
        <template #title
                  class="fs2">
          <van-icon :name="tabName[index][0]" />{{tabName[index][1]}}
        </template>
        <div class="single"
             v-for="(item,index) in $store.state.VideoSource[index]"
             :key="index">
          <video :src="item.data? item.data.playUrl:''"
                 :poster="item.data? item.data.cover.detail:''"
                 class="video"
                 @click="video_play1($event)">

          </video>
          <!-- 视频信息 -->
          <van-icon name="play-circle-o"
                    size="3em"
                    color="#fff"
                    class="play_but"
                    @click="video_play2($event)" />
          <div class="title">{{item.data? item.data.title:''}}</div>
          <div style="width:100%;position:relative"
               @click="to_videoDetail(index)">
            <span class="category">类型: {{item.data? item.data.category:''}}</span>

            <span class="video_option"
                  @click.stop="show = true;checked_index = index">
              <van-icon name="ellipsis"
                        size="25" />
            </span>
          </div>

        </div>
      </van-tab>
    </van-tabs>
    <div class="option">
      <van-action-sheet v-model="show"
                        :actions="actions"
                        @select="select"
                        cancel-text="取消"
                        close-on-click-action />
    </div>
    <span v-if="loading"
          class="loading">
      <van-loading size="24px"
                   color="#1989fa">加载中...</van-loading>
    </span>
    <p v-else
       class="loading"
       @click="load">加载..</p>
  </div>
</template>

<script>
import axios from 'axios'
import { Search, Tab, Tabs, Loading, Toast, Icon, ActionSheet } from 'vant';
import { TABBARSHOW } from '@/type'

export default {
  data () {
    return {
      videoId: [
        126990, 127990, 129990, 125990,
      ],
      tabName: [
        ["music", '音乐'],
        ["video", '电影'],
        ["volume", '广播'],
        ["live", '电视'],
      ],
      show: false,
      actions: [
        { name: '不感兴趣', color: 'gray' },
        { name: '删除', color: '#ee0a24' },
        { name: '添加收藏', subname: '收藏夹' },
      ],
      value: '',  //搜索框内容
      active: 0,  //当前标签栏索引值
      loading: false,
      checked_index: '',  //选择目标的下标
    }
  },
  methods: {
    get_videoSource (id) {
      this.loading = true; //开始加载
      axios({
        method: 'post',
        url: 'https://api.apiopen.top/videoRecommend?id=' + id,
      }).then(res => {
        if (this.$store.state.VideoSource[this.active]) {
          let data = this.$store.state.VideoSource[this.active].concat(res.data.result)
          this.$set(this.$store.state.VideoSource, this.active, data)
          // console.log(this.$store.state.VideoSource[this.active])
        } else {
          this.$set(this.$store.state.VideoSource, this.active, res.data.result)
          // console.log(this.$store.state.VideoSource[this.active])
        }
        this.loading = false; //加载完成
      }).catch(err => { })
    },
    navSelect (id) {
      if (!this.$store.state.VideoSource[id]) {
        this.get_videoSource(this.videoId[id])
      }
    },
    load () {
      let id = this.videoId[this.active] + Math.ceil(this.$store.state.VideoSource[this.active].length / 10);
      this.get_videoSource(id)
    },
    onSearch () {
      // Toast(this.value);
      Toast('功能暂未开启');
    },
    to_videoDetail (index) {
      this.$router.push(`/videodetail/${this.active}/${index}`);
    },
    video_play1 (event) {
      let playBut = event.target.nextElementSibling;
      if (event.target.paused) {
        playBut.style.opacity = 0;
        event.target.play();  //播放
      } else {
        playBut.style.opacity = 1;
        event.target.pause(); //暂停
      }
    },
    video_play2 (event) {
      let video = event.target.previousElementSibling;
      if (video.paused) {
        event.target.style.opacity = 0;
        video.play();  //播放
      } else {
        event.target.style.opacity = 1;
        video.pause(); //暂停
      }
    },
    select (item) {
      if (item.name == '删除') {
        this.$store.state.VideoSource[this.active].splice(this.checked_index, 1);
        Toast('已删除');
      } else if (item.name == '不感兴趣') {
        Toast('尽量减少此类推荐!');
      } else if (item.name == '添加收藏') {
        Toast('已收藏!');
      }
    }
  },
  created () {
    this.$store.commit(TABBARSHOW, true);
    if (!this.$store.state.VideoSource[this.active]) {
      this.get_videoSource(this.videoId[this.active]);
    }
  },
  beforeDestroy () {
    this.$store.commit(TABBARSHOW, false);
  },
}
</script>

<style scoped>
.fs2 {
  font-size: 1.2em;
}
</style>
<style scoped>
.videoPage {
  width: 100%;
  margin: 5em auto 7em auto;
}
.list {
  width: 100%;
  margin: 1em auto 0 auto;
  background-color: #f7f8fa;
  /* border: 1px solid; */
}
.single {
  width: 94%;
  margin: 1em auto 1em auto;
  padding: 0.2em 0;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0px 1px 3px gray;
  font-size: 1.5em;
  position: relative;
}
.video {
  width: 100%;
  height: auto;
  border-radius: 5px;
  z-index: 0;
}
.play_but {
  /* z-index: -1; */
  position: absolute;
  top: 34%;
  left: 44%;
}
.title {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
  overflow: hidden;
  /* width: 100%; */
  padding-left: 1em;
  height: 1.5em;
}

.category {
  padding: 0.5em 1em;
  color: gray;
}
.v_detail {
  float: right;
  font-size: 1.2em;
  /* padding: 1em 2em 1em 0; */
}
.video_option {
  float: right;
  margin: -1em 0.5em -1em 0em;
  transform: rotateZ(90deg);
}
.loading {
  text-align: center;
  font-size: 1.5em;
  margin: 0.5em auto;
}
</style>