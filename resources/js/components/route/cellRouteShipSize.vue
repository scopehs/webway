<template>
  <div>
    {{ text }}
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});

let text = $computed(() => {
  if (props.item.connection) {
    if (
      props.item.connection.type.id == 2 ||
      props.item.connection.type.id == 4 ||
      props.item.connection.type.id == 5
    ) {
      if (props.item.solar_system.system_id == props.item.connection.source_system_id) {
        if (props.item.connection.source_sig.wormhole_info_ship_size_id > 0) {
          return props.item.connection.source_sig.wormhole_info_ship_size.table_text;
        }
      } else {
        if (props.item.connection.target_sig.wormhole_info_ship_size_id > 0) {
          return props.item.connection.target_sig.wormhole_info_ship_size.table_text;
        }
      }
    }
  }
  return null;
});
</script>

<style lang="scss"></style>
