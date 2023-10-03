<template>
  <div>
    <q-table
      title="Connections"
      class="myTableSavedRoutes myRound"
      :rows="routes"
      :columns="columns"
      :style="h"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder bg-amber"
      row-key="id"
      dense
      ref="tableRef"
      dark
      rounded
      color="amber"
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Saved Routes </span>
          </div>
        </div>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="row full-width justify-end">
            <div class="col-auto q-pr-sm q-pb-xs">
              <q-btn
                color="primary"
                flat
                round
                size="md"
                padding="none"
                icon="fa-solid fa-upload"
                @click="load(props.row)"
              />
            </div>
            <div class="col-auto">
              <q-btn
                color="negative"
                flat
                round
                size="md"
                padding="none"
                icon="fa-solid fa-minus-circle"
                @click="remove(props.row)"
              />
            </div></div
        ></q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  height: Number,
});
let pagination = $ref({
  descending: false,
  page: 1,
  rowsPerPage: 10,
});
onMounted(async () => {
  await store.getSavedRoutes();
});

onBeforeUnmount(async () => {});

let remove = async (item) => {
  var request = {
    link: item.link,
  };

  await axios({
    method: "DELETE",
    withCredentials: true,
    url: "api/deletesaveroute",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let load = async (item) => {
  var request = {
    link: item.link,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/loadroute",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let routes = $computed(() => {
  return store.savedRoutes.saved_routes;
});

let columns = $ref([
  {
    name: "from",
    required: true,
    label: "From",
    align: "left",
    field: (row) => row.start_system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "to",
    required: true,
    label: "To",
    align: "left",
    field: (row) => row.end_system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "actions",
    label: "",
    align: "right",
  },
]);
let h = $computed(() => {
  let text = "height: " + props.height + "px";
  return text;
});
</script>

<style lang="sass">
.myTableSavedRoutes
  /* height or max-height is important */
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
