<template>
  <div>
    <q-icon
      v-if="showIcon"
      class="style-2"
      :left="checkSide"
      :right="!checkSide"
      name="fa-solid fa-exclamation-triangle"
      ><q-tooltip> Reported Gone: {{ count }} </q-tooltip></q-icon
    >
  </div>
</template>

<script setup>
const props = defineProps({
  item: [Array, Object],
  side: String,
});

let showIcon = $computed(() => {
  if (props.item.report_count > 0) {
    return true;
  } else {
    return false;
  }
});

let count = $computed(() => {
  return props.item.report_count;
});

let checkSide = $computed(() => {
  if (props.side == "l") {
    return true;
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>

<style>
.style-2 {
  text-align: center;

  background: linear-gradient(
    to right,
    rgb(252, 96, 96) 20%,
    #ff0 40%,
    #ff0 60%,
    #fff 80%
  );
  background-size: 200% auto;

  color: #000;
  background-clip: text;
  text-fill-color: transparent;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;

  animation: shine 1s linear infinite;
  @keyframes shine {
    to {
      background-position: 200% center;
    }
  }
}
</style>
