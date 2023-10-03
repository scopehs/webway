<template>
  <div class="q-mx-md q-mt-sm">
    <div class="row full-width">
      <div class="col-12">
        <div class="row">
          <div class="col-3">
            <q-card class="my-card myRoundTop">
              <q-card-section class="bg-primary myCardHeader">
                <div class="text-h4 text-center">Planner</div>
              </q-card-section>
              <q-card-section class="myPlannerOptions">
                <q-select
                  rounded
                  dense
                  standout
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="start_system_id"
                  :options="systemlistStartEnd"
                  label="From"
                  input-debounce="0"
                  clearable
                  ref="fromSystemRef"
                  @filter="filterFnSystemListStart"
                  map-options
                  use-input
                  hide-selected
                  fill-input
                />
              </q-card-section>
              <q-card-section>
                <q-select
                  rounded
                  dense
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="finish_system_id"
                  :options="systemlistFinishEnd"
                  ref="toSystemRef"
                  label="To"
                  @filter="filterFnSystemListStartFinish"
                  map-options
                  use-input
                  hide-selected
                  fill-input
                />
              </q-card-section>
              <q-card-section class="q-py-sm">
                <div class="row full-width flex-center">
                  <div class="col-3 q-pb-sm text-webway">
                    {{ jumpLabel }}
                  </div>
                  <div class="col-9">
                    <q-slider v-model="slider" :min="0" label :max="50" color="primary" />
                  </div></div
              ></q-card-section>
              <q-card-section class="q-py-sm">
                <div class="row">
                  <div class="col">
                    <div class="text-subtitle2 text-webway">Avoid System Type</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <q-chip
                      v-for="(list, index) in wormholeSystemTypeAvoidItem"
                      :key="index"
                      v-model:selected="list.selected"
                      clickable
                      dense
                      :color="selectedColor(list.selected)"
                      outline
                    >
                      <template v-slot:default>
                        <span :class="selctedTextColor(list.selected)">{{
                          list.text
                        }}</span>
                      </template>
                    </q-chip>
                  </div>
                </div>
              </q-card-section>
              <q-card-section class="q-py-sm">
                <div class="row">
                  <div class="col">
                    <div class="text-subtitle2 text-webway">Avoid Size</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <q-chip
                      v-for="(list, index) in wormholeShipSizeAvoidItem"
                      :key="index"
                      dense
                      v-model:selected="list.selected"
                      clickable
                      :color="selectedColor(list.selected)"
                      outline
                    >
                      <template v-slot:default>
                        <span
                          style="fontsize: '15px'"
                          :class="selctedTextColor(list.selected)"
                          >{{ list.text }}</span
                        >
                      </template>
                    </q-chip>
                  </div>
                </div>
              </q-card-section>
              <q-card-section class="q-py-sm">
                <div class="row">
                  <div class="col">
                    <div class="text-subtitle2 text-webway">Avoid Life</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <q-chip
                      v-for="(list, index) in wormholeLifeAvoidItem"
                      :key="index"
                      dense
                      v-model:selected="list.selected"
                      clickable
                      :color="selectedColor(list.selected)"
                      outline
                      ><template v-slot:default>
                        <span :class="selctedTextColor(list.selected)">{{
                          list.text
                        }}</span>
                      </template></q-chip
                    >
                  </div>
                </div>
              </q-card-section>
              <q-card-section class="q-py-sm">
                <div class="row">
                  <div class="col">
                    <div class="text-subtitle2 text-webway">Avoid Mass</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <q-chip
                      v-for="(list, index) in wormholeMassAvoidItem"
                      :key="index"
                      dense
                      v-model:selected="list.selected"
                      clickable
                      :color="selectedColor(list.selected)"
                      outline
                      ><template v-slot:default>
                        <span :class="selctedTextColor(list.selected)">{{
                          list.text
                        }}</span>
                      </template></q-chip
                    >
                  </div>
                </div>
              </q-card-section>
              <q-card-section class="q-py-sm">
                <q-select
                  rounded
                  dense
                  standout
                  input-debounce="0"
                  label-color="webway"
                  option-value="value"
                  option-label="text"
                  v-model="wormholeSystemsAvoid"
                  :options="wormholeSystemsAvoidEnd"
                  label="Avoid System"
                  ref="avoidSystemRef"
                  @filter="wormholeSystemsAvoidStart"
                  map-options
                  use-input
                  use-chips
                  multiple
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
                  </template></q-select
                >
              </q-card-section>
              <q-card-section class="q-py-sm">
                <q-select
                  rounded
                  dense
                  standout
                  label-color="webway"
                  input-debounce="0"
                  option-value="value"
                  option-label="text"
                  v-model="wormholeTitanSystems"
                  :options="wormholeTitanSystemsEnd"
                  label="Titan Systems"
                  ref="avoidSystemRef"
                  @filter="wormholeTitanSystemsStart"
                  map-options
                  use-input
                  use-chips
                  multiple
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
                  </template></q-select
                >
              </q-card-section>
              <q-card-section class="q-py-sm">
                <q-select
                  rounded
                  dense
                  standout
                  label-color="webway"
                  input-debounce="0"
                  option-value="value"
                  option-label="text"
                  v-model="wormholeBlopsSystems"
                  :options="wormholeBlopsSystemsEnd"
                  label="Blops Systems"
                  ref="avoidSystemRef"
                  @filter="wormholeBlopsSystemsStart"
                  map-options
                  use-input
                  use-chips
                  multiple
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
                  </template></q-select
                >
              </q-card-section>
              <q-card-section class="q-py-sm">
                <div class="row">
                  <div class="col" v-if="can('use_trusted_connections')">
                    <q-toggle
                      v-model="useTrusted"
                      keep-color
                      :color="toggleTrustedColor"
                      :class="toggleTrustedClass"
                    >
                      <template v-slot:default>
                        <transition
                          mode="out-in"
                          enter-active-class="animate__animated animate__flash "
                        >
                          <span :class="toggleTrustedTextColor" :key="useTrusted"
                            >Use Trusted</span
                          >
                        </transition>
                      </template></q-toggle
                    >
                  </div>
                  <div class="col">
                    <q-toggle
                      v-model="useJb"
                      :color="toggleJumpColor"
                      :class="toggleJumpClass"
                      keep-color
                    >
                      <transition
                        mode="out-in"
                        enter-active-class="animate__animated animate__flash "
                      >
                        <span :class="toggleJumpTextColor" :key="useJb">Jump Bridge</span>
                      </transition></q-toggle
                    >
                  </div>
                </div>
              </q-card-section>
              <q-card-actions vertical align="center">
                <q-btn
                  color="primary"
                  label="Get Route"
                  @click="submit()"
                  :disabled="routeDisable"
                />
              </q-card-actions>
            </q-card>
          </div>
          <div class="col-6 q-px-md">
            <RouteTable
              :text="text"
              :route="routeReturn"
              :avoidCount="avoidCount"
              :setting="routeSetting"
              :savedLink="shareLink"
              :loading="loading"
              :savedJumps="savedJumps"
              @routeReserved="routeReserved($event)"
              @avoidConnection="connectrionAvoid($event)"
              @clearAvoid="clearAvoid()"
            >
            </RouteTable>
          </div>
          <div class="col-3">
            <div class="row">
              <div class="col"><RouteSavedTable :height="tableHeight" /></div>
            </div>
            <div class="row q-pt-md">
              <div class="col" v-if="can('make_reserved_connection')">
                <RouteReservedTable :height="tableHeight" :connections="connections" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, inject, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useQuasar } from "quasar";

