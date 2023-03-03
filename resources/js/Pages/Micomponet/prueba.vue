<template>
  <div id="app">
    <v-app id="inspire">
      <v-row>
        <v-col>
          <v-sheet height="500">
            <v-calendar ref="calendar" v-model="value" type="category">
              <template #day-body="{ date, category }">
                <div class="v-current-time" :categories="categories" :class="{ first: true }" :style="{ top: nowY }">
                </div>
              </template>
            </v-calendar>
          </v-sheet>
        </v-col>
      </v-row>
    </v-app>
  </div>
</template>
<script>


export default {
  data: () => ({
    value: '',
    ready: false,
    categories: [],
  }),
  computed: {
    cal() {
      return this.ready ? this.$refs.calendar : null
    },
    nowY() {
      //console.log('dañññññ');
      //console.log(this.cal.times.now);
      return this.cal ? this.cal.timeToY(this.cal.times.now) + 'px' : '-50px'
    },
  },
  mounted() {
    this.ready = true
    this.scrollToTime()
    this.updateTime()
  },
  methods: {
    getCurrentTime() {
      return this.cal ? this.cal.times.now.hour * 60 + this.cal.times.now.minute : 0
    },
    scrollToTime() {
      const time = this.getCurrentTime()
      const first = Math.max(0, time - (time % 30) - 30)
      console.log("11111111111111");
      console.log(first);
      this.cal.scrollToTime(first)
    },
    updateTime() {
      console.log('2222222222222');
      console.log(this.cal.updateTimes());
      setInterval(() => this.cal.updateTimes(), 60 * 2000)
    },
  },
}
</script>