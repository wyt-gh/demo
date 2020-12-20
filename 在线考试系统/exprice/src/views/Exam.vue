<template>
  <div class="exam">
    <ExamTop class="top"
             :progress="progress"
             :allSum="allSum"
             :nowSum="nowSum"
             @submit="sure_submit"
             :subject_name="subject_name">
      <van-count-down :time="time"
                      @finish="timedown"
                      @change="change">
      </van-count-down>
    </ExamTop>

    <div class="main">
      <div v-if="i < r_len">
        <van-cell class="quetype">
          <div class="question">单选题</div>
        </van-cell>
        <van-cell>
          {{Single[ind].single_name}} ()
        </van-cell>
        <van-radio-group v-model="radio">
          <van-cell>
            <van-radio name="A"
                       shape="round">A.{{Single[ind].A}}</van-radio>
          </van-cell>
          <van-cell>
            <van-radio name="B"
                       shape="round">B.{{Single[ind].B}}</van-radio>
          </van-cell>
          <van-cell>
            <van-radio name="C"
                       shape="round">C.{{Single[ind].C}}</van-radio>
          </van-cell>
          <van-cell>
            <van-radio name="D"
                       shape="round">D.{{Single[ind].D}}</van-radio>
          </van-cell>
        </van-radio-group>
      </div>
      <div v-else-if="(i >= r_len) && i < (r_len + s_len)">
        <!-- <div class="question">多选题</div> -->
        <van-cell class="quetype">
          <div class="question">多选题</div>
        </van-cell>
        <van-cell>
          {{ Selection[ind].selection_name }} ()
        </van-cell>
        <van-checkbox-group v-model="checked">
          <van-cell>
            <van-checkbox name="A"
                          shape="square">A.{{Selection[ind].A}}</van-checkbox>
          </van-cell>
          <van-cell>
            <van-checkbox name="B"
                          shape="square">B.{{Selection[ind].B}}</van-checkbox>
          </van-cell>
          <van-cell>
            <van-checkbox name="C"
                          shape="square">C.{{Selection[ind].C}}</van-checkbox>
          </van-cell>
          <van-cell>
            <van-checkbox name="D"
                          shape="square">D.{{Selection[ind].D}}</van-checkbox>
          </van-cell>
        </van-checkbox-group>
      </div>
      <div v-else-if="i >= (r_len + s_len) && i < (r_len + s_len + j_len)">
        <!-- 判断选 -->
        <!-- <div class="question">判断题</div> -->
        <van-cell class="quetype">
          <div class="question">判断题</div>
        </van-cell>
        <van-cell>{{Judge[ind].judge_name}} ()</van-cell>
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
      </div>
      <div v-else-if="i >= (r_len + s_len + j_len) && i < (r_len + s_len + j_len + o_len)">
        <!-- 操作选 -->
        <!-- <div class="question">操作题</div> -->
        <van-cell class="quetype">
          <div class="question">操作题</div>
        </van-cell>
        <van-cell>{{Operation[ind].operation_name}} ()</van-cell>
        <van-cell>
          <form method="post">
            <input type="file"
                   name="file"
                   @change="afterRead($event)" />
          </form>
        </van-cell>
      </div>
    </div>

    <van-popup v-model="show"
               round
               style="width:80%;padding:2%">
      <van-grid :gutter="10">
        <van-grid-item v-for="value in allSum"
                       :key="value"
                       @click="tonum($event)">
          <van-icon :name="ans_card[value-1]? 'success':''"
                    :style="{color: ans_card[value-1]? 'green':'red'}">{{value}}</van-icon>
        </van-grid-item>
      </van-grid>
    </van-popup>

    <ExamBottom @back="back"
                @go="go"
                @showPopup="showPopup"></ExamBottom>
  </div>
</template>

<script>
import axios from 'axios'
import ExamTop from './exam/ExamTop'
import ExamBottom from './exam/ExamBottom'
import { Toast, Grid, GridItem, Popup, RadioGroup, Radio, Checkbox, CheckboxGroup, Field, Dialog, UploaderCell, CellGroup } from 'vant';
import { TABBARSHOW } from '@/type'

