<template>
  <div v-if="showButton">
    <q-btn
      :href="url"
      target="_blank"
      flat
      round
      padding="none"
      color="primary"
      icon="fa-solid fa-route"
      size="sm"
      ><q-tooltip :delay="500"> Get Route: {{ count }} jumps from 1DQ</q-tooltip></q-btn
    >
  </div>
</template>

<script setup>
const props = defineProps({
  item: [Array, Object],
});

let url = $computed(() => {
  var base = "/routing?share=";
  var url = base + props.item.link;
  return url;
});

let count = $computed(() => {
  return props.item.jumps;
});

let showButton = $computed(() => {
  if (props.item.jumps > 0) {
    return true;
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
