<template>
  <div class="row full-height justify-center">
    <div class="col-auto">
      <VueCountUp
        :interval="60000"
        v-if="time"
        :time="time"
        v-slot="{ hours, minutes, seconds }"
      >
        <span class="text-negative text-center">{{ hours }}:{{ minutes }}</span>
      </VueCountUp>
    </div>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { date } from "quasar";
const props = defineProps({
  age: String,
});
const VueCountUp = defineAsyncComponent(() => import("../countup/index"));

let time = $computed(() => {
  let dateString = props.age;
  let date = new Date(dateString);
  let offset = date.getTimezoneOffset() * 60000;
  let timestamp = Date.parse(dateString) - offset;
  return timestamp;
});
</script>

<style lang="scss"></style>
