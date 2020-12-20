<template>
  <div id="login">
    <div class="form">
      <p style="font-size:30px;font-weight:blod">考试登录系统</p>
      <van-form @submit="onSubmit">
        <van-field v-model="username"
                   name="id_card"
                   label="账号:"
                   placeholder="请输入账号"
                   :rules="[{ required: true, message: '请填写账号' }]" />
        <van-field v-model="password"
                   type="password"
                   name="password"
                   label="密码:"
                   placeholder="请输入密码"
                   :rules="[{ required: true, message: '请填写密码' }]" />
        <div style="margin: 16px;">
          <van-button round
                      block
                      type="info"
                      native-type="submit">
            登录
          </van-button>
          <p></p>
          <van-button round
                      block
                      type="info"
                      @click="change_pw">
            修改密码
          </van-button>
        </div>
      </van-form>

    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Form, Notify } from 'vant';
import { USERINFO, TABBARSHOW } from '@/type';

export default {
  data () {
    return {
      username: '',
      password: '',
    }
  },
  methods: {
    onSubmit (values) {
      axios({
        method: "post",
        url: "/api/index/login",
        data: {
          data: values
        }
      }).then(res => {
        if (res.data.code == 0) {
          this.$toast('登录失败')
        } else {
          localStorage.setItem('admin', JSON.stringify(res.data));
          this.$store.state.UserInfo = res.data;
          let is_login = new Date().getTime() / 1000;
          localStorage.setItem('is_login', JSON.stringify(is_login));
          this.$router.push('/index');
        }
      }).catch(err => {
        console.log(err);
      });
    },
    change_pw () {
      this.$router.push('/change_pw');
    },
  },
  mounted () {
    this.$store.commit(TABBARSHOW, false);
  }
}
</script>

<style scoped>
#login {
  width: 100%;
  height: 100vh;
}
h1 {
  margin-bottom: 2em;
}
.form {
  width: 70%;
  height: auto;
  margin: auto;
  font-weight: bold;
  padding-top: 6em;
  text-align: center;
}
</style>
