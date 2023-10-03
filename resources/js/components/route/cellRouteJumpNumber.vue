<template>
  <div>
    <span :class="text"> {{ props.item.jump }} </span>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  item: Object,
});

let text = $computed(() => {
  if (store.routeCurrentSystemID == props.item.solar_system.system_id) {
    return "text-red";
  } else {
    return null;
  }
});
</script>

<style lang="scss"></style>
