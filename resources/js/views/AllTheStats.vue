<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      :loading="loading"
      class="myTableAllTheStats myRound bg-webBack"
      :rows="filterEnd"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder bg-amber"
      row-key="id"
      dense
      dark
      ref="tableRef"
      rounded
      color="amber"
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">All The Stats</span>
          </div>
        </div>
        <div class="row full-width justify-between items-center q-pb-xs">
          <div class="col-4 q-mt-md q-mx-md">
            <q-btn-toggle
              v-model="monthToggle"
              no-caps
              rounded
              @update:model-value="changeMonth"
              unelevated
              toggle-color="primary"
              color="webway"
              text-color="white"
              :options="[
                { label: 'Last', value: 2 },
                { label: 'Current', value: 1 },
              ]"
            />
          </div>
          <div class="col-5 q-mt-md q-mx-md">
            <q-toggle
              v-if="can('download_stats')"
              :disable="showOnlineToggle"
              class="q-ml-lg"
              v-model="onlineOnly"
              @update:model-value="onlineChange"
              :color="toggleOnlineColor"
              :class="toggleOnlineClass"
              keep-color
              unchecked-icon="fa-solid fa-signal fa-rotate-180"
              checked-icon="fa-solid fa-signal"
            >
              <template v-slot:default>
                <transition
                  mode="out-in"
                  enter-active-class="animate__animated animate__flash "
                >
                  <span :class="toggleOnlineTextColor" :key="onlineOnly"
                    >Online Only</span
                  >
                </transition>
              </template>
            </q-toggle>

            <q-toggle
              v-if="can('download_stats')"
              class="q-ml-lg"
              v-model="pathfinderOnlyToggle"
              @update:model-value="pathfinderOnlyChange"
              :color="togglePathFinderColor"
              :class="togglePathFinderClass"
              keep-color
              unchecked-icon="fa-solid fa-user-secret fa-rotate-180"
              checked-icon="fa-solid fa-user-secret"
            >
              <template v-slot:default>
                <transition
                  mode="out-in"
                  enter-active-class="animate__animated animate__flash "
                >
                  <span :class="pathfinderColor" :key="pathfinderOnlyToggle"
                    >Path Finders Only</span
                  >
                </transition>
              </template>
            </q-toggle>
            <q-toggle
              v-if="can('view_stats')"
              class="q-ml-lg"
              v-model="ownOnlyToggle"
              @update:model-value="OwnOnlyChange"
              :color="toggleOwnOnlyColor"
              :class="toggleOwnOnlyClass"
              keep-color
              unchecked-icon="fa-solid fa-user-secret fa-rotate-180"
              checked-icon="fa-solid fa-user-secret"
            >
              <template v-slot:default>
                <transition
                  mode="out-in"
                  enter-active-class="animate__animated animate__flash "
                >
                  <span :class="ownColor" :key="ownOnlyToggle">Own Only</span>
                </transition>
              </template>
            </q-toggle>
            <q-toggle
              v-if="can('download_stats')"
              class="q-ml-lg"
              v-model="iskOnly"
              :color="toggleiskOnlyColor"
              :class="toggleiskOnlyClass"
              keep-color
              unchecked-icon="fa-solid fa-sterling-sign fa-rotate-180"
              checked-icon="fa-solid fa-sterling-sign"
            >
              <template v-slot:default>
                <transition
                  mode="out-in"
                  enter-active-class="animate__animated animate__flash "
                >
                  <span :class="toggleiskOnlyTextColor" :key="iskOnly">Isk Only</span>
                </transition>
              </template>
            </q-toggle>
          </div>

          <div class="col-2 flex justify-end">
            <DownloadExcel
              v-if="can('download_stats')"
              class="q-btn q-btn-item non-selectable no-outline q-btn--standard q-btn--rectangle q-btn--rounded bg-primary text-white q-btn--actionable q-focusable q-hoverable"
              :data="filteredItems"
              :fields="fields"
              type="xls"
              worksheet="My Worksheet"
              :name="fileName"
            >
              Download
            </DownloadExcel>
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

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="name" :props="props">
            {{ props.row.name }}
          </q-td>

          <q-td key="sigs" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.sigsAll ?? 0"
              :done="props.row.stats_current.stats.sigsDoneAll ?? 0"
              :group="1"
              :part="props.row.stats_current.stats.sigsPartAll ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="sigsLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.sigsAll ?? 0"
              :done="props.row.stats_last_month.stats.sigsDoneAll ?? 0"
              :group="1"
              :part="props.row.stats_last_month.stats.sigsPartAll ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="connections" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.connectionsAll ?? 0"
              :done="props.row.stats_current.stats.connectionsDone ?? 0"
              :group="2"
              :part="props.row.stats_current.stats.connectionsPart ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="connectionsLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.connectionsAll ?? 0"
              :done="props.row.stats_last_month.stats.connectionsDone ?? 0"
              :group="2"
              :part="props.row.stats_last_month.stats.connectionsPart ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="wormhole" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.sigsAllWormholes ?? 0"
              :done="props.row.stats_current.stats.sigsDoneWormholes ?? 0"
              :group="3"
              :part="props.row.stats_current.stats.sigsPartWormholes ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="wormholeLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.sigsAllWormholes ?? 0"
              :done="props.row.stats_last_month.stats.sigsDoneWormholes ?? 0"
              :group="3"
              :part="props.row.stats_last_month.stats.sigsPartWormholes ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="combat" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.sigsAllCombat ?? 0"
              :done="props.row.stats_current.stats.sigsDoneCombat ?? 0"
              :group="4"
              :part="props.row.stats_current.stats.sigsPartCombat ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="combatLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.sigsAllCombat ?? 0"
              :done="props.row.stats_last_month.stats.sigsDoneCombat ?? 0"
              :group="4"
              :part="props.row.stats_last_month.stats.sigsPartCombat ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="data" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.sigsAllData ?? 0"
              :done="props.row.stats_current.stats.sigsDoneData ?? 0"
              :group="5"
              :part="props.row.stats_current.stats.sigsPartData ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="dataLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.sigsAllData ?? 0"
              :done="props.row.stats_last_month.stats.sigsDoneData ?? 0"
              :group="5"
              :part="props.row.stats_last_month.stats.sigsPartData ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="gas" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.sigsAllGas ?? 0"
              :done="props.row.stats_current.stats.sigsDoneGas ?? 0"
              :group="6"
              :part="props.row.stats_current.stats.sigsPartGas ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="gasLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.sigsAllGas ?? 0"
              :done="props.row.stats_last_month.stats.sigsDoneGas ?? 0"
              :group="6"
              :part="props.row.stats_last_month.stats.sigsPartGas ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="ore" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.sigsAllOre ?? 0"
              :done="props.row.stats_current.stats.sigsDoneOre ?? 0"
              :group="7"
              :part="props.row.stats_current.stats.sigsPartOre ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="oreLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.sigsAllOre ?? 0"
              :done="props.row.stats_last_month.stats.sigsDoneOre ?? 0"
              :group="7"
              :part="props.row.stats_last_month.stats.sigsPartOre ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="relic" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.sigsAllRelic ?? 0"
              :done="props.row.stats_current.stats.sigsDoneRelic ?? 0"
              :group="8"
              :part="props.row.stats_current.stats.sigsPartRelic ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="relicLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.sigsAllOre ?? 0"
              :done="props.row.stats_last_month.stats.sigsDoneOre ?? 0"
              :group="7"
              :part="props.row.stats_last_month.stats.sigsPartOre ?? 0"
              :type="1"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="unknown" :props="props" v-if="monthToggle == 1">
            <CellAllStats
              :total="props.row.stats_current.stats.sigsAllUnknown ?? 0"
              :type="2"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>
          <q-td key="unknownLast" :props="props" v-if="monthToggle == 2">
            <CellAllStats
              :total="props.row.stats_last_month.stats.sigsAllUnknown ?? 0"
              :type="2"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="isk" :props="props" v-show="showCurrentISK">
            <CellAllStats
              :total="props.row.totalisk ?? 0"
              :type="3"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="iskLast" :props="props" v-show="!showCurrentISK">
            <CellAllStats
              :total="props.row.totaliskhistory ?? 0"
              :type="3"
              :userID="props.row.id"
            ></CellAllStats>
          </q-td>

          <q-td key="tracking" :props="props">
            <CellOnlineStatus :userID="props.row.id"></CellOnlineStatus>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, inject, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { exportFile, useQuasar, date } from "quasar";
