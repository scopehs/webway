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
      var text = null;
      var name = this.item.name;
      var corp = null;
      var alliance = null;

      if (this.item.alliance) {
        alliance = this.item.alliance.name;
      }

      if (this.item.corporation) {
        corp = this.item.corporation.name;
      }

      text = name + " :  " + alliance + " - " + corp;
      return text;
    },
  },
};
</script>

<style scoped></style>