const RouteTable = defineAsyncComponent(() =>
  import("../components/route/routeTable.vue")
);

const RouteSavedTable = defineAsyncComponent(() =>
  import("../components/route/routeSavedTable.vue")
);

const RouteReservedTable = defineAsyncComponent(() =>
  import("../components/route/routeReservedTable.vue")
);
const $q = useQuasar();
let store = useMainStore();
let can = inject("can");
onMounted(async () => {
  window.Echo.private("route." + store.user_id).listen("RouteUpdate", (e) => {
    if (e.flag.flag == 1) {
      shareLink = e.flag.link;
      checkLinkcon();
    }

    if (e.flag.flag == 2) {
      store.getSavedRoutes();
    }

    if (e.flag.flag == 3) {
      store.updateRouteUpdateSystemID(e.flag.systemID);
    }

    if (e.flag.flag == 4) {
      store.getReservedConnection();
    }

    if (e.flag.flag == 5) {
      submit();
    }
  });
  await store.getSystemList();
  await store.getTitanList();
  if (can("make_reserved_connection")) {
    await store.getReservedConnection();
  }

  shareLink = props.share;
  checkLinkcon();
});

onBeforeUnmount(async () => {
  window.Echo.leave("route." + store.user_id);
});

const props = defineProps({
  linkcon: String,
  linkname: String,
  share: String,
});

