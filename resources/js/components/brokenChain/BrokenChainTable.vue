<template>
  <div>
    <q-table
      class="myRound bg-webBack myTableBroken"
      :rows="store.brokenChain"
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
            <span class="text-h4">Broken Chains </span>
          </div>
        </div>
      </template>

      <template v-slot:body-cell-age="props">
        <q-td :props="props">
          <VueCountUp :time="props.value" v-slot="{ hours, minutes, seconds }">
            <span class="text-negative">{{ hours }}:{{ minutes }}:{{ seconds }}</span>
          </VueCountUp>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="item">
        <q-td :props="item">
          <div class="row justify-end q-gutter-sm">
            <div class="col-auto"><BrokenChainURL :item="item.row" /></div>
            <!-- <div class="col-auto"><SigDeleteButton :item="item.row" /></div> -->
          </div>
        </q-td>
      </template>

      <template v-slot:body-cell-claim="item">
        <q-td :props="item"> <BrokenChainClaim :item="item.row" /> </q-td>
      </template>

      <template v-slot:body-cell-constellation="item">
        <q-td :props="item">
          <BrokenChainTableConstellationCell :item="item.row" />
        </q-td>
      </template>
      <template v-slot:body-cell-region="item">
        <q-td :props="item"> <BrokenChainTableRegionCell :item="item.row" /> </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { onMounted, onBeforeUnmount, defineAsyncComponent } from "vue";
import { date } from "quasar";
let store = useMainStore();
const VueCountUp = defineAsyncComponent(() => import("../countup/index"));
const BrokenChainURL = defineAsyncComponent(() => import("./BrokenChainURL.vue"));
const BrokenChainClaim = defineAsyncComponent(() => import("./BrokenChainClaim.vue"));
const BrokenChainTableRegionCell = defineAsyncComponent(() =>
  import("./BrokenChainTableRegionCell.vue")
);
const BrokenChainTableConstellationCell = defineAsyncComponent(() =>
  import("./BrokenChainTableConstellationCell.vue")
);
onMounted(async () => {
  await store.getBrokenChain();
  window.Echo.private("broken").listen("BrokenUpdate", (e) => {
    if (e.flag.flag == 1) {
    }
    if (e.flag.flag == 2) {
      store.removeBroken(e.flag.message);
    }
    if (e.flag.flag == 3) {
      store.updateBroken(e.flag.message);
    }
    if (e.flag.flag == 4) {
    }
    if (e.flag.flag == 5) {
    }
  });
});
onBeforeUnmount(async () => {
  window.Echo.leave("broken");
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
    field: (row) => row.solar_system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "name_id",
    label: "SigID",
    align: "left",
    field: (row) => row.name_id,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "region",
    label: "Region",
    align: "left",
    field: (row) => row.solar_system.region.name,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "constellation",
    label: "Constellation",
    align: "left",
    field: (row) => row.solar_system.constellation.name,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "jumps",
    label: "Jumps",
    align: "left",
    field: (row) => row.jumps_p,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "age",
    label: "Age",
    align: "left",
    field: (row) => row.created_at,
    format: (val) => {
      let dateString = val;
      let dateObject = Date.parse(dateString);
      return dateObject;
    },
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

let test = (time) => {
  let dateObject = Date.parse(time);
  return dateObject;
};
</script>

<style lang="sass">
.myTableBroken
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
