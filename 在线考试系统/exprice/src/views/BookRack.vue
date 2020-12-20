<template>
  <div class="rack">
    <van-nav-bar title="我的书架"
                 fixed
                 placeholder
                 @click-left="tobook"
                 @click-right="tosearch"
                 class="bor_bot">
      <template #left>
        <van-icon name="wap-home-o"
                  size="25px" />
      </template>
      <template #right>
        <van-icon name="search"
                  size="22px" />
      </template>
    </van-nav-bar>
    <div>
      <van-cell :title="'共' +  bookrack.length + '本'"
                icon="bar-chart-o"
                class="cell">
        <!-- 使用 right-icon 插槽来自定义右侧图标 -->
        <template #right-icon>
          <span v-if="flag">
            <van-checkbox v-model="checkedAll">全选</van-checkbox>
          </span>
          <span v-else
                @click="edit">
            <van-icon name="edit">编辑</van-icon>
          </span>
        </template>
      </van-cell>
    </div>

    <transition name="van-slide-up">
      <div class="bottom_but"
           v-if="flag">
        <van-button type="info"
                    block
                    @click="flag=!flag">取消</van-button>

        <van-button type="danger"
                    block
                    @click="del_bookbrak">移出</van-button>

      </div>
    </transition>

    <div class="book">
      <van-checkbox-group v-model="checked"
                          checked-color="red"
                          ref="checkboxGroup">
        <van-grid>
          <van-grid-item v-for="(data,index) in bookrack"
                         :key="index"
                         class="bookrack_img">
            <!-- 复选框 -->
            <van-checkbox v-show="flag"
                          :name="data.id"
                          v-model="checked"
                          class="checked" />
            <img :src="data.cover_img"
                 alt="未加载"
                 class="bookimg"
                 @click="select_section(data.id)">
            <p>{{data.book_name}}</p>
          </van-grid-item>
          <van-grid-item @click="tobook">
            <van-icon name="plus"
                      size="60" />
          </van-grid-item>
        </van-grid>
      </van-checkbox-group>
    </div>
  </div>
</template>

<script>
import { Grid, GridItem, Icon, Toast, Checkbox, Dialog, CheckboxGroup, Cell, CellGroup, Button } from 'vant';
import axios from 'axios'
import { TABBARSHOW } from '@/type'

export default {
  data () {
    return {
      flag: false,  //编辑开关
      checkedAll: false,  //全选复选框
      checked: [],  //选中的复选框
      student_id: '', //学生id
    }
  },
  computed: {
    bookrack () {
      return this.$store.state.BookRack;
    }
  },
  watch: {
    checkedAll (newc, oldc) {
      if (newc == true) {
        this.$refs.checkboxGroup.toggleAll(true);
      } else {
        this.$refs.checkboxGroup.toggleAll(false);
      }
    },
  },
  methods: {
    select_section (book_id) {
      if (this.flag) {
        return false;
      }
      axios({
        method: 'post',
        url: '/api/book/select_section',
        data: {
          book_id: book_id
        }
      }).then(res => {
        if (!res.data.code) {
          Toast('该书籍暂无任何章节!')
          return false;
        } else {
          this.toread(book_id);
        }
      })
    },
    toread (book_id) {
      let admin = JSON.parse(localStorage.getItem('admin'));
      axios({
        method: 'post',
        url: '/api/book/bookread',
        data: {
          book_id: book_id,
          student_id: admin.id
        }
      }).then(res => {
        let id = res.data.data.book_id
        let num = res.data.data.section_num
        this.$router.push({
          path: `/bookread/${id}/${num}`,
        })
      })
    },
    tosearch () {
      this.$router.push('/booksearch');
    },
    tobook () {
      this.$router.push('/book');
    },
    edit () { //点击编辑
      this.flag = !this.flag;
    },
    del_bookbrak () {
      Dialog.confirm({
        message: '移出所选中的书?',
      }).then(() => {
        axios({
          method: 'post',
          url: '/api/book/del_bookbrak',
          data: {
            ids: this.checked.join(','),
            student_id: this.student_id,
          }
        }).then(res => {
          if (res.data.code == 1) {
            Object.assign(this.$data, this.$options.data());  //初始化数据
            this.$store.dispatch("GetBookRack");
            Toast.success(res.data.msg);
          } else {
            Toast.fail(res.data.msg);
          }
        })
      })
        .catch(() => { });
    },
  },
  mounted () {
    this.$store.commit(TABBARSHOW, false);
    this.$store.dispatch("GetBookRack");
    this.student_id = JSON.parse(localStorage.getItem('admin'))['id'];
  },
}
</script>

<style scoped>
.rack {
  width: 100%;
  margin-bottom: 5em;
  position: absolute;
  left: 0;
  top: 0;
}
.bor_bot {
  border-bottom: 1px solid #39a9ed;
}
.book {
  width: 100%;
  margin: 0em auto 5em auto;
}

.book p {
  margin-bottom: 0;
  font-size: 1.2em;
}
.bookimg {
  width: 8em;
  height: 10em;
  border-radius: 5px;
  position: relative;
}
.checked {
  position: absolute;
  top: 10px;
  right: 1px;
  z-index: 2;
}
.custom-title {
  margin-right: 4px;
  vertical-align: middle;
}
.cell {
  color: #39a9ed;
  font-size: 1.5em;
}
.bottom_but {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  height: auto;
  display: flex;
  border-top: 1px solid #39a9ed;
  text-align: center;
  line-height: 5em;
}
</style>

<style>
.bookrack_img {
  overflow: hidden;
}
</style>