let fromSystemRef = $ref(null);
let toSystemRef = $ref(null);
let avoidSystemRef = $ref(null);
let savedJumps = $ref();
let text = $ref(null);
let routeReturn = $ref([]);

document.title = "Webway - Routing";

let selectedColor = (selected) => {
  if (selected) {
    return "primary";
  } else {
    return "webChip";
  }
};

let selctedTextColor = (selected) => {
  if (selected) {
    return "text-primary text-weight-thin";
  } else {
    return "text-white text-weight-thin";
  }
};

let wormholeSystemTypeAvoidItem = $ref([
  { text: "Wormhole", value: 1, selected: false },
  { text: "Thera", value: 12, selected: false },
  { text: "Drifter", value: 14, selected: false },
  { text: "Pochven", value: 25, selected: false },
]);

let setWormholeSystemTypeAvoid = (data) => {
  data.forEach((d) => {
    switch (d) {
      case 1:
        wormholeSystemTypeAvoidItem[0].selected = true;
        break;
      case 12:
        wormholeSystemTypeAvoidItem[1].selected = true;
        break;

      case 14:
        wormholeSystemTypeAvoidItem[2].selected = true;
        break;

      case 25:
        wormholeSystemTypeAvoidItem[3].selected = true;
        break;
    }
  });
};

let wormholeShipSizeAvoidItem = $ref([
  { text: "Very Large", value: 1, selected: false },
  { text: "Larger", value: 2, selected: false },
  { text: "Medium", value: 3, selected: false },
  { text: "Frigate", value: 4, selected: false },
]);

let setwormholeShipSizeAvoidItem = (data) => {
  data.forEach((d) => {
    switch (d) {
      case 1:
        wormholeShipSizeAvoidItem[0].selected = true;
        break;
      case 2:
        wormholeShipSizeAvoidItem[1].selected = true;
        break;

      case 3:
        wormholeShipSizeAvoidItem[2].selected = true;
        break;

      case 4:
        wormholeShipSizeAvoidItem[3].selected = true;
        break;
    }
  });
};

let wormholeLifeAvoidItem = $ref([
  { text: "Stage 1", value: 1, selected: false },
  { text: "Stage 2", value: 2, selected: false },
  { text: "Stage 3", value: 3, selected: false },
]);

let setwormholeLifeAvoidItem = (data) => {
  data.forEach((d) => {
    switch (d) {
      case 1:
        wormholeLifeAvoidItem[0].selected = true;
        break;
      case 2:
        wormholeLifeAvoidItem[1].selected = true;
        break;

      case 3:
        wormholeLifeAvoidItem[2].selected = true;
        break;
    }
  });
};

let wormholeMassAvoid = $ref([]);
let wormholeMassAvoidItem = $ref([
  { text: "Stage 1", value: 1, selected: false },
  { text: "Stage 2", value: 2, selected: false },
  { text: "Stage 3", value: 3, selected: false },
]);

