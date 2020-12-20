<template>
  <div id="selectfalse">
    <div>
      <van-button type="danger"
                  plain
                  @click="sel_false">查看错题</van-button>
    </div>
    <div>
      <van-circle v-model="currentRate"
                  :rate="sum"
                  :speed="70"
                  color="red"
                  layer-color="#ebedf0"
                  :stroke-width="60"
                  size="140px"
                  :text="text" />
    </div>
    <div>
      <van-button type="info"
                  plain
                  @click="sel_rank">排行榜</van-button>
    </div>
  </div>
</template>

<script>
import { Circle, Button, Toast } from 'vant';
export default {
  props: ["sum"],
  data () {
    return {
      currentRate: 0,
    };
  },
  computed: {
    text () {
      return '正确率: ' + this.sum + '%';
    },
  },
  methods: {
    sel_false () {
      this.$router.push('/falseexam');
    },
    sel_rank () {
      this.$router.push('/rankinglist');
    }
  }
}
</script>

<style scoped>
#selectfalse {
  height: 15em;
  width: 100%;
  display: flex;
}
#selectfalse div {
  flex: 1;
  text-align: center;
  line-height: 15em;
}
</style>