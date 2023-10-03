<template>
  <div>
    <VueCountUp v-if="time" :time="time" v-slot="{ hours, minutes, seconds }">
      <span class="text-negative">{{ hours }}:{{ minutes }}:{{ seconds }}</span>
    </VueCountUp>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { date } from "quasar";
const props = defineProps({
  item: Object,
});
const VueCountUp = defineAsyncComponent(() => import("../countup/index"));

let time = $computed(() => {
  let time = null;
  if (props.item.connection) {
    if (
      props.item.connection.type.id == 2 ||
      props.item.connection.type.id == 4 ||
      props.item.connection.type.id == 5
    ) {
      if (props.item.solar_system.system_id == props.item.connection.source_system_id) {
        time = props.item.connection.source_sig.life_time;
      } else {
        time = props.item.connection.target_sig.life_time;
      }
    }

    let dateString = time;
    let date = new Date(dateString);
    let offset = date.getTimezoneOffset() * 60000;
    let timestamp = Date.parse(dateString) - offset;
    return timestamp;
  }
  return null;
});

let zero = (num) => {
  if (num > 9) {
    return num;
  } else {
    return "0" + num;
  }
};
</script>

<style lang="scss"></style>
