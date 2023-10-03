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
    if (props.item.connection.type.name) {
      return props.item.connection.type.name;
    } else {
      return "Bridge";
    }
  } else {
    return null;
  }
});
</script>

<style lang="scss"></style>
