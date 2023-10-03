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
        return props.item.connection.source_sig
          ? props.item.connection.source_sig.signature_id
          : "???";
      } else {
        return props.item.connection.target_sig
          ? props.item.connection.target_sig.signature_id
          : "???";
      }
    }
  }
  return null;
});
</script>

<style lang="scss"></style>
