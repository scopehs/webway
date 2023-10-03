<template>
  <div>
    <q-table
      class="myRound bg-webBack myTableChain"
      :rows="store.staticMissing"
      :columns="columns"
      table-class="text-webway"
      table-header-class="bg-amber"
      row-key="id"
      dense
      dark
      ref="tableRef"
      rounded
      hide-bottom=""
      color="amber"
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-py-xs myRoundTop bg-primary">
          <div class="col-10 flex flex-center">
            <span class="text-h4">Missing Statics </span>
          </div>
        </div>
      </template>

      <template v-slot:body-cell-actions="item">
        <q-td :props="item">
          <div class="row justify-end q-gutter-sm">
            <div class="col-auto"><BrokenSigURL :item="item.row" /></div>
            <!-- <div class="col-auto"><SigDeleteButton :item="item.row" /></div> -->
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { onMounted, onBeforeUnmount, defineAsyncComponent } from "vue";
const BrokenSigURL = defineAsyncComponent(() => import("./BrokenSigURL.vue"));
let store = useMainStore();
onMounted(async () => {
  await store.getMissingStatic();
  window.Echo.private("static").listen("StaticUpdate", (e) => {
    if (e.flag.flag == 1) {
      store.updateStatic(e.flag.message);
    }
    if (e.flag.flag == 2) {
      store.removeStatic(e.flag.message);
    }
    if (e.flag.flag == 3) {
    }
    if (e.flag.flag == 4) {
    }
    if (e.flag.flag == 5) {
    }
  });
});
onBeforeUnmount(async () => {
  window.Echo.leave("static");
});
let pagination = $ref({
  sortBy: "jumps",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});
let columns = $ref([
  {
    name: "system",
    label: "System",
    align: "left",
    field: (row) => row.system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "type",
    label: "Type",
    align: "left",
    field: (row) => row.static_type.wormhole_type,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "jumps",
    label: "Jumps",
    align: "left",
    field: (row) => row.jumps,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "claim",
    align: "right",
    label: "Claim",
    sortable: true,
  },

  {
    name: "actions",
    align: "right",
    label: "",
    sortable: true,
  },
]);
let h = $computed(() => {
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableChain
  /* height or max-height is important */
  height: v-bind(h)

  .q-table__top
    padding-top: 0 !important
    padding-left: 0 !important
    padding-right: 0 !important





  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #202020

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
