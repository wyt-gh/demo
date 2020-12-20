<template>
  <div class="videoDetail">
    <!-- 头部 -->
    <van-nav-bar left-arrow
                 fixed
                 placeholder
                 @click-left="back"
                 @click-right="showShare = true">
      <template #title>
        <b>{{data.title}}</b>
      </template>
      <template #right>
        <van-icon name="ellipsis"
                  size="18"
                  style="transform: rotateZ(90deg);" />
      </template>
    </van-nav-bar>
    <!-- 分享面板 -->
    <van-share-sheet v-model="showShare"
                     title="立即分享给好友"
                     :options="options"
                     @select="onSelect" />
    <!-- 视频标签 -->
    <video class="video"
           ref="video"
           autoplay
           controls
           :poster="data.cover.detail"
           :src="data.playUrl"></video>
    <!-- 标签页 -->
    <van-tabs v-model="active"
              swipeable
              replace
              sticky
              :offset-top="videoHeight"
              animated
              color="#39a9ed"
              title-active-color="#39a9ed">
      <!-- 内容页1 -->
      <van-tab title="简介">
        <div class="description"
             :style="{ marginTop: videoHeight - 44 +'px'}">
          <van-cell center
                    style="padding-top:2px;padding-bottom:2px;">
            <!-- 使用 title 插槽来自定义标题 -->
            <template #title>
              <div class="line_cenrer">
                <img :src="data.cover.blurred"
                     class="user_img">
                <div>
                  <p class="a_description">{{data.author.description}}</p>
                  <p style="margin:1px 5px;font-size:0.8em;color:gray">粉丝数: {{data.id}}</p>
                </div>
              </div>
            </template>
            <van-button icon="plus"
                        size="small"
                        plain
                        type="info">关注</van-button>
          </van-cell>
          <van-collapse v-model="activeNames">
            <van-collapse-item name="1">
              <template #title>
                <div>
                  <span class="category">{{data.category}}</span> {{data.title}}
                </div>
              </template>
              <span>简介: {{data.description}}</span>
            </van-collapse-item>
          </van-collapse>
          <!-- 回复/收藏/转发 -->
          <div class="func_module">
            <ul>
              <li @click="handle_like"
                  :style="{color: like? '#1989fa':'gray'}"><span>
                  <van-icon name="good-job"
                            size="20px" />
                </span><br><span>{{data.duration}}</span></li>
              <li @click="handle_nolike"
                  :style="{color: nolike? '#1989fa':'gray'}"><span>
                  <van-icon name="good-job"
                            size="20px"
                            style="transform:rotate(180deg)" />
                </span><br><span>不喜欢</span></li>
              <li @click="handle_collection"
                  :style="{color: collection? '#1989fa':'gray'}"><span>
                  <van-icon name="like"
                            size="20px" />
                </span><br><span>{{data.consumption.collectionCount}}</span></li>
              <li @click="handle_share"
                  :style="{color: share? '#1989fa':'gray'}"><span>
                  <van-icon name="share"
                            size="20px" />
                </span><br><span>{{data.consumption.shareCount}}</span></li>
            </ul>
          </div>
          <!-- 其他视频 -->
          <OtherVideo :active="active_last"
                      :index="index_last"
                      @to_videoDetail="to_videoDetail"></OtherVideo>
        </div>
      </van-tab>

      <!-- 内容页2 -->
      <van-tab :title="'评论' + data.consumption.replyCount">
        <div class="description"
             :style="{ marginTop: videoHeight - 44 +'px'}">
          <Comment></Comment>
        </div>

      </van-tab>

    </van-tabs>
    <transition name="van-slide-up">
      <div class="comment"
           v-show="active==1? true:false">

        <van-search v-model="value"
                    left-icon=""
                    show-action
                    placeholder="发条友善的评论"
                    @search="comment">
          <template #action>
            <span @click="comment">评论</span>
          </template>
        </van-search>

      </div>
    </transition>
  </div>
</template>

