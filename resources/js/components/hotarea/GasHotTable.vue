<template>
  <div class="">
    <q-table
      class="gasTable myRoundTop bg-webBack"
      dense=""
      :rows="filterdItems"
      :columns="columns"
      flat=""
      table-class=" text-webway"
      table-header-class=" text-weight-bolder bg-amber"
      row-key="id"
      ref="tableRef"
      dark
      rounded
      color="amber"
      hide-bottom
      :pagination="pagination"
    >
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn
            push
            padding="none"
            size="sm"
            text-color="negative"
            round
            icon="fas fa-minus-circle"
            @click="remove(props.row)"
          />
        </q-td>
      </template>

      <template v-slot:header-cell-actions="props">
        <q-th :props="props">
          <div class="q-pa-md">
            <q-btn color="warning" label="Add Nebual" rounded size="sm">
              <q-menu class="myRound">
                <q-card class="my-card">
                  <q-card-section>
                    <q-select
                      ref="dropNeb"
                      autofocus
                      v-model="nebual"
                      :options="nebListEnd"
                      input-debounce="0"
                      option-value="value"
                      option-label="text"
                      map-options
                      use-input
                      hide-selected
                      label="Nebual List"
                      filled
                      @filter="filterFn"
                      fill-input
                    >
                    </q-select>
                  </q-card-section>
                  <q-card-actions horizontal align="evenly">
                    <q-btn flat color="red" label="Cancel" v-close-popup />
                    <q-btn
                      flat
                      color="green"
                      label="Save"
                      @click="save()"
                      v-close-popup
                    />
                  </q-card-actions>
                </q-card>
              </q-menu>
            </q-btn>
          </div>
        </q-th>
      </template>
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h6">Jabber Gas</span>
          </div>
        </div>
        <div class="row full-width justify-end items-end bg-webBack">
          <div class="col-3 flex justify-end">
            <q-btn
              flat
              round
              dense
              :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
              @click="props.toggleFullscreen"
              class="q-ml-md"
            />
          </div>
        </div>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { useQuasar } from "quasar";

let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});
const $q = useQuasar();
let store = useMainStore();
let menu = $ref(0);
let nebual = $ref(null);

let close = () => {
  (nebual = null), (menu = 0);
};

let remove = async (item) => {
  var request = {
    jabber: 0,
  };
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/nebula/" + item.id,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  await store.getNebulaList();
  await store.getNebulaHot();
};

let save = async () => {
  var request = {
    jabber: 1,
  };
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/nebula/" + nebual.value,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  await store.getNebulaList();
  await store.getNebulaHot();
  $q.notify({
    type: "positive",
    message: "Gas Added",
  });
  nebual = null;
  menu = 0;
};

let filterdItems = $computed(() => {
  return store.nebulaHot;
});

let nebList = $computed(() => {
  return store.nebulaList;
});

let nebListEnd = $computed(() => {
  let data = [];
  if (userFilterText) {
    return nebList.filter((v) => v.text.toLowerCase().indexOf(userFilterText) > -1);
  }
  return store.nebulaList;
});

let userFilterText = $ref(null);

let filterFn = (val, update, abort) => {
  update(() => {
    userFilterText = val.toLowerCase();
    if (nebListEnd.length > 0 && val) {
      nebual = nebList[0];
    }
  });
};

let columns = $ref([
  {
    name: "name",
    label: "Name",
    align: "left",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "actions",
    align: "right",
  },
]);

let h = $computed(() => {
  let mins = 530;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.gasTable
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
