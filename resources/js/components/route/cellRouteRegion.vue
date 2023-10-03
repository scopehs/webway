<template>
  <div v-if="showRegion">
    {{ item.solar_system.region.name }}
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});
let showRegion = $computed(() => {
  switch (props.item.solar_system.system_type[0].id) {
    case 7:
      return true;

    case 8:
      return true;

    case 9:
      return true;

    default:
      return false;
  }
});
</script>

<style lang="scss"></style>
