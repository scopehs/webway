<template>
  <div>
    <transition
      mode="out-in"
      enter-active-class="animate__animated animate__zoomIn animate__faster"
      leave-active-class="animate__animated animate__zoomOut animate__faster"
    >
      <div :key="value" class="text-left">
        {{ value }}
        <q-tooltip :delay="500" v-if="showToolTip">
          {{ toolTipText }}
        </q-tooltip>
      </div>
    </transition>
  </div>
</template>

<script setup>
const props = defineProps({
  value: String,
  typeName: String,
  typeID: Number,
});

let showToolTip = $computed(() => {
  if (props.typeName) {
    var count = props.typeName.length;
    if (count > 0 && props.typeID != 1) {
      return true;
    }
    return false;
  } else {
    return false;
  }
});

let toolTipText = $computed(() => {
  if (props.typeName) {
    var count = props.typeName.length;
    if (count > 0 && props.typeID != 1) {
      return props.typeName;
    }

    return "Unknown";
  } else {
    return "Unknown";
  }
});
</script>

<style lang="scss"></style>
