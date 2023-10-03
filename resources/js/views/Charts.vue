<template>
  <div overflow-x-hidden>
    <v-row no-gutters class="pt-5" justify="center">
      <v-col cols="10">
        <SigsCountChart ref="sigsCountChartComp"></SigsCountChart>
      </v-col>
    </v-row>
  </div>
</template>
<script>

import { mapState } from 'pinia'
export default {
  async created() {},
  data() {
    return {};
  },
  mounted() {},
  methods: {},

  computed: {},

  async beforeDestroy() {},
};
</script>
