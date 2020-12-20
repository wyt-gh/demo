<template>
  <div id="main">
    <van-nav-bar title="查看出错题目"
                 left-text="返回"
                 left-arrow
                 @click-left="onClickLeft"
                 style="border-bottom:1px solid #1989fa" />
    <p></p>
    <div class="topic">
      <!-- 单选题 -->
      <span class="single"
            v-if="i < $store.state.Single.length">
        <p></p>
        <van-cell>
          第{{i + 1}}题 : {{$store.state.Single[i].single_name}} (单选题)
        </van-cell>
        <P></P>
        <van-cell>
          <van-radio-group v-model="$store.state.Single[i].answer"
                           disabled>
            <van-radio name="A"
                       shape="round">A.{{$store.state.Single[i].A}}</van-radio><br>
            <van-radio name="B"
                       shape="round">B.{{$store.state.Single[i].B}}</van-radio><br>
            <van-radio name="C"
                       shape="round">C.{{$store.state.Single[i].C}}</van-radio><br>
            <van-radio name="D"
                       shape="round">D.{{$store.state.Single[i].D}}</van-radio><br>
          </van-radio-group>
          <p></p>
          <van-cell :value="'您的答案: ' + $store.state.Single[i].answer" />
          <van-cell :value="'正确答案: ' + $store.state.Single[i].right_key" />
        </van-cell>
      </span>
      <!-- 多选题 -->
      <span class="selection"
            v-if="i >= $store.state.Single.length && i < ($store.state.Sin_sel_len)">
        <p></p>
        <van-cell>
          第{{i + 1}}题 : {{$store.state.Selection[i - $store.state.Single.length].selection_name}} (多选题)
        </van-cell>
        <P></P>
        <van-cell>

          <van-checkbox-group v-model="$store.state.Selection[i - $store.state.Single.length].answer"
                              disabled>
            <van-checkbox name="A"
                          shape="square">A.{{$store.state.Selection[i - $store.state.Single.length].A}}</van-checkbox><br>
            <van-checkbox name="B"
                          shape="square">B.{{$store.state.Selection[i - $store.state.Single.length].B}}</van-checkbox><br>
            <van-checkbox name="C"
                          shape="square">C.{{$store.state.Selection[i - $store.state.Single.length].C}}</van-checkbox><br>
            <van-checkbox name="D"
                          shape="square">D.{{$store.state.Selection[i - $store.state.Single.length].D}}</van-checkbox><br>
          </van-checkbox-group>
        </van-cell>
        <van-cell>您的答案: {{$store.state.Selection[i - $store.state.Single.length].myanswer}}</van-cell>
        <van-cell>正确答案: {{$store.state.Selection[i - $store.state.Single.length].right_key}}</van-cell>
      </span>
      <!-- 判断题 -->
      <span class="judge"
            v-if="i >= $store.state.Sin_sel_len && i < $store.state.Sin_sel_jud_len">
        <van-cell>
          第{{i + 1}}题 : {{$store.state.Judge[i - $store.state.Sin_sel_len].judge_name}} (判断题)
        </van-cell>
        <p></p>
        <van-cell>
          <van-radio-group v-model="$store.state.Judge[i - $store.state.Sin_sel_len].answer"
                           disabled>
            <van-radio name="1"
                       shape="round">对</van-radio><br>
            <van-radio name="0"
                       shape="round">错</van-radio><br>
          </van-radio-group>
        </van-cell>
        <p></p>
        <van-cell>您的答案: {{$store.state.Judge[i - $store.state.Sin_sel_len].answer=='未填'? '未填':$store.state.Judge[i - $store.state.Sin_sel_len].answer==1? '对':'错'}}</van-cell>
        <van-cell>正确答案: {{$store.state.Judge[i - $store.state.Sin_sel_len].right_key==1? '对':'错'}}</van-cell>
      </span>

    </div>

    <div class="button_box">
      <span>
        <van-button plain
                    type="info"
                    @click="last">上一题</van-button>
      </span>
      <span>
        <van-button plain
                    type="info"
                    @click="next">下一题</van-button>
      </span>

    </div>
  </div>
</template>

<script>
import { NavBar, Button, Cell, CellGroup, Toast } from 'vant';
export default {
  data () {
    return {
      i: 0,
      s: ["A", "B", "C"],
    }
  },
  mounted () {
    this.$store.dispatch("GetFalseTopicList");
  },
  methods: {
    onClickLeft () {
      this.$router.push('/select')
    },
    last () {
      if (this.i >= 1) {  //i为0没有上一题
        this.i--;
      } else {
        Toast({
          message: '当前为第1题!',
          position: 'bottom',
        });
      }
    },
    next () {
      if (this.i < (this.$store.state.Single.length + this.$store.state.Selection.length + this.$store.state.Judge.length - 1)) {
        this.i++;
      } else {
        Toast({
          message: '没有下一题了!',
          position: 'bottom',
        });
      }
    },
  },
}
</script>

<style scoped>
#main {
  width: 100%;
  margin-bottom: 10em;
}

.button_box {
  width: 100%;
  display: flex;
  position: fixed;
  bottom: 4em;
  left: 0;
}
.button_box span {
  flex: 1;
  text-align: center;
}
</style>