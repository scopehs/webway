<template>
  <div>
    {{ text }}
  </div>
</template>
<script>

import moment from "moment";
export default {
  props: {
    item: Object,
  },
  data() {
    return {};
  },

  methods: {},

  computed: {
    text() {
      var t = moment.utc(this.item.created_at).format("YYYY-MM-DD HH:mm:ss");
      return t;
    },
  },
};
</script>

<style scoped></style>
