<template>
  <div class="movieMain">
    <van-tabs title-active-color="#1989fa"
              sticky
              animated
              color="#1989fa">
      <van-tab v-for="(data,index) in $store.state.Subject"
               :key="index"
               :title="data">
        <div class="moviebox"
             v-for="(da,ind) in videolist"
             :key="ind">
          <div class="user">
            <div>
              <img src="https://img.yzcdn.cn/vant/cat.jpeg"
                   alt="未加载">
            </div>
            <div class="username">王某某</div>
            <div class="option"
                 @click="option(ind)">
              <van-icon name="close"
                        size="2em" />
            </div>
          </div>
          <div class="movie">
            <video :src="da"
                   controls
                   style="width:100%;height:100%"></video>
          </div>
        </div>
        <div style="text-align:center;">
          <van-loading v-if="loading"
                       color="#1989fa" />
          <p @click="addvideo"
             v-else>加载...</p>

        </div>

      </van-tab>
    </van-tabs>
  </div>
</template>

<script>
import axios from 'axios'
import { Tab, Tabs, Cell, CellGroup, Image as VanImage, Icon, Loading } from 'vant';

export default {
  data () {
    return {
      videolist: [],  //总长度
      num: 0, //从第几条开始加载
      number: 5,  //一次加载几条
      loading: false,  //是否处于加载中
    }
  },
  mounted () {
    this.$store.dispatch('GetSubject');
    if (this.videolist.length === 0) { this.getvideo() }

  },
  methods: {
    option (index) {
      this.videolist.splice(index, 1)
    },
    getvideo () {
      this.num = this.num + this.number;
      axios({
        method: 'post',
        url: "/api/video/videolist",
        data: {
          num: parseInt(this.num - this.number),
          number: parseInt(this.number)
        }
      }).then(res => {
        res.data.forEach(element => {
          this.videolist.push(element);
        });
        this.loading = false;
      })
    },
    addvideo () {
      this.loading = true;
      this.getvideo()
    }
  }
}
</script>

<style scoped>
.movieMain {
  width: 100%;
  /* height: 100vh; */
  margin-bottom: 7em;
}
.moviebox {
  width: 100%;
  height: 25em;
  /* margin-bottom: 1em; */
  border-bottom: 0.8em solid #1989fa;
}
.user {
  height: 4em;
  border-top: 1px solid #1989fa;
}
img {
  width: 3em;
  height: 3em;
  border-radius: 50%;
  margin: 0.5em;
  float: left;
}
.username {
  float: left;
  font-size: 1.5em;
  margin: 0.75em 0.5em 0 0.5em;
}
.option {
  float: right;
  margin: 0.5em 1em;
  line-height: 4em;
}
.movie {
  width: 100%;
  height: 21em;
  margin-top: 0;
  position: relative;
}
.movie video {
  position: absolute;
  background-color: black;
}
</style>