<template>
  <div>
    <span :class="text"> {{ props.item.solar_system.name }}</span>
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});

let text = $computed(() => {
  if (props.item.connection) {
    if (props.item.connection.reserved == 1 || props.item.connection.reserved == 2) {
      return "text-indigo font-weight-bold";
    } else {
      return null;
    }
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
