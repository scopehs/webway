<template>
  <div class="q-ma-md">
    <div class="row full-width justify-center">
      <div class="col-8">
        <q-table
          title="Connections"
          class="myRound bg-webBack myTableAllSigs"
          :rows="filterend"
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
            <div class="row full-width q-py-xs items-center myRoundTop bg-primary">
              <div class="col-1 q-pl-sm">
                <q-btn
                  color="warning"
                  rounded
                  push
                  v-if="can('edit_shortest')"
                  class="myOutLineButton"
                  dense
                  padding="md"
                  label="Add"
                  @click="showSetting = true"
                >
                  <q-dialog v-model="showSetting" persistent>
                    <q-card class="myRoundTop">
                      <q-card-section class="myCardHeader bg-primary text-center text-h4">
                        Setting
                      </q-card-section>
                      <q-card-section>
                        <q-input
                          v-model="text"
                          type="text"
                          label="Notes"
                          outlined
                          rounded
                          dense=""
                        />
                      </q-card-section>
                      <q-card-section>
                        <div class="row full-width q-gutter-md">
                          <div class="col-auto">
                            <q-select
                              rounded
                              dense
                              standout
                              label-color="webway"
                              input-debounce="0"
                              option-value="value"
                              option-label="text"
                              v-model="startSystem"
                              :options="systemlistStartEnd"
                              label="From"
                              clearable
                              ref="fromSystemRef"
                              @filter="filterFnSystemListStart"
                              map-options
                              use-input
                              hide-selected
                              fill-input
                            />
                          </div>
                          <div class="col-auto">
                            <q-select
                              rounded
                              dense
                              standout
                              label-color="webway"
                              input-debounce="0"
                              option-value="value"
                              option-label="text"
                              v-model="endSystem"
                              clearable
                              :options="systemlistFinishEnd"
                              ref="toSystemRef"
                              label="To"
                              @filter="filterFnSystemListStartFinish"
                              map-options
                              use-input
                              hide-selected
                              fill-input
                            />
                          </div>
                        </div>
                      </q-card-section>
                      <q-card-actions align="around">
                        <q-btn
                          rounded
                          :disable="showSubmit"
                          label="Submit"
                          color="positive"
                          push
                          @click="submit()"
                        />
                        <q-btn
                          rounded
                          label="Close"
                          color="negative"
                          push
                          @click="close()"
                        />
                      </q-card-actions>
                    </q-card>
                  </q-dialog>
                </q-btn>
              </div>
              <div class="col-10 flex flex-center">
                <span class="text-h4">Shortest Routes </span>
              </div>
            </div>
            <div class="row full-width q-pt-xs justify-between"></div>
          </template>

          <template v-slot:body-cell-jumps="item">
            <q-td :props="item">
              <transition
                mode="out-in"
                enter-active-class="animate__animated animate__flash"
                leave-active-class="animate__animated animate__flash"
              >
                <span :key="`${item.value}-${item.row.id}-jumps`">
                  {{ item.value }}
                </span>
              </transition>
            </q-td>
          </template>

          <template v-slot:body-cell-actions="item">
            <q-td :props="item">
              <div class="row full-width justify-end q-gutter-md">
                <div class="col-auto">
                  <shortedRouteButton :item="item.row" />
                </div>
                <div class="col-auto">
                  <q-btn
                    v-if="can('edit_shortest')"
                    text-color="negative"
                    icon="fa-solid fa-trash-can"
                    flat
                    round
                    padding="none"
                    @click="removeRoute(item.row.id)"
                  />
                </div>
              </div>
            </q-td>
          </template>
        </q-table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { onMounted, onBeforeUnmount, inject, defineAsyncComponent } from "vue";
const shortedRouteButton = defineAsyncComponent(() =>
  import("../components/shortest/shortedRouteButton.vue")
);
document.title = "Webway - Shortest";
let store = useMainStore();
let can = inject("can");

onMounted(async () => {
  store.getSystemList();
  store.getShortestRoute();

  window.Echo.private("shortest").listen("ShortestUpdate", (e) => {
    if (e.flag.flag == 1) {
      //update solo route updateShortestRoute
      store.updateShortestRoute(e.flag.message);
    }
    if (e.flag.flag == 2) {
      //delete solo route deleteShortestRoute
      store.deleteShortestRoute(e.flag.message);
    }
  });
});

onBeforeUnmount(async () => {});

let text = $ref();
let showSetting = $ref(false);
let startSystemText = $ref();
let startSystem = $ref();
let systemlistStartEnd = $computed(() => {
  if (startSystemText) {
    return store.systemlist.filter(
      (d) => d.text.toLowerCase().indexOf(startSystemText) > -1
    );
  }
  return store.systemlist;
});
let filterFnSystemListStart = (val, update, abort) => {
  update(() => {
    startSystemText = val.toLowerCase();
    if (systemlistStartEnd.length > 0 && val) {
      startSystem = systemlistStartEnd[0];
    }
  });
};

let finsishSystemText = $ref();
let endSystem = $ref();
let systemlistFinishEnd = $computed(() => {
  if (finsishSystemText) {
    return store.systemlist.filter(
      (d) => d.text.toLowerCase().indexOf(finsishSystemText) > -1
    );
  }
  return store.systemlist;
});
let filterFnSystemListStartFinish = (val, update, abort) => {
  update(() => {
    finsishSystemText = val.toLowerCase();
    if (systemlistFinishEnd.length > 0 && val) {
      endSystem = systemlistFinishEnd[0];
    }
  });
};

let close = () => {
  text = null;
  startSystemText = null;
  startSystem = null;
  finsishSystemText = null;
  endSystem = null;
  showSetting = false;
};

let submit = async () => {
  let request = {
    startSystemID: startSystem.value,
    endSystemID: endSystem.value,
    notes: text,
  };

  await axios({
    method: "post",
    withCredentials: true,
    url: "/api/shorted",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  text = null;
  startSystemText = null;
  startSystem = null;
  finsishSystemText = null;
  endSystem = null;
};

let removeRoute = async (id) => {
  await axios({
    method: "delete",
    withCredentials: true,
    url: "/api/shortest/" + id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let showSubmit = $computed(() => {
  if (!startSystem || !endSystem) {
    return true;
  }

  return false;
});

let pagination = $ref({
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let filterend = $computed(() => {
  return store.shortestRoute;
});

let columns = $ref([
  {
    name: "from",
    label: "From",
    align: "left",
    field: (row) => row.start_system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "to",
    label: "To",
    align: "left",
    field: (row) => row.end_system.name,
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
    name: "notes",
    label: "Notes",
    align: "left",
    field: (row) => row.notes,
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
  let mins = 30;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableAllSigs
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
<style lang="scss" scoped>
.my-class,
.q-toggle__thumb {
  border-color: currentColor;
}
</style>
