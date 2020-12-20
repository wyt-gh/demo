<template>
  <div class="other_video">
    <ul>
      <li v-for="(data,index) in videoList"
          :key="index"
          @click="to_videoDetail(index)">
        <div class="info_top">
          <p class="public_hidden"
             style="width:90%;margin-top:0">{{data.data.title}}</p>
        </div>

        <div class="info_bottom">
          <p class="public_hidden"
             style="width:10em;margin-bottom:3px">{{data.data.author.description}}</p>
          <p style="margin:1px 1em 1px 0">
            <span style="margin-right:2em">
              <van-icon name="video-o" />{{data.data.duration}}
            </span>
            <span>
              <van-icon name="smile-comment-o" />{{data.data.consumption.replyCount}}
            </span>
          </p>
        </div>

        <div class="img_box">
          <img :src="data.data.cover.detail"
               class="img">
        </div>

        <van-icon name="ellipsis"
                  size="20"
                  class="video_option"
                  @click="show_option(index)" />
      </li>
    </ul>
    <van-action-sheet v-model="show"
                      get-container="body"
                      :actions="actions"
                      cancel-text="取消"
                      @select="select"
                      close-on-click-action />
  </div>
</template>

<script>
import { Toast } from 'vant';
// import { Icon, Toast, ActionSheet } from 'vant';

export default {
  props: ['active', 'index'],
  data () {
    return {
      videoList: '',
      checked_index: '',  //预备 删除或其他选项 的数据下标
      show: false,
      actions: [
        { name: '不感兴趣', color: 'gray' },
        { name: '删除', color: '#ee0a24' },
        { name: '加入收藏夹', subname: '点击收藏' },
      ],
    }
  },
  methods: {
    to_videoDetail (index) {
      let ind = Math.floor(this.index / 10);
      ind = ind + index;
      this.$emit('to_videoDetail', ind);
    },
    show_option (index) {
      this.checked_index = index;
      this.show = true;
    },
    select (item) {
      if (item.name == '删除') {
        let ind = Math.floor(this.index / 10) + this.checked_index;
        this.$store.state.VideoSource[this.active].splice(ind, 1);
        this.videoList.splice(this.checked_index, 1);
        Toast('已删除');
      } else if (item.name == '不感兴趣') {
        Toast('尽量减少此类推荐!');
      } else if (item.name == '加入收藏夹') {
        Toast('已收藏!');
      }
    }
  },
  mounted () {
    let ind = Math.floor(this.index / 10);
    this.videoList = this.$store.state.VideoSource[this.active].slice(ind * 10, ind * 10 + 10);
  }
}
</script>

<style scoped>
.public_hidden {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>

<style scoped>
.public_hidden {
  width: 10em;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>

<style scoped>
.other_video {
  width: 100%;
}
.other_video ul {
  width: 94%;
  margin: 1em auto;
}
.other_video ul li {
  width: 100%;
  padding: 0.5em 0;
  position: relative;
  /* border: 1px solid; */
}
.img_box {
  width: 40%;
  height: 100%;
  /* border: 1px solid red; */
  /* float: left; */
}
img {
  width: 90%;
  height: auto;
  border-radius: 5px;
}
.info_box {
  width: 60%;
  /* height: auto; */
  float: right;
  border: 1px solid black;
}
.info_top {
  position: absolute;
  top: 0.5em;
  left: 40%;
}
.info_bottom {
  position: absolute;
  bottom: 0.5em;
  left: 40%;
}
.video_option {
  transform: rotateZ(90deg);
  position: absolute;
  bottom: 10px;
  right: 0;
}
</style>