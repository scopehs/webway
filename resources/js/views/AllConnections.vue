<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="myTableConnections myRound"
      :rows="filter_end"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder bg-amber"
      row-key="id"
      dense
      :filter="filterText"
      ref="tableRef"
      dark
      rounded
      color="amber"
      hide-bottom
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Connections - {{ rowCount }}</span>
          </div>
        </div>
        <div class="row full-width justify-between items-center q-pb-xs">
          <div class="col-8 q-mt-md q-mx-md">
            <div class="row full-width">
              <div class="col-auto q-mt-md q-mx-md">
                <div style="min-width: 300px; max-width: 600px">
                  <q-select
                    rounded
                    standout
                    v-model="selectedRegions"
                    :options="store.connectionRegionList"
                    label="Regions"
                    option-value="value"
                    option-label="text"
                    multiple
                    emit-value
                    map-options
                    menu-shrink
                    stack-label
                  >
                    <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                      <q-item v-bind="itemProps">
                        <q-item-section>
                          <q-item-label v-html="opt.text" />
                        </q-item-section>
                        <q-item-section side>
                          <q-toggle
                            :model-value="selected"
                            @update:model-value="toggleOption(opt)"
                          />
                        </q-item-section>
                      </q-item>
                    </template>

                    <template v-slot:selected-item="scope">
                      <q-chip
                        removable
                        @remove="scope.removeAtIndex(scope.index)"
                        :tabindex="scope.tabindex"
                        color="webChip"
                        text-color="white"
                        class="q-ma-none"
                      >
                        <span class="text-xs"> {{ scope.opt.text }} </span>
                      </q-chip>
                    </template>
                  </q-select>
                </div>
              </div>
              <div class="col-auto q-mt-md q-mx-md">
                <div style="min-width: 300px; max-width: 600px">
                  <q-select
                    rounded
                    standout
                    v-model="selectedConstellation"
                    :options="store.connectionConstellationList"
                    label="Constellation"
                    option-value="value"
                    option-label="text"
                    multiple
                    emit-value
                    map-options
                    menu-shrink
                    stack-label
                  >
                    <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                      <q-item v-bind="itemProps">
                        <q-item-section>
                          <q-item-label v-html="opt.text" />
                        </q-item-section>
                        <q-item-section side>
                          <q-toggle
                            :model-value="selected"
                            @update:model-value="toggleOption(opt)"
                          />
                        </q-item-section>
                      </q-item>
                    </template>

                    <template v-slot:selected-item="scope">
                      <q-chip
                        removable
                        @remove="scope.removeAtIndex(scope.index)"
                        :tabindex="scope.tabindex"
                        color="webChip"
                        text-color="white"
                        class="q-ma-none"
                      >
                        <span class="text-xs"> {{ scope.opt.text }} </span>
                      </q-chip>
                    </template>
                  </q-select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-3 flex justify-end">
            <q-input
              standout
              rounded
              dense
              debounce="300"
              v-model="filterText"
              placeholder="Search"
            >
              <template v-slot:append>
                <q-icon q-icon name="fa-solid fa-magnifying-glass" />
              </template>
            </q-input>
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
import { onMounted, onBeforeUnmount, inject } from "vue";
import { date } from "quasar";
let store = useMainStore();
let can = inject("can");
document.title = "Webway - All Connections";
onMounted(async () => {
  await window.Echo.private("allconnections").listen("AllConnectionUPdate", (e) => {
    if (e.flag.flag == 1) {
      store.getConnectionLists();
      store.getAllConnections();
    }

    if (e.flag.flag == 2) {
    }
  });

  await store.getConnectionLists();
  await store.getAllConnections().then((loading = false));
});

onBeforeUnmount(async () => {
  await window.Echo.leave("allconnections");
});

let tableRef = $ref();

let rowCount = $computed(() => {
  if (tableRef) {
    return tableRef.computedRowsNumber;
  }
  return 0;
});

let pagination = $ref({
  sortBy: "Updated",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});
let filterEnd = $computed(() => {
  return store.connections;
});

let loading = $ref(true);
let sigName = (row, type) => {
  if (type == 1) {
    if (row.source_sig) {
      return row.source_sig.name_id;
    } else {
      return "???";
    }
  } else {
    if (row.target_sig) {
      return row.target_sig.name_id;
    } else {
      return "???";
    }
  }
};

let filterText = $ref();
let selectedRegions = $ref([]);
let selectedConstellation = $ref([]);

let filter_mid = $computed(() => {
  let data = [];
  if (selectedConstellation.length != 0) {
    selectedConstellation.forEach((p) => {
      let pick = store.connections.filter(
        (f) =>
          f.source_system.constellation_id == p || f.target_system.constellation_id == p
      );
      if (pick != null) {
        pick.forEach((pk) => {
          data.push(pk);
        });
      }
    });
    return data;
  }
  return store.connections;
});

let filter_end = $computed(() => {
  let data = [];
  if (selectedRegions.length != 0) {
    selectedRegions.forEach((p) => {
      let pick = filter_mid.filter(
        (f) => f.source_system.region_id == p || f.target_system.region_id == p
      );
      if (pick != null) {
        pick.forEach((pk) => {
          data.push(pk);
        });
      }
    });
    return data;
  }
  return filter_mid;
});

let columns = $ref([
  {
    name: "startRegion",
    required: true,
    label: "Start Region",
    align: "left",
    field: (row) => row.source_system.region.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "startCon",
    required: true,
    label: "Start Con",
    align: "left",
    field: (row) => row.source_system.constellation.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "From",
    required: true,
    label: "Start System",
    align: "left",
    field: (row) => row.source_system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "startSig",
    align: "left",
    label: "SigID",
    field: (row) => sigName(row, 1),
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "endRegion",
    required: true,
    label: "End Region",
    align: "right",
    field: (row) => row.target_system.region.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "endCon",
    required: true,
    label: "End Con",
    align: "right",
    field: (row) => row.target_system.constellation.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "To",
    label: "To",
    align: "right",
    field: (row) => row.target_system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "SigID",
    label: "SigID",
    align: "right",
    field: (row) => sigName(row, 2),
    format: (val) => `${val}`,
  },
  {
    name: "Type",
    label: "Type",
    align: "right",
    field: (row) => row.type.name,
    format: (val) => `${val}`,
  },
  {
    name: "Updated",
    label: "Updated At",
    align: "right",
    field: (row) => row.updated_at,
    format: (val) => {
      let date = new Date(val);
      let dateString = date.toISOString();
      dateString = dateString.replace("T", " ");
      dateString = dateString.slice(0, 16);
      return dateString;
    },
  },
]);

let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableConnections
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
