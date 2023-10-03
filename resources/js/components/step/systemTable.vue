<template>
  <div class="q-pa-md">
    <q-table
      class="myRound bg-webBack stepTable overflow-hidden"
      :rows="filteredItems"
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
        <div
          class="row full-width justify-between q-py-xs text-webway myRoundTop"
          :class="backColor"
        >
          <div class="col-auto">
            <q-list dense class="q-pt-xs text-webway">
              <q-item-section>
                <q-item-label class="q-pl-sm" dense>
                  Class: {{ classType }}
                  <q-icon v-if="shatterIcon" name="fa-solid fa-skull" />
                </q-item-label>
              </q-item-section>
              <q-item-section>
                <q-item-label dense>
                  Security:
                  <span :class="securityText"> {{ security }}</span></q-item-label
                >
              </q-item-section>
              <q-item-section>
                <q-item-label dense> Effects: {{ effect }}</q-item-label>
              </q-item-section>
              <q-item-section>
                <q-item-label dense>
                  Statics:
                  <span v-if="showStatics"
                    ><span v-for="(hole, index) in system.statics" :key="index">
                      {{ hole.wormhole_type }}
                      <span :class="staticColor(hole.type.id)">
                        ({{ hole.type.name }})
                      </span>
                      &nbsp;&nbsp;
                    </span></span
                  ></q-item-label
                >
              </q-item-section>
            </q-list>
          </div>
          <div class="col-auto">
            <div class="row full-width">
              <div class="col text-h4">
                {{ title }} -
                <q-btn
                  color="green-9"
                  size="xs"
                  :disabled="disableShowUserMenu"
                  round
                  :label="userCount"
                >
                  <q-menu transition-show="rotate" transition-hide="rotate">
                    <q-list style="min-width: 100px">
                      <q-item v-for="(list, index) in userlist" :key="index">
                        <q-item-section avatar>
                          <q-avatar> <img :src="url(list.id)" /> </q-avatar
                        ></q-item-section>
                        <q-item-section>{{ list.name }}</q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
              </div>
            </div>
            <div class="row full-width">
              <div class="col-auto text-h5">
                <span>{{ system.name }}</span>
                <span v-if="showRegion"> - {{ system.region.name }}</span>
              </div>
            </div>
            <div class="row">
              <AddDrifterHole :system="system" :routeID="routeID"></AddDrifterHole>
            </div>
          </div>

          <div class="col-auto flex">
            <div class="row full-width justify-end">
              <div class="col-auto">
                Kills 24H - {{ killCount }}
                <q-btn
                  flat
                  padding="none"
                  round
                  dense
                  :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                  @click="props.toggleFullscreen"
                  class="q-ml-md"
                />
              </div>
            </div>
            <div class="row full-width justify-end">
              <div class="col-auto">
                <SystemNotes :system="system" :type="type"></SystemNotes>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template v-slot:body="props" name="list" is="transition-group">
        <transition mode="out-in" leave-active-class="animate__animated animate__zoomOut">
          <q-tr :props="props" :key="`${props.row.signature_id} - row`">
            <q-td key="id" :props="props">
              <CellSigId
                :value="props.row.signature_id"
                :sig="props.row"
                :type="type"
                :edit="edit"
                :lastSystem="lastSystemPropID"
                :currentSystem="currentSystemPropID"
              ></CellSigId>
            </q-td>
            <q-td key="group" :props="props">
              <CellStepGroup
                :value="props.row.group.name"
                :typeName="props.row.name"
                :typeID="props.row.group.id"
              ></CellStepGroup>
            </q-td>
            <q-td key="type" :props="props">
              <CellWormholeTypeButton
                v-if="showAddType(props.row)"
                :sig="props.row"
                :type="type"
                :edit="edit"
                :system="system"
              ></CellWormholeTypeButton>
            </q-td>
            <q-td key="age" :props="props">
              <CellAge :age="props.row.life_time" />
            </q-td>

            <q-td key="linkTo" :props="props">
              <CellLinkToButton
                :sig="props.row"
                :type="type"
                :edit="edit"
              ></CellLinkToButton>
            </q-td>
            <q-td key="mass" :props="props">
              <Cell :value="infoText(props.row.wormhole_info_mass)" :type="2"></Cell>
            </q-td>
            <q-td key="size" :props="props">
              <Cell :value="infoText(props.row.wormhole_info_ship_size)" :type="1"></Cell>
            </q-td>
            <q-td key="life" :props="props">
              <Cell
                :value="infoText(props.row.wormhole_info_time_till_death)"
                :type="2"
              ></Cell>
            </q-td>
            <q-td key="actions" :props="props">
              <div class="row full-width justify-end q-gutter-xs">
                <div class="col-auto">
                  <RouteInfoPaste
                    :id="`${props.row.id}-infopannel`"
                    v-if="showInfoButton(props.row)"
                    :sig="props.row"
                  ></RouteInfoPaste>
                </div>
                <div class="col-auto">
                  <!-- gone = set all the delete flags to 1
                                         1 = No roles Is Owner  = normal delete
                                         2 = Has roles Is Owner = Give options to delete or say sig is gone
                                         3 = Has roles Not Owner = Say sig is gone
                                         4 = No roles Not Owner = Report sig as gone. -->
                  <SigNotes :sig="props.row" :type="type"></SigNotes>
                </div>
                <div class="col-auto" v-if="props.row.leads_to > 0">
                  <NextSystemSigs :nextSigs="props.row.next_system_sigs" />
                </div>
                <div class="col-auto" v-if="showDeleteButtons()">
                  <RouteDeleteButtonNoRolesIsOwner
                    v-if="deleteButton(props.row) == 1"
                    :item="props.row"
                    :type="props.type"
                  />

                  <RouteDeleteButtonHasRolesIsOwner
                    v-if="deleteButton(props.row) == 2"
                    :item="props.row"
                  ></RouteDeleteButtonHasRolesIsOwner>
                  <RouteDeleteButtonHasRolesNotOwner
                    v-if="deleteButton(props.row) == 3"
                    :item="props.row"
                    :type="props.type"
                  ></RouteDeleteButtonHasRolesNotOwner>
                  <RouteDeleteButtonNoRolesNotOwner
                    v-if="deleteButton(props.row) == 4"
                    :item="props.row"
                    :type="props.type"
                  ></RouteDeleteButtonNoRolesNotOwner>
                </div>
              </div>
            </q-td>
          </q-tr>
        </transition>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { onMounted, onBeforeUnmount, inject, defineAsyncComponent } from "vue";