document.title = "Webway - All The Stats";
const CellAllStats = defineAsyncComponent(() =>
  import("../components/allthestats/cellAllStats.vue")
);
const CellOnlineStatus = defineAsyncComponent(() =>
  import("../components/allthestats/cellOnlineStatus.vue")
);

let store = useMainStore();
let can = inject("can");
onMounted(async () => {
  if (can("view_stats")) {
    Echo.private("allthestats").listen("AllTheStatsUpdate", (e) => {
      if (e.flag.flag == 1) {
        if (liveFeed) {
          store.updateAllTheStatsUser(e.flag.message[0]);
        }
      }
      if (e.flag.flag == 2) {
      }
      if (e.flag.flag == 3) {
      }
    });
  }

  Echo.private("allthestatsuser." + store.user_id).listen(
    "AllTheStatsUpdateUser",
    (e) => {
      if (e.flag.flag == 1) {
        if (liveFeed) {
          store.updateAllTheStatsUser(e.flag.message[0]);
        }
      }
    }
  );

  await store.setAllTheStatsUser();
  await store.getAllTheStatsLastMonth();
  loading = false;
});

onBeforeUnmount(async () => {
  window.Echo.leave("allthestats");
  window.Echo.leave("allthestatsuser." + store.user_id);
});
let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 50,
});
let loading = $ref(true);
let liveFeed = $ref(true);
let monthToggle = $ref(1);
let pathfinderOnlyToggle = $ref(false);
let ownOnlyToggle = $ref(false);
let onlineOnly = $ref(false);
let iskOnly = $ref(false);
let sortBy = $ref("name");
let sortDesc = $ref(false);

