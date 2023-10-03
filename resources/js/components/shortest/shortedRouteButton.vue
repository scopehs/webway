<template>
  <div v-if="showButton">
    <q-btn
      :href="url"
      target="_blank"
      text-color="info"
      icon="fa-solid fa-route"
      flat
      round
      padding="none"
    />
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

let showButton = $computed(() => {
  if (props.item.jumps > 0) {
    return true;
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