import { date } from "quasar";
let store = useMainStore();
let can = inject("can");
const props = defineProps({
  system: Object,
  type: Number,
  lastRouteSystemID: Number,
  routeID: Number,
  lo: Number,
  currentSystemPropID: Number,
  lastSystemPropID: Number,
});
const NextSystemSigs = defineAsyncComponent(() => import("./nextSystemSigs.vue"));
const AddDrifterHole = defineAsyncComponent(() => import("./addDrifterHole.vue"));
const SystemNotes = defineAsyncComponent(() => import("./systemNotes.vue"));
const CellSigId = defineAsyncComponent(() => import("./cellSigId.vue"));
const CellStepGroup = defineAsyncComponent(() => import("./cellGroup.vue"));
const CellAge = defineAsyncComponent(() => import("./cellAge.vue"));
const CellWormholeTypeButton = defineAsyncComponent(() =>
  import("./cellWormholeTypeButton.vue")
);
const CellLinkToButton = defineAsyncComponent(() => import("./cellLinkToButton.vue"));
const Cell = defineAsyncComponent(() => import("./cell.vue"));
const RouteInfoPaste = defineAsyncComponent(() => import("./routeInfoPaste.vue"));
const SigNotes = defineAsyncComponent(() => import("./sigNotes.vue"));
const RouteDeleteButtonNoRolesIsOwner = defineAsyncComponent(() =>
  import("./routeDeleteButtonNoRolesIsOwner.vue")
);

const RouteDeleteButtonHasRolesIsOwner = defineAsyncComponent(() =>
  import("./routeDeleteButtonHasRolesIsOwner.vue")
);

const RouteDeleteButtonHasRolesNotOwner = defineAsyncComponent(() =>
  import("./routeDeleteButtonHasRolesNotOwner.vue")
);

