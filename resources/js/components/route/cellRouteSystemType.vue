<template>
  <div>
    {{ props.item.solar_system.system_type[0]["name"] }}
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});
</script>

<style lang="scss"></style>
