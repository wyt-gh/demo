<template>
  <div class="bottom">
    <span>
      <van-button type="info"
                  @click="back">上一题</van-button>
    </span>
    <span>
      <van-button type="info"
                  plain
                  block
                  @click="sel">答题卡</van-button>
    </span>
    <span>
      <van-button type="info"
                  @click="go">下一题</van-button>
    </span>
  </div>
</template>

<script>
import { Toast } from 'vant';

export default {
  methods: {
    go () {
      this.$emit('go')
    },
    back () {
      this.$emit('back')
    },
    sel () {
      this.$emit('showPopup')
    }
  }
}
</script>

<style scoped>
.bottom {
  width: 100%;
  height: 5em;
  display: flex;
  position: fixed;
  bottom: 3em;
  left: 0;
  /* border: 1px solid; */
}
.bottom span {
  flex: 1;
  /* border: 1px solid; */
  text-align: center;
}
</style>