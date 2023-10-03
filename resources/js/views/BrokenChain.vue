<template>
  <div class="q-ma-md">
    <div class="row justify-around">
      <div :class="chainTableClass()"><BrokenChainTable /></div>
      <div class="col-4" v-if="can('super_admin')"><BrokenStaticTable /></div>
    </div>
  </div>
</template>

<script setup>
import { inject, defineAsyncComponent } from "vue";
const BrokenStaticTable = defineAsyncComponent(() =>
  import("../components/brokenChain/BrokenStaticTable.vue")
);
const BrokenChainTable = defineAsyncComponent(() =>
  import("../components/brokenChain/BrokenChainTable.vue")
);
document.title = "Webway - Broken";
let can = inject("can");

let chainTableClass = () => {
  if (can("super_admin")) {
    return "col-7";
  } else {
    return "col-10";
  }
};
</script>

<style lang="scss"></style>
