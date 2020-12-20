<template>
  <div class="select">
    <van-nav-bar title="学生成绩信息"
                 left-text="返回"
                 right-text="选择科目"
                 left-arrow
                 @click-left="back"
                 @click-right="showPicker = true"
                 style="border-bottom:1px solid #1989fa" />
    <van-popup v-model="showPicker"
               position="bottom">
      <van-picker show-toolbar
                  :columns="this.$store.state.Subject"
                  @confirm="onConfirm"
                  @cancel="showPicker = false" />
    </van-popup>

    <div class="topic_result">
      <div class="topic_left">
        <div class="left_top">
          <div class="topic_progress">
            <van-circle v-model="startNum"
                        stroke-width="100"
                        :clockwise="false"
                        stroke-linecap="butt"
                        size="12em"
                        layer-color="#f7f8fa"
                        :rate="scroe"
                        :speed="30"
                        :text="scroe? scroe + '分':'暂无分数'" />
          </div>
        </div>
        <div class="left_bottom">
          考试时长: 120 分钟
        </div>
      </div>
      <div class="topic_right">
        <h2>错题分析</h2>
        <p>单选题: <span style="color:red">{{single_scroe? single_scroe:0}}</span></p>
        <p>多选题: <span style="color:red">{{selection_scroe? selection_scroe:0}}</span></p>
        <p>判断题: <span style="color:red">{{judge_scroe? judge_scroe:0}}</span></p>
        <p>操作题: <span style="color:red">{{operation_scroe? operation_scroe:0}}</span></p>
      </div>
      <div class="subject_position">
        <p>当前科目: <b>{{value}}</b></p>
      </div>
    </div>

    <div class="other_option">
      <van-cell-group>
        <van-cell title="查看本次错题"
                  value="内容"
                  label="现在回顾记得更牢">
          <van-button plain
                      type="info"
                      @click="to_falseExam">错题回顾</van-button>
        </van-cell>
        <van-cell title="查看科目排行榜"
                  value="内容"
                  label="看看大家考的怎么样">
          <van-button plain
                      type="info"
                      @click="to_ranking">排行榜</van-button>
        </van-cell>
      </van-cell-group>
    </div>
  </div>
</template>

<script>
import { Form, Cell, CellGroup, NavBar, Toast, Empty, Circle, Button } from 'vant';
import axios from 'axios';
import SelectFalse from './select/SelectFalse';
import { TABBARSHOW } from '@/type'

export default {
  components: {
    SelectFalse,
  },
  data () {
    return {
      value: '',
      showPicker: false,
      topic: '',
      admin: '',
      select_show: false,
      scroe: 0,  //分数
      single_scroe: 0,  //单选题分数
      selection_scroe: 0,  //多选题分数
      judge_scroe: 0,  //单选题分数
      operation_scroe: 0,   //操作题分数
      startNum: 0,
      code: 0,  //是否允许查看错题
    };
  },
  methods: {
    onConfirm (value) {
      this.value = value;
      this.showPicker = false;
      localStorage.setItem('now_subject_name', JSON.stringify(value))
      axios({
        method: 'post',
        url: "/api/index/is_exam",
        data: {
          major_id: this.admin.major_id,
          subject_name: this.value,
          id_card: this.admin.id_card
        }
      }).then(res => {
        if (!res.data.data) {
          Toast(res.data.msg);
          this.code = 0
        } else {
          this.code = 1;
        }

        this.topic = res.data.data;
        this.scroe = res.data.data.scroe;
        this.single_scroe = res.data.data.single_scroe;
        this.selection_scroe = res.data.data.selection_scroe;
        this.judge_scroe = res.data.data.judge_scroe;
        this.operation_scroe = res.data.data.operation_scroe;
      }).catch(err => {

      })
    },
    back () {
      this.$router.push('/index');
    },
    to_ranking () {
      this.$router.push('/rankinglist');
    },
    to_falseExam () {
      if (!this.code) {
        Toast('暂无成绩,无法查看!');
        return false;
      } else {
        this.$router.push('/falseexam');
      }
    }
  },
  mounted () {
    // created () {
    this.$store.commit(TABBARSHOW, false);
    if (this.$store.state.Subject.length === 0) {
      //请求科目
      this.$store.dispatch("GetSubject");
    }
    let admin = JSON.parse(localStorage.getItem('admin'));
    this.admin = admin;
    let now_subject_name = JSON.parse(localStorage.getItem('now_subject_name'));
    if (now_subject_name) {
      this.onConfirm(now_subject_name);
    }

  }
}
</script>

<style scoped>
.select {
  height: 100vh;
  background-color: #fff;
}
.topic_result {
  width: 90%;
  height: 20em;
  margin: 1em auto;
  /* border: 1px solid; */
  border-radius: 10px;
  box-shadow: 0 1px 0px gray;
  position: relative;
}
.topic_left {
  /* width: 50%; */
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
  /* border-right: 1px solid; */
}
.topic_progress {
  width: 12em;
  height: 12em;
  box-shadow: 0 0 1px black;
  border-radius: 100%;
  padding: 3px;
  margin: 2em 2em 1em 2em;
}
.left_bottom {
  text-align: center;
  margin: 2em auto;
}
.topic_right {
  position: absolute;
  top: 1em;
  left: 20em;
  /* border: 1px solid; */
}
.subject_position {
  position: absolute;
  top: 16em;
  right: 4em;
}
.other_option {
  width: 90%;
  margin: 3em auto 1em auto;
}
</style>