let setwormholeMassAvoidItem = (data) => {
  data.forEach((d) => {
    switch (d) {
      case 1:
        wormholeMassAvoidItem[0].selected = true;
        break;
      case 2:
        wormholeMassAvoidItem[1].selected = true;
        break;

      case 3:
        wormholeMassAvoidItem[2].selected = true;
        break;
    }
  });
};

let start_system_id = $ref(null);
let startSystemText = $ref();

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
      start_system_id = systemlistStartEnd[0];
    }
  });
};

let finish_system_id = $ref(null);
let finsishSystemText = $ref();

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
      finish_system_id = systemlistFinishEnd[0];
    }
  });
};

let wormholeSystemsAvoidText = $ref();
let wormholeSystemsAvoid = $ref([]);
let wormholeSystemsAvoidEnd = $computed(() => {
  if (wormholeSystemsAvoidText) {
    return store.systemlist.filter(
      (d) => d.text.toLowerCase().indexOf(wormholeSystemsAvoidText) > -1
    );
  }
  return store.systemlist;
});

let wormholeSystemsAvoidStart = (val, update, abort) => {
  update(() => {
    wormholeSystemsAvoidText = val.toLowerCase();
    if (systemlistFinishEnd.length > 0 && val) {
      finish_system_id = systemlistFinishEnd[0];
    }
  });
};

let wormholeTitanSystemsText = $ref();
let wormholeTitanSystems = $ref([]);
let wormholeTitanSystemsEnd = $computed(() => {
  if (wormholeTitanSystemsText) {
    return store.titanList.filter(
      (d) => d.text.toLowerCase().indexOf(wormholeTitanSystemsText) > -1
    );
  }
  return store.titanList;
});

let wormholeTitanSystemsStart = (val, update, abort) => {
  update(() => {
    wormholeTitanSystemsText = val.toLowerCase();
  });
};

let wormholeBlopsSystems = $ref([]);
let wormholeBlopsSystemsText = $ref();
let wormholeBlopsSystemsEnd = $computed(() => {
  if (wormholeBlopsSystemsText) {
    return store.systemlist.filter(
      (d) => d.text.toLowerCase().indexOf(wormholeBlopsSystemsText) > -1
    );
  }
  return store.systemlist;
});

let wormholeBlopsSystemsStart = (val, update, abort) => {
  update(() => {
    wormholeBlopsSystemsText = val.toLowerCase();
  });
};

let useTrusted = $ref(false);
let toggleTrustedTextColor = $computed(() => {
  return useTrusted ? "text-primary" : " text-warning text-strike";
});
let toggleTrustedColor = $computed(() => {
  return useTrusted ? "primary" : "warning";
});
let toggleTrustedClass = $computed(() => {
  return useTrusted ? "" : "toggleClass";
});

let useJb = $ref(true);
let toggleJumpTextColor = $computed(() => {
  return useJb ? "text-primary" : " text-warning text-strike";
});
let toggleJumpColor = $computed(() => {
  return useJb ? "primary" : "warning";
});
let toggleJumpClass = $computed(() => {
  return useJb ? "" : "toggleClass";
});

let toText = $ref(null);

let fromText = $ref(null);
let shareLink = $ref(null);

let slider = $ref(50);
let routeSetting = $ref(null);
let loading = $ref(false);
let permission = $ref(null);
let avoidConnection = $ref([]);
let useGenesis = $ref(null);

let routeReserved = (jump) => {
  routeReturn[jump].connection.reserved = 1;
  routeReturn[jump].connection.reserved_by_user_id = store.user_id;
};

