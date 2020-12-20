<template>
  <div class="exe">
    <van-nav-bar title="每日一练"
                 left-text="退出"
                 right-text="选择科目"
                 fixed
                 placeholder
                 left-arrow
                 @click-left="onClickLeft"
                 @click-right="showPicker = true" />
    <van-popup v-model="showPicker"
               position="bottom">
      <van-picker show-toolbar
                  :columns="$store.state.Subject"
                  @confirm="onConfirm"
                  @cancel="showPicker = false" />
    </van-popup>

    <div v-if="value">
      <van-cell>
        <p class="center">当前科目:{{this.value}}(共 {{judge_len}} 题)</p>
      </van-cell>
      <van-cell-group v-if="type==0? true:false">
        <van-cell>
          {{i+1}}、题目: {{topic[type][ind].single_name}}()
          <van-radio-group v-model="radio">
            <van-cell>
              <van-radio name="A"
                         shape="round">A.{{topic[type][ind].A}}</van-radio>
            </van-cell>
            <van-cell>
              <van-radio name="B"
                         shape="round">B.{{topic[type][ind].B}}</van-radio>
            </van-cell>
            <van-cell>
              <van-radio name="C"
                         shape="round">C.{{topic[type][ind].C}}</van-radio>
            </van-cell>
            <van-cell>
              <van-radio name="D"
                         shape="round">D.{{topic[type][ind].D}}</van-radio>
            </van-cell>
          </van-radio-group>
        </van-cell>
      </van-cell-group>
      <van-cell-group v-if="type==1? true:false">
        <van-cell>
          {{i+1}}、题目: {{topic[type][ind].selection_name}}
        </van-cell>
        <van-checkbox-group v-model="checked">
          <van-cell>
            <van-checkbox name="A"
                          shape="square">A.{{topic[type][ind].A}}</van-checkbox>
          </van-cell>
          <van-cell>
            <van-checkbox name="B"
                          shape="square">B.{{topic[type][ind].B}}</van-checkbox>
          </van-cell>
          <van-cell>
            <van-checkbox name="C"
                          shape="square">C.{{topic[type][ind].C}}</van-checkbox>
          </van-cell>
          <van-cell>
            <van-checkbox name="D"
                          shape="square">D.{{topic[type][ind].D}}</van-checkbox>
          </van-cell>
        </van-checkbox-group>
      </van-cell-group>
      <van-cell-group v-if="type==2? true:false">
        <van-cell>
          {{i+1}}、题目: {{topic[type][ind].judge_name}}
        </van-cell>
        <van-radio-group v-model="check">
          <van-cell>
            <van-radio name="1"
                       shape="round">对</van-radio>
          </van-cell>
          <van-cell>
            <van-radio name="0"
                       shape="round">错</van-radio>
          </van-cell>
        </van-radio-group>
      </van-cell-group>

      <div v-if="is_show"
           class="right_key"
           @click="is_show = false">正确答案:{{topic[type][ind].right_key==1? '对':topic[type][ind].right_key==0? '错':topic[type][ind].right_key}}</div>
      <div v-else
           class="right_key"
           @click="is_show = true">正确答案: xxx(点击显示答案)</div>

      <div class="but">
        <span>
          <van-button plain
                      block
                      type="info"
                      @click="last">上一题</van-button>
        </span>
        <span>
          <van-button plain
                      block
                      type="info"
                      @click="next">下一题</van-button>
        </span>
      </div>
    </div>
    <div v-else>
      <van-empty description="先选择科目" />
    </div>

  </div>
</template>

<script>
import { NavBar, Form, Empty, Cell, CellGroup, Button, Toast } from 'vant';
import axios from 'axios';
import { TABBARSHOW } from '@/type'

export default {
  data () {
    return {
      value: '',  //选择科目值
      showPicker: false,
      topic: [],  //题目
      single_len: 0, //单选题长度
      seletcion_len: 0, //单到多选题长度
      judge_len: 0, //单到判断题长度
      i: 0, //第几题
      type: 0,  //题型
      ind: 0, //当前题型中的第几题
      radio: '',  //单选答案
      checked: [], //多选答案
      check: '', //判断答案
      answer: [[], [], []], //学生答题卡
      is_show: false, //是否显示正确答案
    };
  },
  watch: {

    i (newi, oldi) {
      //给答题卡赋值
      if (this.type == 0) {
        this.answer[this.type][this.ind] = this.radio;
        this.radio = '';
      } else if (this.type == 1) {
        this.answer[this.type][this.ind] = this.checked;
        this.checked = [];
      } else {
        this.answer[this.type][this.ind] = this.check;
        this.check = '';
      }
      //更新数据
      if (newi < this.single_len) {
        //单选题
        this.type = 0;
        this.ind = newi - 0;
      } else if (newi >= this.single_len && newi < this.seletcion_len) {
        //多选题
        this.type = 1;
        this.ind = newi - this.single_len;
      } else if (newi >= this.seletcion_len && newi < this.judge_len) {
        //多选题
        this.type = 2;
        this.ind = newi - this.seletcion_len;
      } else {
        //操作题
        this.type = 3;
        this.ind = newi - this.judge_len;
      }
      //如果答题卡有当前题目的答案,就获取
      // console.log(this.answer)
      if (this.answer[this.type][this.ind]) {
        if (this.type == 0) {
          this.radio = this.answer[this.type][this.ind];
          this.is_show = true;  //显示答案
        }
        if (this.type == 1) {
          this.checked = this.answer[this.type][this.ind];
          if (this.answer[this.type][this.ind].length == 0) { this.is_show = false; }
          else { this.is_show = true; }
        }
        if (this.type == 2) {
          this.check = this.answer[this.type][this.ind];
          this.is_show = true;  //显示答案
        }

      } else {
        //没有的话
        this.is_show = false; //隐藏答案
      }
    }
  },
  beforeDestroy () { this.$store.commit(TABBARSHOW, true); },
  mounted () {
    this.$store.commit(TABBARSHOW, false);
    this.$store.dispatch("GetSubject");
  },
  methods: {
    onClickLeft () {
      this.$router.push('/index');
    },
    onConfirm (value) {

      //获取练习题
      let admin = JSON.parse(localStorage.getItem('admin'));
      axios({
        method: 'post',
        url: '/api/index/question',
        data: {
          major_id: admin['major_id'],
          subject_name: value
        }
      }).then(res => {
        Object.assign(this.$data, this.$options.data())
        this.topic = res.data;
        this.single_len = res.data[0].length;
        this.seletcion_len = this.single_len + res.data[1].length;
        this.judge_len = this.seletcion_len + res.data[2].length;
        this.value = value;
        this.showPicker = false;
      })
    },
    last () {
      if (this.i == 0) {
        Toast('当前为第1题');
        return false;
      }
      this.i--;
    },
    next () {
      if (this.i >= this.judge_len - 1) {
        Toast('已经是最后一题了');
        return false;
      }
      this.i++;
    }
  },
}
</script>

<style scoped>
.exe {
  height: 100vh;
  background-color: #fff;
}

.center {
  text-align: center;
}
.but {
  width: 100%;
  display: flex;
  position: fixed;
  left: 0;
  bottom: 0;
}
.but span {
  flex: 1;
}
.right_key {
  position: absolute;
  right: 1em;
  bottom: 4em;
  font-size: 18px;
}
</style>