const RouteDeleteButtonNoRolesNotOwner = defineAsyncComponent(() =>
  import("./routeDeleteButtonNoRolesNotOwner.vue")
);

onMounted(async () => {
  if (props.type == 1) {
    window.Echo.private("mapping." + props.system.system_id).listen(
      "MappingUpdate",
      (e) => {
        if (e.flag.flag == 1) {
          store.updateCurrentSystemSigs(e.flag.message);
        }

        if (e.flag.flag == 2) {
          store.deleteCurrentSystemSig(e.flag.id);
          store.deleteLastSystemSig(e.flag.id);
        }

        if (e.flag.flag == 3) {
          store.updateJove(e.flag.message);
        }
        if (e.flag.flag == 4) {
          store.setCurrentSystemChars(e.flag.message);
        }
        if (e.flag.flag == 5) {
          store.updateCurrentKillCount(e.flag.kills);
        }
        if (e.flag.flag == 6) {
          store.getSystemNotes({
            type: 1,
            id: props.system.system_id,
          });
        }
        if (e.flag.flag == 7) {
          store.getSigNotes({
            type: 1,
            id: props.system.system_id,
          });
        }
      }
    );

    window.Echo.private("mapping." + props.lastRouteSystemID).listen(
      "MappingUpdate",
      (e) => {
        if (e.flag.flag == 1) {
          store.updateLastSystemSigs(e.flag.message);
        }
        if (e.flag.flag == 2) {
          store.deleteCurrentSystemSig(e.flag.id);
          store.deleteLastSystemSig(e.flag.id);
        }
        if (e.flag.flag == 4) {
          store.setLastSystemChars(e.flag.message);
        }
        if (e.flag.flag == 5) {
          store.updateLastKillCount(e.flag.kills);
        }
        if (e.flag.flag == 6) {
          store.getSystemNotes({
            type: 2,
            id: props.lastSystemPropID,
          });
        }
        if (e.flag.flag == 7) {
          store.getSigNotes({
            type: 2,
            id: props.lastSystemPropID,
          });
        }
      }
    );
  }
});

onBeforeUnmount(async () => {
  window.Echo.leave("mapping." + props.lastRouteSystemID);
  window.Echo.leave("mapping." + props.system.system_id);
});