export default {
  components: {
    ExamTop,
    ExamBottom
  },
  data () {
    return {
      time: 1.5 * 60 * 60 * 1000, //考试时间
      allSum: 0, //总共几题
      i: 0, //当前第几题
      ind: 0, //ind为下标
      subject_name: '', //当前考试科目名
      Single: [{}], //单选题题目
      Selection: [{}],
      Judge: [{}],
      Operation: {},
      radio: '',  //单选题答案
      r_len: 0,   //单选长
      checked: [], //多选题答案
      s_len: 0,
      check: '',  //判断题答案
      j_len: 0,
      answer: '', //操作题答案
      o_len: 0,
      show: false,
      ans_card: [], //检测答题卡答案是否填写,写为true,没有为false
    }
  },
  computed: {
    progress () {
      let num = parseInt(this.i) + 1;
      return Math.floor((num / this.allSum) * 100);
    },
    nowSum () {
      return (this.i) + 1;
    }
  },
  watch: {
    answer (new_answer, old_answer) {
      if (this.i == this.allSum - 1) {
        //监听最后一题操作题
        let answer = JSON.parse(localStorage.getItem(this.subject_name + '_answer'));
        answer[3][this.o_len - 1] = new_answer;
        localStorage.setItem(this.subject_name + '_answer', JSON.stringify(answer))
      }
    },
    i (newi, oldi) {
      localStorage.setItem('i', newi);
      //更ind
      if (newi < this.r_len) {
        this.ind = newi - 0;
      } else if (newi >= this.r_len && newi < (this.r_len + this.s_len)) {
        this.ind = newi - this.r_len;
      } else if (newi >= (this.r_len + this.s_len) && newi < (this.r_len + this.s_len + this.j_len)) {
        ;
        this.ind = newi - (this.r_len + this.s_len);
      } else {
        this.ind = newi - (this.r_len + this.s_len + this.j_len);
      }
      //获取考生答题卡
      let answer = JSON.parse(localStorage.getItem(this.subject_name + '_answer'));
      // console.log(answer)
      let que1 = this.topicType(oldi);
      let que2 = this.topicType(newi);
      let old_que = que1[0]; //题型
      let old_n = que1[1]; //第几题
      let new_que = que2[0]; //题型
      let new_n = que2[1]; //第几题
      //1.保存当前题目答案
      if (old_que == 0) {
        if (this.radio != '') {
          answer[old_que][old_n] = this.radio //给答题卡赋值
        }
        localStorage.setItem(this.subject_name + '_answer', JSON.stringify(answer));//提交答题卡
        this.radio = '' //清理
      }
      if (old_que == 1) {
        answer[old_que][old_n] = this.checked
        localStorage.setItem(this.subject_name + '_answer', JSON.stringify(answer));//提交答题卡
        this.checked = [] //清理
      }
      if (old_que == 2) {
        answer[old_que][old_n] = this.check
        localStorage.setItem(this.subject_name + '_answer', JSON.stringify(answer));//提交答题卡
        this.check = '' //清理
      }
      if (old_que == 3) {
        answer[old_que][old_n] = this.answer
        localStorage.setItem(this.subject_name + '_answer', JSON.stringify(answer));//提交答题卡
        this.answer = '' //清理
      }
      //2.查看接下来的题目是否已经填写答案,有则使用
      if (answer[new_que][new_n]) {
        //说明已填写过答案
        if (new_que == 0) { this.radio = answer[new_que][new_n] }
        if (new_que == 1) { this.checked = answer[new_que][new_n] }
        if (new_que == 2) { this.check = answer[new_que][new_n] }
        if (new_que == 3) { this.answer = answer[new_que][new_n] }
      }
      let topic = JSON.parse(localStorage.getItem(this.subject_name))
      // answer = JSON.parse(localStorage.getItem(this.subject_name + '_answer'))
      this.ans_card = []; //初始化值
      for (let i = 0; i < topic.length; i++) {
        for (let j = 0; j < topic[i].length; j++) {
          if (i == 1) {
            if (answer[i][j].length == 0) {
              this.ans_card.push(0)
            } else {
              this.ans_card.push(1)
            }
          } else {
            if (answer[i][j] == '未填') {
              this.ans_card.push(0)
            } else {
              this.ans_card.push(1)
            }
          }
        }
      }
      // console.log(this.ans_card)
    }
  },
  beforeDestroy () { this.$store.commit(TABBARSHOW, true); },
  mounted () {
    this.$store.commit(TABBARSHOW, false);
    this.subject_name = this.$route.params.subject_name;
    let topic = JSON.parse(localStorage.getItem(this.subject_name));

    if (topic) {
      //考过
      this.Single = topic[0];
      this.Selection = topic[1];
      this.Judge = topic[2];
      this.Operation = topic[3];
      this.r_len = this.Single.length
      this.s_len = this.Selection.length
      this.j_len = this.Judge.length
      this.o_len = this.Operation.length
      this.allSum = this.r_len + this.s_len + this.j_len + this.o_len;
      Toast('开始考试');

      let answer = JSON.parse(localStorage.getItem(this.subject_name + '_answer'));
      if (answer) {
        this.i = JSON.parse(localStorage.getItem('i'));
        this.ind = this.topicType(this.i)[1];
        //进来将考生已填写过的答题卡答案进行渲染
        this.radio = answer[0][0];
      }
      //渲染之前考试剩余时间
      let time = JSON.parse(localStorage.getItem(this.subject_name + '_time'));
      if (time) {
        this.time = time;
      }

    } else {
      //没考过
      let admin = JSON.parse(localStorage.getItem('admin'));
      //获取题目内容
      axios({
        method: 'post',
        url: '/api/index/question',
        data: {
          major_id: admin['major_id'],
          subject_name: this.subject_name
        }
      }).then(res => {
        // console.log(res.data)
        //将题目存在locaStore里面
        localStorage.setItem(this.subject_name, JSON.stringify(res.data));
        this.Single = res.data[0];
        this.Selection = res.data[1];
        this.Judge = res.data[2];
        this.Operation = res.data[3];
        this.r_len = this.Single.length
        this.s_len = this.Selection.length
        this.j_len = this.Judge.length
        this.o_len = this.Operation.length
        this.allSum = this.r_len + this.s_len + this.j_len + this.o_len;
        Toast('开始考试');
        //设置答题卡
        this.setanswer(res.data);

      }).catch(err => {
        console.log(err)
      })
    }

  },
  methods: {
    tonum (event) {
      this.i = parseInt(event.target.innerText) - 1;
      this.show = false;
    },
    showPopup () {
      this.show = true;
    },
    setanswer (data) {
      //设置答题卡
      let answer = [];
      for (let i = 0; i < data.length; i++) {
        let n = [];
        for (let j = 0; j < data[i].length; j++) {
          if (i == 1) {
            n.push([]);
          } else {
            n.push('未填');
          }
        }
        answer.push(n);
      }
      localStorage.setItem(this.subject_name + '_answer', JSON.stringify(answer));
    },
    go () {
      if (parseInt(this.i) + 1 >= this.allSum) {
        Toast('已经是最后一题了');
        return false;
      }
      this.i++;
      this.ind = this.topicType(this.i)[1];
    },
    back () {
      if (this.i <= 0) {
        Toast('当前是第1题');
        return false;
      }
      this.i--;
      this.ind = this.topicType(this.i)[1];
    },
    afterRead (event) {
      //上传文件
      this.answer = event.target.files[0];
    },
    topicType (i) {
      if (i < this.r_len) { var n1 = i - 0; var n2 = 0; }
      if (i >= this.r_len && i < (this.r_len + this.s_len)) { var n1 = i - this.r_len; var n2 = 1; }
      if (i >= (this.r_len + this.s_len) && i < (this.r_len + this.s_len + this.j_len)) { var n1 = i - (this.r_len + this.s_len); var n2 = 2 }
      if (i >= (this.r_len + this.s_len + this.j_len) && i < (this.r_len + this.s_len + this.j_len + this.o_len)) { var n1 = i - (this.r_len + this.s_len + this.j_len); var n2 = 3 }
      return [n2, n1]; //n2: 题型 n1: 下标
    },
    sure_submit () {
      Dialog.confirm({
        title: '考试中..',
        message: '确认提交?',
      }).then(() => {
        this.submit();
      }).catch(() => { });
    },
    submit () {
      let topic = localStorage.getItem(this.subject_name);
      let answer = JSON.parse(localStorage.getItem(this.subject_name + '_answer'));
      let n = this.topicType(this.i);
      n = n[0];
      if (n == 0) { answer[n][this.ind] = this.radio }
      if (n == 1) { answer[n][this.ind] = this.checked }
      if (n == 2) { answer[n][this.ind] = this.check }
      if (n == 3) { answer[n][this.ind] = this.answer }

      //将多项选择题答案从数组转换成字符串
      answer[1] = answer[1].map(res => res.join(''))
      let admin = localStorage.getItem('admin');
      let formData = new FormData();
      formData.append("file", this.answer);
      formData.append("topic", topic);
      formData.append("admin", admin);
      formData.append("answer", JSON.stringify(answer));
      formData.append("subject_name", this.subject_name);

      axios({
        method: 'post',
        url: '/api/index/read',
        data: formData,
      }).then(res => {
        console.log('等待批阅');
      }).catch(err => {
        console.log(err);
      })
      localStorage.removeItem(this.subject_name);
      localStorage.removeItem(this.subject_name + '_answer');
      localStorage.removeItem(this.subject_name + '_time');
      localStorage.removeItem('i');
      //退出考试
      this.$router.push('/index');
      Toast('考试完成');
    },
    timedown () {
      Dialog({ message: '考试时间到' });
      this.time = 0;
      this.submit();
    },
    change (Time) {
      let time = (Time.hours * 60 * 60 + Time.minutes * 60 + Time.seconds) * 1000;
      localStorage.setItem(this.subject_name + '_time', time);
    }
  }
}
</script>

<style scoped>
.exam {
  width: 100%;
  height: 100vh;
  background-color: #fff;
}
.question {
  height: 1.5em;
  width: 100%;
  font-size: 1.2em;
  line-height: 1.5em;
  text-align: center;
}
.quetype {
  margin: 0.2em auto 0.2em auto;
}
.main {
  width: 100%;
  font-size: 1.5em;
  margin: 9em 0 0 0;
}
.top {
  z-index: 2;
  position: fixed;
  left: 0;
  top: 0;
}
</style>