let checkLinkcon = async () => {
  if (props.linkcon) {
  }

  if (shareLink) {
    loading = true;
    await axios({
      method: "GET",
      withCredentials: true,
      url: "api/savedroute/" + shareLink,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }).then((res) => {
      if (res.data) {
        const json = JSON.parse(res.data.settings);
        finish_system_id = {
          value: res.data.end_system.system_id,
          text: res.data.end_system.name,
        };
        start_system_id = {
          value: res.data.start_system.system_id,
          text: res.data.start_system.name,
        };
        slider = json.max_jumps;
        setWormholeSystemTypeAvoid(json.avoid_system_types);
        setwormholeShipSizeAvoidItem(json.size);
        setwormholeLifeAvoidItem(json.life);
        setwormholeMassAvoidItem(json.mass);
        wormholeSystemsAvoid = json.avoid_systems;
        wormholeTitanSystems = json.titan_systems;
        wormholeBlopsSystems = json.blops_systems;
        useTrusted = json.trusted;
        useJb = json.jump_bridge;
        wormholeSystemsAvoid = json.avoid_systems;
        if (json.permission) {
          permission = json.permission;
        } else {
          if (can("use_reserved_connection")) {
            permission = 1;
          } else {
            permission = 0;
          }
        }
        if (json.aviod_connections) {
          avoidConnection = json.aviod_connections;
        } else {
          avoidConnection = [];
        }
        if (json.use_genesis) {
          useGenesis = json.use_genesis;
        } else {
          if (can("use_genesis")) {
            useGenesis = true;
          } else {
            useGenesis = false;
          }
        }

        submit(1);
      } else {
        loading = 0;
        oldLink();
      }
    });
  }
};

let submit = async (n) => {
  loading = true;
  var systemTypeAvoid = [];
  var shipSizeAvoid = [];
  var lifeAvoid = [];
  var massAvoid = [];
  if (wormholeSystemTypeAvoidItem[0].selected) {
    systemTypeAvoid.push(1);
    systemTypeAvoid.push(2);
    systemTypeAvoid.push(3);
    systemTypeAvoid.push(4);
    systemTypeAvoid.push(5);
    systemTypeAvoid.push(6);
  }

  if (wormholeSystemTypeAvoidItem[1].selected) {
    systemTypeAvoid.push(12);
  }

  if (wormholeSystemTypeAvoidItem[2].selected) {
    systemTypeAvoid.push(14);
    systemTypeAvoid.push(15);
    systemTypeAvoid.push(16);
    systemTypeAvoid.push(17);
    systemTypeAvoid.push(18);
  }

  if (wormholeSystemTypeAvoidItem[3].selected) {
    systemTypeAvoid.push(25);
  }

  if (wormholeShipSizeAvoidItem[0].selected) {
    shipSizeAvoid.push(1);
  }
  if (wormholeShipSizeAvoidItem[1].selected) {
    shipSizeAvoid.push(2);
  }
  if (wormholeShipSizeAvoidItem[2].selected) {
    shipSizeAvoid.push(3);
  }
  if (wormholeShipSizeAvoidItem[3].selected) {
    shipSizeAvoid.push(4);
  }

  if (wormholeLifeAvoidItem[0].selected) {
    lifeAvoid.push(1);
  }
  if (wormholeLifeAvoidItem[1].selected) {
    lifeAvoid.push(2);
  }
  if (wormholeLifeAvoidItem[2].selected) {
    lifeAvoid.push(3);
  }

  if (wormholeMassAvoidItem[0].selected) {
    massAvoid.push(1);
  }
  if (wormholeMassAvoidItem[1].selected) {
    massAvoid.push(2);
  }
  if (wormholeMassAvoidItem[2].selected) {
    massAvoid.push(3);
  }

  if (n == 1) {
    var request = {
      start_system_id: start_system_id.value,
      finish_system_id: finish_system_id.value,
      mass: massAvoid,
      avoid_system_types: systemTypeAvoid,
      life: lifeAvoid,
      size: shipSizeAvoid,
      jump_bridge: useJb,
      trusted: useTrusted,
      avoid_systems: wormholeSystemsAvoid,
      titan_systems: wormholeTitanSystems,
      blops_systems: wormholeBlopsSystems,
      max_jumps: slider,
      link: shareLink,
      permission: permission,
      aviod_connections: avoidConnection,
      use_genesis: useGenesis,
    };
    permission = null;
    useGenesis = false;
  } else {
    if (can("use_reserved_connection")) {
      permission = 1;
    }

    if (can("use_genesis")) {
      useGenesis = true;
    }
    var request = {
      start_system_id: start_system_id.value,
      finish_system_id: finish_system_id.value,
      mass: massAvoid,
      avoid_system_types: systemTypeAvoid,
      life: lifeAvoid,
      size: shipSizeAvoid,
      jump_bridge: useJb,
      trusted: useTrusted,
      avoid_systems: wormholeSystemsAvoid,
      titan_systems: wormholeTitanSystems,
      blops_systems: wormholeBlopsSystems,
      max_jumps: slider,
      link: null,
      permission: permission,
      aviod_connections: avoidConnection,
      use_genesis: useGenesis,
    };
  }

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/path",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    routeSetting = request;
    routeReturn = res.data.route;
    toText = start_system_id.text;
    fromText = finish_system_id.text;
    shareLink = res.data.link;
    savedJumps = res.data.jumps_saved;
    loading = false;
    text = res.data.text;
  });
};

