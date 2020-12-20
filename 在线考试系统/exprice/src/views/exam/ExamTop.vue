<template>
  <div id="top">
    <van-nav-bar title="考试中.."
                 left-text="退出"
                 left-arrow
                 right-text="提交"
                 placeholder
                 @click-left="stop"
                 @click-right="submit" />

    <van-progress style="z-index:2"
                  v-if="progress"
                  :percentage="progress>100?100:progress" />
    <van-cell placeholder>
      <p class="num">当前第 {{nowSum}} 题,共 {{allSum}} 题</p>
    </van-cell>
    <van-cell style="margin:0em auto">
      <slot></slot>
    </van-cell>
  </div>
</template>

<script>
import { Progress, CountDown, Dialog, NavBar, Cell, CellGroup } from 'vant';

export default {
  props: ["progress", "allSum", "nowSum", "subject_name"],
  methods: {
    submit () {
      this.$emit('submit')
    },
    stop () {
      Dialog.confirm({
        title: '正在考试中',
        message: '您确定要退出吗?',
      })
        .then(() => {
          //退出考试,清除答案
          localStorage.removeItem(this.subject_name + '_answer');
          localStorage.removeItem(this.subject_name);
          localStorage.removeItem('i');
          localStorage.removeItem(this.subject_name + '_time');
          this.$router.push('/index');
        })
        .catch(() => {
          // on cancel
        });

    }
  }
}
</script>

<style scoped>
#top {
  width: 100%;
  text-align: center;
}
.stop {
  color: red;
  font-size: 1.5em;
}
.num {
  text-align: center;
  margin: 0;
}
</style>