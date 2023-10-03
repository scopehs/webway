<template>
  <div>
    {{ regionName }}
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});

const regionTypes = [7, 8, 9];

let regionName = $computed(() => {
  if (regionTypes.includes(props.item.solar_system.system_type[0].id)) {
    return props.item.solar_system.constellation.name;
  }
  return "";
});
</script>

<style lang="sass"></style>