let oldLink = () => {
  //   $toast.error("This Link is no longer Vaild", {
  //     position: "top-center",
  //     timeout: 2000,
  //     closeOnClick: true,
  //     pauseOnFocusLoss: true,
  //     pauseOnHover: true,
  //     draggable: false,
  //     draggablePercent: 0.6,
  //     showCloseButtonOnHover: false,
  //     hideProgressBar: false,
  //     closeButton: "button",
  //     icon: "fas fa-clipboard-check",
  //     rtl: false,
  //   });

  $q.notify({
    message: "This Link is no longer Vaild",
    color: "purple",
    type: "negative",
  });
};

let connectrionAvoid = ($event) => {
  routeReturn = [];
  const data = avoidConnection;
  data.push($event);
  avoidConnection = data;
  submit();
};

let clearAvoid = () => {
  avoidConnection = [];
  routeReturn = [];
  submit();
};

let showSystemTypeAviod = $computed(() => {
  let show = false;

  wormholeSystemTypeAvoidItem.forEach((w) => {
    if (w.selected) {
      show = true;
    }
  });
  return show;
});

let avoidCount = $computed(() => {
  const data = avoidConnection;
  let length = data.length;
  return length;
});

let routeDisable = $computed(() => {
  if (start_system_id && finish_system_id) {
    return false;
  } else {
    return true;
  }
});

let jumpLabel = $computed(() => {
  return "Max Jumps: " + slider;
});

let showWormholeMassAvoid = $computed(() => {
  let show = false;

  wormholeMassAvoidItem.forEach((w) => {
    if (w.selected) {
      show = true;
    }
  });
  return show;
});

let showWormholeShipSizeAvoid = $computed(() => {
  let show = false;

  wormholeShipSizeAvoidItem.forEach((w) => {
    if (w.selected) {
      show = true;
    }
  });
  return show;
});

let showWormholeLifeAvoid = $computed(() => {
  let show = false;

  wormholeLifeAvoidItem.forEach((w) => {
    if (w.selected) {
      show = true;
    }
  });
  return show;
});

let connections = $computed(() => {
  return store.reservedConnections;
});

let tableHeight = $computed(() => {
  let hi = 0;
  if (can("make_reserved_connection")) {
    hi = (store.size.height - 250) / 2;
    hi = hi + 80;
  } else {
    hi = store.size.height - 250;
  }
  return hi;
});

let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myPlannerCard
  /* height or max-height is important */
  height: v-bind(h)
</style>

<style lang="scss" scoped>
.toggleClass,
.q-toggle__thumb {
  border-color: currentColor;
}
</style>
