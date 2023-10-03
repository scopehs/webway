<template>
  <div class="row q-pa-md justify-around max-h-screen">
    <div class="col-8">
      <q-table
        title="Connections"
        class="my-sticky-header-table myRound"
        :rows="filteredItemsEnd"
        :columns="columns"
        table-class=" text-webway"
        table-header-class=" text-weight-bolder"
        row-key="id"
        dense
        ref="tableRef"
        dark
        :filter="filterText"
        rounded
        color="amber"
        hide-bottom
        v-model:pagination="pagination"
      >
        <template v-slot:top="props">
          <div class="row full-width flex-center">
            <div class="col-auto q-mt-md">
              <q-btn-group rounded glossy push class="shadow-12">
                <q-btn label="UpdateStart" @click="updateOver(true)" />
                <q-btn label="UpdateDone" @click="updateOver(false)" />
                <q-btn label="Refresh" @click="refreshStart()" />
                <q-btn label="Horizon" @click="horizon()" />
                <q-btn label="Prequal" @click="prequal()" />
                <q-btn label="Logereader" @click="logs()" />
              </q-btn-group>
            </div>
          </div>
          <div class="row full-width justify-between">
            <div class="col-6">
              <span class="text-h6">Connections - {{ rowCount }}</span>
            </div>
            <div class="col-3 flex justify-end">
              <q-input
                borderless
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
          </div> </template
      ></q-table>
    </div>
    <div class="col-3">
      <div class="column justify-around full-height">
        <div class="col-auto">
          <q-card class="my-card myRound">
            <q-card-section class="bg-primary text-center text-h6">
              Connection Types
            </q-card-section>
            <q-card-section>
              <q-chip v-model:selected="chipSelect.gate" outline :color="chipGateColor">
                Gate
              </q-chip>
              <q-chip
                v-model:selected="chipSelect.wormhole"
                outline
                :color="chipWormHoleColor"
              >
                WormHole
              </q-chip>
              <q-chip
                v-model:selected="chipSelect.jumpBridge"
                outline
                :color="chipJumpBridgeColor"
              >
                Jump Bridge
              </q-chip>
            </q-card-section>
          </q-card>
        </div>
        <div class="col-auto">
          <q-card class="my-card myRound">
            <q-card-section class="bg-primary text-center text-h6">
              Jump Bridges
            </q-card-section>
            <q-card-section>
              <q-input
                v-model="jumpBridges"
                label="Enter Jump Data Here"
                outlined
                type="textarea"
            /></q-card-section>
            <q-card-actions vertical align="center">
              <q-btn
                rounded
                glossy
                color="warning"
                label="Update"
                @click="updateJump()"
                :disable="jumpBridges ? false : true"
              />
            </q-card-actions>
          </q-card>
        </div>
        <div class="col-auto">
          <q-card class="my-card myRound">
            <q-card-section class="bg-primary text-center text-h6">
              Metro Cookie
            </q-card-section>
            <q-card-section>
              <q-input
                v-model="metroCookie"
                label="Enter Cookie Here"
                outlined
                type="textarea"
              />
            </q-card-section>
            <q-card-actions vertical align="center">
              <q-btn
                rounded
                glossy
                color="warning"
                @click="updateMetro()"
                label="Update"
                :disable="metroCookie ? false : true"
              />
            </q-card-actions>
          </q-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { onMounted, inject, onBeforeUnmount } from "vue";
import { date, useQuasar } from "quasar";
import { useRouter } from "vue-router";
const $q = useQuasar();
let store = useMainStore();
let can = inject("can");
let router = useRouter();
let tableRef = $ref();

let filterText = $ref();
document.title = "Webway - HOW ARER YOU ON THIS PAGE";
onMounted(async () => {
  await window.Echo.private("scopeh").listen("ScopehUpdate", (e) => {});
  await store.getAllConnections();
});

onBeforeUnmount(async () => {
  await window.Echo.leave("scopeh");
});

let chipSelect = $ref({
  jumpBridge: false,
  wormhole: false,
  gate: false,
});

let filteredItemsStart = $computed(() => {
  let connectionType = [];
  let data = [];
  if (chipSelect.jumpBridge) {
    connectionType.push(3);
  }
  if (chipSelect.wormhole) {
    connectionType.push(2);
  }
  if (chipSelect.gate) {
    connectionType.push(1);
  }

  if (connectionType.length != 0) {
    connectionType.forEach((p) => {
      let pick = store.connections.filter((f) => f.type.id == p);
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

let filteredItemsEnd = $computed(() => {
  return filteredItemsStart;
});

let rowCount = $computed(() => {
  if (tableRef) {
    return tableRef.computedRowsNumber;
  }
  return 0;
});

let chipGateColor = $computed(() => {
  if (chipSelect.gate) {
    return "primary";
  } else {
    return "webway";
  }
});

let chipWormHoleColor = $computed(() => {
  if (chipSelect.wormhole) {
    return "primary";
  } else {
    return "webway";
  }
});

let chipJumpBridgeColor = $computed(() => {
  if (chipSelect.jumpBridge) {
    return "primary";
  } else {
    return "webway";
  }
});

let pagination = $ref({
  sortBy: "Updated",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

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

let jumpBridges = $ref();
let metroCookie = $ref();

let updateJump = async () => {
  var request = {
    data: jumpBridges,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/import/jump_bridges",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(() => {
    $q.notify({
      type: "positive",
      position: "right",
      message: "Jump Bridges Updateds",
      timeout: 2000,
      classes: "myRound",
    });

    jumpBridges = null;
  });
};

let updateMetro = async () => {
  var request = {
    cookie: metroCookie,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/metrocookie",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(() => {
    $q.notify({
      type: "positive",
      position: "right",
      message: "Metro Cookie Updateds",
      timeout: 2000,
      classes: "myRound",
    });

    metroCookie = null;
  });
};

let updateOver = async (state) => {
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/updateoverlay/" + state,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let refreshStart = async () => {
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/refreshoverlay",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let horizon = () => {
  let route = router.resolve({ path: "/hithere" });
  window.open(route.href);
};

let prequal = () => {
  let route = router.resolve({
    path: "/hitherealso",
  });
  window.open(route.href);
};

let logs = () => {
  let route = router.resolve({
    path: "/hithereagain",
  });
  window.open(route.href);
};

let columns = $ref([
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
    name: "SigID",
    align: "left",
    label: "SigID",
    field: (row) => sigName(row, 1),
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "To",
    label: "To",
    align: "left",
    field: (row) => row.target_system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "SigID",
    label: "SigID",
    align: "left",
    field: (row) => sigName(row, 2),
    format: (val) => `${val}`,
  },
  {
    name: "Type",
    label: "Type",
    align: "left",
    field: (row) => row.type.name,
    format: (val) => `${val}`,
  },
  {
    name: "Updated",
    label: "Updated At",
    align: "left",
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
  let mins = 100;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.my-sticky-header-table
  /* height or max-height is important */
  height: v-bind(h)

  .q-table__top,
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
