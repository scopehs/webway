<template>
  <div :class="color">{{ percent }}%</div>
</template>
<script>

export default {
  props: {
    percent: Number,
  },
  data() {
    return {};
  },

  methods: {},

  computed: {
    color() {
      if (this.percent > 74) {
        return "green--text";
      }

      if (this.percent < 25) {
        return "red--text";
      }

      return "orange--text";
    },
  },
};
</script>