let pagination = $ref({
  sortBy: "id",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let testTime = () => {
  return new Date();
};
let staticColor = (id) => {
  switch (id) {
    // 1-6 C#
    // 7-9 H/L/N S
    // 12 Thera
    // 13-18 C#
    // 25 Trig
    case 1:
      return "text-negative";

    case 2:
      return "text-negative";

    case 3:
      return "text-negative";

    case 4:
      return "text-negative";

    case 5:
      return "text-negative";

    case 6:
      return "text-negative";

    case 7:
      return "text-primary";

    case 8:
      return "text-warning";

    case 9:
      return "text-negative";

    case 12:
      return "text-negative";

    case 13:
      return "text-negative";

    case 14:
      return "text-negative";

    case 15:
      return "text-negative";

    case 16:
      return "text-negative";

    case 17:
      return "text-negative";

    case 18:
      return "text-negative";

    case 25:
      return "text-negative";
  }
};

let showInfoButton = (item) => {
  if (item.type && props.type == 1) {
    return true;
  } else {
    return false;
  }
};

let showDeleteButtons = () => {
  if (props.type == 1) {
    return true;
  }
  false;
};

let deleteButton = (item) => {
  var roles = false;
  var owner = false;
  if (can("delete_sigs")) {
    roles = true;
  }

  if (item.created_by_id == store.user_id) {
    owner = true;
  }

  if (roles == false && owner == true) {
    return 1;
  }

  if (roles == true && owner == true) {
    return 2;
  }

  if (roles == true && owner == false) {
    return 3;
  }

  if (roles == false && owner == false) {
    return 4;
  }
};

let showAddType = (item) => {
  if (item.signature_group_id == 1) {
    return true;
  } else {
    return false;
  }
};

let url = (charID) => {
  return "https://image.eveonline.com/Character/" + charID + "_128.jpg";
};

let infoText = (item) => {
  if (item) {
    return item.table_text;
  } else {
    return "";
  }
};

let disableShowUserMenu = $computed(() => {
  if (userCount == 0) {
    return true;
  } else {
    return false;
  }
});

let userlist = $computed(() => {
  if (props.type == 1) {
    return store.currentSystemChars;
  } else {
    return store.lastSystemChars;
  }
});

let killCount = $computed(() => {
  if (props.type == 1) {
    if (store.currentKillCount > 0) {
      return store.currentKillCount;
    } else {
      return 0;
    }
  } else {
    if (store.lastKillCount > 0) {
      return store.lastKillCount;
    } else {
      return 0;
    }
  }
});

let userCount = $computed(() => {
  if (props.type == 1) {
    return store.getCurrentSystemCharsCount;
  } else {
    return store.getLastSystemCharsCount;
  }
});

let showRegion = $computed(() => {
  switch (props.system.system_type[0].id) {
    case 7:
      return true;

    case 8:
      return true;

    case 9:
      return true;

    default:
      return false;
  }
});

let filteredItems = $computed(() => {
  if (props.type == 1) {
    return store.currentSystemSigs.filter((s) => s.delete != 1);
  } else {
    return store.lastSystemSigs.filter((s) => s.delete != 1);
  }
});

let edit = $computed(() => {
  var num = props.lo + 1;
  if (num == store.getLocationCount) {
    return true;
  } else {
    return false;
  }
});

let title = $computed(() => {
  switch (props.type) {
    case 1:
      return "Current System";
      break;
    default:
      return "Last System";
  }
});

let classType = $computed(() => {
  return props.system.system_type[0]["name_full"];
});

let shatterIcon = $computed(() => {
  if (props.system.shattered) {
    return true;
  } else {
    return false;
  }
});

let classText = $computed(() => {
  if (props.system.system_type[0]["id"] < 7 || props.system.system_type[0]["id"] > 9) {
    return "blue-grey darken-2";
  }

  if (props.system.system_type[0]["id"] == 7) {
    return "primay";
  }

  if (props.system.system_type[0]["id"] == 8) {
    return "amber darken-4";
  }

  if (props.system.system_type[0]["id"] == 9) {
    return "deep-orange darken-2";
  }
});

let security = $computed(() => {
  var number = props.system.security;
  var rounded = Math.round(number * 10) / 10;
  if (rounded == 1) {
    return "1.0";
  } else {
    if (rounded == -1) {
      return "-1.0";
    } else {
      return rounded;
    }
  }
});

let securityText = $computed(() => {
  if (security <= 0.0) {
    return "text-negative";
  }

  if (security >= 0.45) {
    return "text-primay";
  }

  return "text-warning";
});

let effect = $computed(() => {
  if (props.system.effect[0]) {
    return props.system.effect[0]["name"];
  }
});

let backColor = $computed(() => {
  return "bg-blue-grey-10";
});

let showStatics = $computed(() => {
  if (props.system.statics.length > 0) {
    return true;
  } else {
    return false;
  }
});

let columns = $ref([
  {
    name: "id",
    label: "ID",
    align: "left",
    field: (row) => row.signature_id,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "group",
    label: "Group",
    align: "left",
    field: (row) => row.group.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "type",
    label: "Type",
    align: "left",
  },
  {
    name: "age",
    label: "Age(HH:MM)",
    field: (row) => row.life_left,
    format: (val) => `${val}`,
    align: "center",
  },
  {
    name: "linkTo",
    label: "Link to",
    align: "left",
    field: (row) => row.leads_to,
    format: (val) => `${val}`,
  },
  {
    name: "mass",
    label: "Mass",
    align: "left",
  },
  {
    name: "size",
    label: "Size",
    align: "left",
  },
  {
    name: "life",
    label: "Life",
    align: "left",
  },
  {
    name: "actions",
    align: "right",
  },
]);
let h = $computed(() => {
  let mins = 100;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.stepTable
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
<style scoped>
.list-enter-active,
.list-leave-active {
  transition: all 0.8s;
}
.list-enter,
.list-leave-to {
  opacity: 0;
  transform: translateY(100%);
}
.list-move {
  transition: transform 0.5s;
}
.item-row {
  display: table-row;
}

.compact-checkbox {
  transform: scale(0.875);
  transform-origin: left;
}

.v-list__tile {
  padding: 0;
}
</style>
