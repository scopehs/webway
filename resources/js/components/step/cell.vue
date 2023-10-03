<template>
  <div>
    <transition
      mode="out-in"
      enter-active-class="animate__animated animate__zoomIn animate__faster"
      leave-active-class="animate__animated animate__zoomOut animate__faster"
    >
      <div :key="value" class="text-left">
        <span :class="classText"> {{ value }}</span>
      </div>
    </transition>
  </div>
</template>

<script setup>
const props = defineProps({
  value: String,
  type: Number,
});

let classText = $computed(() => {
  if (props.type == 2) {
    if (props.value == "Stage 1") {
      return "text-center text-positive";
    }
    if (props.value == "Stage 2") {
      return "text-center text-warning";
    }
    if (props.value == "Stage 3") {
      return "text-center text-negative";
    }
  } else {
    return "";
  }
});
</script>

<style lang="scss"></style>
