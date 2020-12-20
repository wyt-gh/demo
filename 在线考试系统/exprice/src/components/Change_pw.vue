<template>
  <div id="login">
    <div class="form">
      <h1>考试登录系统</h1>
      <van-form @submit="onSubmit">
        <van-field v-model="username"
                   name="id_card"
                   label="账号:"
                   placeholder="账号"
                   :rules="[{ required: true, message: '请填写账号' }]" />
        <van-field v-model="old_password"
                   type="password"
                   name="old_password"
                   label="旧密码:"
                   placeholder="旧密码"
                   :rules="[{ required: true, message: '请填写旧密码' }]" />
        <van-field v-model="new_password"
                   type="password"
                   name="new_password"
                   label="新密码:"
                   placeholder="新密码"
                   :rules="[{ required: true, message: '请填写新密码' }]" />
        <van-field v-model="new_password2"
                   type="password"
                   name="new_password2"
                   label="确认密码:"
                   placeholder="确认密码"
                   :rules="[{ required: true, message: '请再次确定密码' }]" />
        <div style="margin: 16px;">
          <van-button round
                      block
                      type="info">
            修改密码
          </van-button><br>
          <van-button round
                      block
                      @click="back"
                      type="info">
            取消
          </van-button>
        </div>
      </van-form>

    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Form, Notify, Toast } from 'vant';

export default {
  data () {
    return {
      username: '',
      old_password: '',
      new_password: '',
      new_password2: '',
    }
  },
  methods: {
    onSubmit (values) {
      if (this.new_password != this.new_password2) {
        //两次密码不一样
        Toast('密码不一致,请确认密码');
      } else {
        //进行账号密码的验证
        axios({
          method: "post",
          url: "http://127.0.0.1/index.php/api/index/change_pw",
          data: {
            data: values
          }
        }).then(res => {
          console.log(res.data)
          if (res.data.code) {
            //修改成功
            Toast('修改成功');
            //路由跳转,返回登录页面
            this.$router.push('/login');
          } else {
            Toast('修改失败');
          }
        }).catch(err => {
          Notify({ type: 'warning', message: '请求失败' });
        })
      }
    },
    back () {
      this.$router.back(-1);
    },
  }
}
</script>

<style scoped>
#login {
  width: 100%;
  height: 66.7em;
  /* background: url("../assets/gif/login_bg.gif") no-repeat; */
  /* background-size: 100% 100%; */
}
.form {
  width: 30em;
  height: 30em;
  margin: auto;
  font-weight: bold;
  padding-top: 10em;
  text-align: center;
}
</style>
