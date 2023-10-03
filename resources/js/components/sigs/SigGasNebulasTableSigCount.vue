<template>
  <div>
    <q-btn
      size="sm"
      push
      :disabled="buttonDisable"
      :color="color"
      color="primary"
      :label="sigCount"
      @click="close()"
      round
      v-close-popup
    />
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  item: [Object, Array],
});

let close = async () => {
  store.updateSigTableSearch(props.item.name);
};

let sigCount = $computed(() => {
  if (props.item.locations.length > 0 && props.item.sigs.length > 0) {
    var location = props.item.locations[0].region_id;
    var n = props.item.sigs.filter((s) => s.solar_system.region_id == location).length;
    return n;
  } else {
    return 0;
  }
});

let buttonDisable = $computed(() => {
  if (sigCount > 0) {
    return false;
  } else {
    return true;
  }
});

let color = $computed(() => {
  if (sigCount > 0) {
    return "positive";
  } else {
    return "";
  }
});
</script>

<style lang="scss"></style>