<script>
import { NavBar, Toast, Tab, Tabs, Cell, CellGroup, Button, Collapse, CollapseItem } from 'vant';
import { TABBARSHOW } from '@/type'
import OtherVideo from './video/OtherVideo'
import Comment from './video/Comment'

export default {
  components: {
    OtherVideo,
    Comment,
  },
  data () {
    return {
      active_last: 0, //当前视频位于前一个页面的哪个索引栏
      index_last: 0, //当前视频位于前一个页面的索引栏的哪一位
      showShare: false,
      options: [
        { name: '微信', icon: 'wechat' },
        { name: '微博', icon: 'weibo' },
        { name: '复制链接', icon: 'link' },
        { name: '分享海报', icon: 'poster' },
        { name: '二维码', icon: 'qrcode' },
      ],
      active: 2, //标签栏当前索引
      activeNames: ['0'],
      videoHeight: 40, //标签栏吸顶距离

      data: [], //详情信息
      like: false,  //点赞
      nolike: false,  //不喜欢
      collection: false,  //收藏
      share: false,  //分享
      value: '', //用户评论内容
    }
  },
  methods: {
    back () {
      this.$router.back(-1);
    },
    onSelect (option) {
      Toast(option.name);
      this.showShare = false;
    },
    handle_like () {
      this.like = !this.like;
      if (this.like) {
        this.nolike = false;
        this.data.duration += 1;
        Toast('点赞+1');
      } else {
        this.data.duration -= 1;
      }
    },
    handle_nolike () {
      this.nolike = !this.nolike;
      if (this.nolike) {
        this.like = false;
        this.data.duration -= 1;
        Toast('踩+1');
      } else {
        this.data.duration += 1;
      }
    },
    handle_collection () {
      this.collection = !this.collection;
      if (this.collection) {
        this.data.consumption.collectionCount += 1;
        Toast({
          message: '收藏',
          icon: 'like-o',
        });
      } else {
        this.data.consumption.collectionCount -= 1;
        Toast({
          message: '取消收藏',
          icon: 'like-o',
        });
      }

    },
    handle_share () {
      this.share = !this.share;
      if (this.share) {
        this.showShare = true;  //打开分享面板
      }
    },
    to_videoDetail (ind) {
      this.$set(this.data, 0, this.$store.state.VideoSource[this.active_last][ind])
      this.data = this.data[0].data;
    },
    comment () {
      Toast(this.value);
    },
  },
  created () {
    this.$store.commit(TABBARSHOW, false);  //隐藏导航栏
    this.active_last = this.$route.params.active;
    this.index_last = this.$route.params.index;
    //获取相应数据
    this.$set(this.data, 0, this.$store.state.VideoSource[this.active_last][this.index_last])
    this.data = this.data[0].data;
  },
  mounted () {
    //调试标签页吸顶高度
    this.videoHeight = this.videoHeight + this.$refs.video.offsetHeight;
  }
}
</script>

<style scoped>
.videoDetail {
  width: 100%;
  /* background-color: #f7f7f7; */
}
.video {
  width: 100%;
  height: auto;
  position: fixed;
  top: 46px;
  left: 0;
  z-index: 2;
}
.a_description {
  margin: 1px 5px;
  height: 1.5em;
  width: 12em;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.description {
  width: 100%;
}
.line_cenrer {
  display: flex;
  justify-content: start;
  align-items: center;
}
.user_img {
  width: 3em;
  height: 3em;
  border-radius: 50%;
}
.category {
  border-radius: 10px;
  color: red;
  background-color: rgb(247, 213, 246);
  padding: 3px 5px;
  font-size: 0.6em;
}
.func_module {
  width: 100%;
  height: 5em;
  margin-top: 1em;
}
.func_module ul {
  width: 90%;
  height: 5em;
  margin: 0 auto;
  display: flex;
}
.func_module ul li {
  flex: 1;
  height: 3.5em;
  font-size: 1.3em;
  text-align: center;
  /* color: #1989fa; */
  /* border: 1px solid; */
}
.comment {
  z-index: 4;
  width: 100%;
  position: fixed;
  bottom: 0;
  left: 0;
}
</style>