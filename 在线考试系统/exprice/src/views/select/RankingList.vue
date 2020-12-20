<template>
  <div class="rl">

    <van-nav-bar left-text="返回"
                 left-arrow
                 fixed
                 placeholder
                 @click-left="back"
                 style="border-bottom:1px solid #1989fa">
      <template #title>
        <b>
          {{now_subject_name}} 排行榜
        </b>
      </template>
    </van-nav-bar>
    <div v-for="(data,index) in $store.state.SubRanking"
         :key="index">
      <van-collapse v-model="activeNames">
        <van-collapse-item :name="index"
                           :value="data.scroe + '分'">
          <template #title>
            <div>
              <span v-if="index+1==1"
                    style="color:red;font-size:1.2em">NO.{{(parseInt(index)+1)}} {{data.username}}</span>
              <span v-else-if="index+1==2"
                    style="color:blue;font-size:1.1em">NO.{{(parseInt(index)+1)}} {{data.username}}</span>
              <span v-else-if="index+1==3"
                    style="color:green;font-size:1em">NO.{{(parseInt(index)+1)}} {{data.username}}</span>
              <span v-else>NO.{{(parseInt(index)+1)}} {{data.username}}</span>
            </div>
          </template>
          <template #value>
            <div>
              <span v-if="index+1==1"
                    style="color:red;font-size:1.2em">{{data.scroe}} 分</span>
              <span v-else-if="index+1==2"
                    style="color:blue;font-size:1.1em">{{data.scroe}} 分</span>
              <span v-else-if="index+1==3"
                    style="color:green;font-size:1em">{{data.scroe}} 分</span>
              <span v-else>{{data.scroe}} 分</span>
            </div>
          </template>
          <van-cell-group>
            <van-cell title="单选题"
                      :value="data.single_scroe + '分'" />
            <van-cell title="多选题"
                      :value="data.selection_scroe + '分'" />
            <van-cell title="判断题"
                      :value="data.judge_scroe + '分'" />
            <van-cell title="操作题"
                      :value="data.operation_scroe + '分'" />
          </van-cell-group>
        </van-collapse-item>
      </van-collapse>
    </div>

  </div>
</template>

<script>
import { Collapse, CollapseItem, NavBar, Cell, CellGroup } from 'vant';

export default {
  data () {
    return {
      activeNames: [],
      now_subject_name: '',
    };
  },
  methods: {
    back () {
      this.$router.push('/select');
    }
  },
  mounted () {
    this.$store.dispatch('GetSubRanking');

    this.now_subject_name = JSON.parse(localStorage.getItem('now_subject_name'));
  }
}
</script>