const clickLastMonth = () => {
  onlineOnly = false;
};

const onlineChange = () => {
  if (onlineOnly == true) {
    pathfinderOnlyToggle = false;
    ownOnlyToggle = false;
  }
};

const OwnOnlyChange = () => {
  if (ownOnlyToggle == true) {
    pathfinderOnlyToggle = false;
    onlineOnly = false;
  }
};

const pathfinderOnlyChange = () => {
  if (pathfinderOnlyToggle == true) {
    onlineOnly = false;
    ownOnlyToggle = false;
  }
};

const refreshstats = async () => {
  await axios({
    method: "get",
    withCredentials: true,
    url: "/api/allthestats",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

const changeMonth = () => {
  if (monthToggle == 1) {
    liveFeed = true;
  } else {
    liveFeed - false;
  }
};

const showOnlineToggle = $computed(() => {
  if (monthToggle == 1) {
    return false;
  } else {
    return true;
  }
});

const ownColor = $computed(() => {
  if (ownOnlyToggle) {
    return "text-blue";
  } else {
    return "text-warning text-strike";
  }
});

const pathfinderColor = $computed(() => {
  if (pathfinderOnlyToggle) {
    return "text-blue";
  } else {
    return "text-warning text-strike";
  }
});

const trackingCurrent = $computed(() => {
  let data = store.allthestatsusers;

  return data.filter((d) => d.esi_tokens.some((t) => t.tracking == 1));
});

const pathfindersCurrent = $computed(() => {
  let data = store.allthestatsusers;
  return data.filter((d) => d.roles.some((n) => n.name == "Pathfinder"));
});

const ownOnlyFilterCurrent = $computed(() => {
  let data = store.allthestatsusers;
  return data.filter((d) => d.id == store.user_id);
});

const ownOnlyFilterLast = $computed(() => {
  let data = store.allthestatusuerslastmonth;
  return data.filter((d) => d.id == store.user_id);
});

const pathfinderslast = $computed(() => {
  let data = store.allthestatusuerslastmonth;
  return data.filter((d) => d.roles.some((n) => n.name == "Pathfinder"));
});

const iskOnlyCurrent = $computed(() => {
  let data = store.allthestatsusers;
  return data.filter((d) => d.totalisk > 0);
});

const iskOnlyLast = $computed(() => {
  let data = store.allthestatusuerslastmonth;
  return data.filter((d) => d.totalisk > 0);
});

const filteredItems = $computed(() => {
  if (monthToggle == 1) {
    if (pathfinderOnlyToggle) {
      return pathfindersCurrent;
    } else {
      if (onlineOnly) {
        return trackingCurrent;
      } else {
        if (ownOnlyToggle) {
          return ownOnlyFilterCurrent;
        } else {
          return store.allthestatsusers;
        }
      }
    }
  } else {
    if (pathfinderOnlyToggle) {
      return pathfinderslast;
    } else {
      if (ownOnlyToggle) {
        return ownOnlyFilterLast;
      } else {
        return store.allthestatusuerslastmonth;
      }
    }
  }
});

const filterEnd = $computed(() => {
  if (iskOnly) {
    if (monthToggle == 1) {
      return filteredItems.filter((d) => d.totalisk > 0);
    } else {
      return filteredItems.filter((d) => d.totaliskhistory > 0);
    }
  } else {
    return filteredItems;
  }
});

let toggleOnlineColor = $computed(() => {
  return onlineOnly ? "primary" : "warning";
});

let toggleiskOnlyColor = $computed(() => {
  return iskOnly ? "primary" : "warning";
});

let toggleiskOnlyClass = $computed(() => {
  return iskOnly ? "" : "my-class";
});

let toggleiskOnlyTextColor = $computed(() => {
  return iskOnly ? "text-primary" : " text-warning text-strike";
});

let toggleOnlineTextColor = $computed(() => {
  return onlineOnly ? "text-primary" : " text-warning text-strike";
});

let toggleOnlineClass = $computed(() => {
  return onlineOnly ? "" : "my-class";
});

let togglePathFinderColor = $computed(() => {
  return pathfinderOnlyToggle ? "primary" : "warning";
});

let togglePathFinderClass = $computed(() => {
  return pathfinderOnlyToggle ? "" : "my-class";
});

let toggleOwnOnlyColor = $computed(() => {
  return ownOnlyToggle ? "primary" : "warning";
});

let toggleOwnOnlyClass = $computed(() => {
  return ownOnlyToggle ? "" : "my-class";
});

const columns = $computed(() => {
  if (can("view_stats")) {
    if (monthToggle == 1) {
      var cols = [
        {
          name: "name",
          label: "Name",
          align: "left",
          field: (row) => row.name,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "sigs",
          label: "Sigs",
          align: "left",
          field: (row) => row.stats_current.stats.sigsAll,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "connections",
          label: "Connections",
          align: "left",
          field: (row) => row.stats_current.stats.connectionsAll,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "wormhole",
          label: "Wormhole",
          field: (row) => row.stats_current.stats.sigsAllWormholes,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "combat",
          label: "Combat",
          field: (row) => row.stats_current.stats.sigsAllCombat,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "data",
          label: "Data",
          field: (row) => row.stats_current.stats.sigsAllData,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "gas",
          label: "Gas",
          field: (row) => row.stats_current.stats.sigsAllGas,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "ore",
          label: "Ore",
          field: (row) => row.stats_current.stats.sigsAllOre,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "relic",
          label: "Relic",
          field: (row) => row.stats_current.stats.sigsAllRelic,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "unknown",
          label: "Unknown",
          field: (row) => row.stats_current.stats.sigsAllUnknown,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "isk",
          label: "ISK",
          field: (row) => row.totalisk,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "tracking",
          label: "Tracking",
          field: (row) => row.inTracking,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },

        // { label: "Vulernable End Time", field: (row) => "vulnerable_end_time" }
      ];
    } else {
      var cols = [
        {
          name: "name",
          label: "Name",
          field: (row) => row.name,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "sigsLast",
          label: "Sigs",
          field: (row) => row.stats_last_month.stats.sigsAll,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "connectionsLast",
          label: "Connections",
          field: (row) => row.stats_last_month.stats.connectionsAll,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "wormholeLast",
          label: "Wormhole",
          field: (row) => row.stats_last_month.stats.sigsAllWormholes,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "combatLast",
          label: "Combat",
          field: (row) => row.stats_last_month.stats.sigsAllCombat,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "dataLast",
          label: "Data",
          field: (row) => row.stats_last_month.stats.sigsAllData,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "gasLast",
          label: "Gas",
          field: (row) => row.stats_last_month.stats.sigsAllGas,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "oreLast",
          label: "Ore",
          field: (row) => row.stats_last_month.stats.sigsAllOre,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "relicLast",
          label: "Relic",
          field: (row) => row.stats_last_month.stats.sigsAllRelic,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "unknownLast",
          label: "Unknown",
          field: (row) => row.stats_last_month.stats.sigsAllUnknown,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "iskLast",
          label: "ISK",
          field: (row) => row.totaliskhistory,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
      ];
    }

    return cols;
  } else {
    if (monthToggle == 1) {
      var cols = [
        {
          name: "name",
          label: "Name",
          field: (row) => row.name,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "sigs",
          label: "Sigs",
          field: (row) => row.stats_current.stats.sigsAll,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "connections",
          label: "Connections",
          field: (row) => row.stats_current.stats.connectionsAll,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "wormhole",
          label: "Wormhole",
          field: (row) => row.stats_current.stats.sigsAllWormholes,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "combat",
          label: "Combat",
          field: (row) => row.stats_current.stats.sigsAllCombat,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "data",
          label: "Data",
          field: (row) => row.stats_current.stats.sigsAllData,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "gas",
          label: "Gas",
          field: (row) => row.stats_current.stats.sigsAllGas,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "ore",
          label: "Ore",
          field: (row) => row.stats_current.stats.sigsAllOre,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "relic",
          label: "Relic",
          field: (row) => row.stats_current.stats.sigsAllRelic,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "unknown",
          label: "Unknown",
          field: (row) => row.stats_current.stats.sigsAllUnknown,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "isk",
          label: "ISK",
          field: (row) => row.totalisk,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },

        // { label: "Vulernable End Time", field: (row) => "vulnerable_end_time" }
      ];
    } else {
      var cols = [
        {
          name: "name",
          align: "left",
          label: "Name",
          field: (row) => "name",
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "sigLast",
          label: "Sigs",
          field: (row) => row.stats_last_month.stats.sigsAll,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "connectionsLast",
          label: "Connections",
          field: (row) => row.stats_last_month.stats.connectionsAll,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "wormholeLast",
          label: "Wormhole",
          field: (row) => row.stats_last_month.stats.sigsAllWormholes,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "combatLast",
          label: "Combat",
          field: (row) => row.stats_last_month.stats.sigsAllCombat,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "dataLast",
          label: "Data",
          field: (row) => row.stats_last_month.stats.sigsAllData,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "gasLast",
          label: "Gas",
          field: (row) => row.stats_last_month.stats.sigsAllGas,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "oreLast",
          label: "Ore",
          field: (row) => row.stats_last_month.stats.sigsAllOre,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "relicLast",
          label: "Relic",
          field: (row) => row.stats_last_month.stats.sigsAllRelic,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "unknownLast",
          label: "Unknown",
          field: (row) => row.stats_last_month.stats.sigsAllUnknown,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
        {
          name: "iskLast",
          label: "ISK",
          field: (row) => row.totaliskhistory,
          format: (val) => `${val}`,
          sortable: true,
          align: "left",
        },
      ];
    }

    return cols;
  }
});

let fileName = $computed(() => {
  const now = date.buildDate(true);
  const last = date.subtractFromDate(now, { months: 1 });
  if (monthToggle == 1) {
    var month = date.formatDate(now, "MM");
    var year = date.formatDate(now, "YYYY");
  } else {
    var month = date.formatDate(last, "MM");
    var year = date.formatDate(last, "YYYY");
  }

  return year + "_" + month + ".xls";
});

let fields = $computed(() => {
  if (monthToggle == 1) {
    var data = {
      name: "name",
      Connections: "stats_current.stats.connectionsDone",
      Gas: "stats_current.stats.sigsDoneGas",
      ISK: "totalisk",
    };
  } else {
    var data = {
      name: "name",
      Connections: "stats_last_month.stats.connectionsDone",
      Gas: "stats_last_month.stats.sigsDoneGas",
      ISK: "totaliskhistory",
    };
  }

  return data;
});

const showCurrentISK = $computed(() => {
  if (monthToggle == 1) {
    return true;
  } else {
    return false;
  }
});
let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableAllTheStats
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
