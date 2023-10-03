<template>
  <div>{{ show }}</div>
</template>

<script setup>
const props = defineProps({
  num: Number,
  type: Number,
});

let number = $computed(() => {
  return Number(props.num).toLocaleString();
});

let text = $computed(() => {
  if (props.num == 1) {
    return "Yes";
  } else {
    return "No";
  }
});
let show = $computed(() => {
  if (props.type == 1) {
    return text;
  } else if (props.type == 2) {
    return number;
  }
});
</script>

<style lang="scss"></style>
