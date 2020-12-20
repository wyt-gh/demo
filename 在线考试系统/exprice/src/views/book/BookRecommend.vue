<template>
  <div>11</div>
</template>

<script>
export default {
  data () {
    return {
      hot: [],  //最热
      new: [],  //最新
      free: [], //免费
      end: []   //完本
    }
  },
  methods: {
    getHotBook () {
      axios({
        method: 'post',
        url: '/api/book/hotbook',
      }).then(res => {
        this.hot = res.data;
        console.log(this.hot);
      })
    },
    getNewBook () {
      axios({
        method: 'post',
        url: '/api/book/newbook',
      }).then(res => {
        this.new = res.data;
        console.log(this.new);
      })
    },
    getFreeBook () {
      axios({
        method: 'post',
        url: '/api/book/freebook',
      }).then(res => {
        this.free = res.data;
        console.log(this.free);
      })
    },
    getEndBook () {
      axios({
        method: 'post',
        url: '/api/book/endbook',
      }).then(res => {
        this.end = res.data;
        console.log(this.end);
      })
    },
    mounted () {
      this.getHotBook();
      this.getNewBook();
      this.getFreeBook();
      this.getEndBook();
    }
  }
}
</script>