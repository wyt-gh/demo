<template>
  <div id="index">
    <van-nav-bar title="主页面"
                 left-text="退出登录"
                 right-text=""
                 left-arrow
                 @click-left="back_login"
                 style="border-bottom:1px solid #1989fa" />

    <van-cell title="姓名:"
              :value="$store.state.UserInfo.username"
              size="large" />
    <van-cell title="专业"
              :value="$store.state.UserInfo.class_name"
              size="large" />
    <van-cell title="学科"
              :value="$store.state.UserInfo.major_name"
              size="large" />

    <van-field readonly
               clickable
               name="picker"
               :value="value"
               placeholder="请选择考试科目"
               @click="showPicker = true" />
    <van-popup v-model="showPicker"
               position="bottom">
      <van-picker show-toolbar
                  :columns="$store.state.Subject"
                  @confirm="onConfirm"
                  @cancel="showPicker = false"
                  value="1" />
    </van-popup>
    <van-cell></van-cell>
    <van-cell>
      <van-button type="info"
                  plain
                  hairline
                  block
                  class="change"
                  @click="begin_exam">开始考试(选择科目)</van-button>
    </van-cell>
    <van-cell>
      <van-button type="info"
                  plain
                  hairline
                  block
                  class="change"
                  @click="to_select">查看成绩</van-button>
    </van-cell>
    <van-cell>
      <van-button plain
                  hairline
                  type="info"
                  block
                  class="change"
                  @click="toExercises">每日一练</van-button>
    </van-cell>
  </div>

</template>

<script>
import { Cell, CellGroup, Button, Toast, NavBar, Dialog } from 'vant';
import axios from 'axios'
import { TABBARSHOW } from '@/type'

export default {
  data () {
    return {
      info: '',
      value: '',
      columns: [],
      showPicker: false,
    }
  },
  mounted () {
    this.$store.commit(TABBARSHOW, true);
    if (this.$store.state.UserInfo.length == 0) {
      this.$store.dispatch("GetUserInfo");
    }
    if (this.$store.state.Subject.length === 0) {
      this.$store.dispatch("GetSubject");
    }
  },
  methods: {

    back_login () {
      Dialog.confirm({
        title: '系统提示',
        message: '您确定要退出登录吗?',
      }).then(() => {
        //退出登录
        this.$store.state.UserInfo = [];
        this.$store.state.Subject = [];
        localStorage.clear();
        this.$router.push('/login');
        Toast('已退出');
      }).catch(err => {
        console.log(err)
      })
    },
    onConfirm (value) {
      //选择科目
      this.value = value;
      this.showPicker = false;
    },
    begin_exam () {
      // 点击考试
      if (!this.value) {
        Toast('请先选择科目!');
        return false;
      }
      let admin = JSON.parse(localStorage.getItem('admin'));
      axios({
        method: 'post',
        url: "/api/index/is_exam",
        data: {
          major_id: admin['major_id'],
          subject_name: this.value,
          id_card: admin.id_card
        }
      }).then(res => {
        if (res.data.code == 1) {
          Toast('该科目已经交卷!,不能再次考试');
          return false;
        } else {
          this.$router.push({
            path: `/exam/${this.value}`,
          })
        }
      })
    },
    to_select () {
      this.$router.push('/select');
    },
    toExercises () {
      this.$router.push('/exercises');
    }
  }
}
</script>

<style scoped>
#index {
  width: 100%;
  margin-bottom: 5em;
}
.change:active {
  transform: scale(1.2);
}
</style>