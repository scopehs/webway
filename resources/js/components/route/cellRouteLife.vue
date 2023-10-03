<template>
  <div>
    <span :class="textColor">{{ text }}</span>
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});

let type = $computed(() => {
  if (props.item.connection) {
    if (
      props.item.connection.type.id == 2 ||
      props.item.connection.type.id == 4 ||
      props.item.connection.type.id == 5
    ) {
      if (props.item.solar_system.system_id == props.item.connection.source_system_id) {
        if (props.item.connection.source_sig.wormhole_info_time_till_death_id > 0) {
          return props.item.connection.source_sig.wormhole_info_time_till_death_id;
        }
      } else {
        if (props.item.connection.target_sig.wormhole_info_time_till_death_id > 0) {
          return props.item.connection.target_sig.wormhole_info_time_till_death_id;
        }
      }
    }
  }
  return null;
});

let text = $computed(() => {
  if (type == 1) {
    return "24+";
  }
  if (type == 2) {
    return "4-24";
  }
  if (type == 3) {
    return "0-4";
  }

  return null;
});

let textColor = $computed(() => {
  if (type == 1) {
    return "text-positive";
  }
  if (type == 2) {
    return "text-warning";
  }
  if (type == 3) {
    return "text-negative";
  }
  return null;
});
</script>

<style lang="scss"></style>
