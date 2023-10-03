<template>
  <div class="q-ma-xs overflow-hidden" @paste="onPaste">
    <transition-group
      mode="out-in"
      enter-active-class="animate__animated animate__zoomIn"
      leave-active-class="animate__animated animate__zoomOut"
    >
      <div
        class="row full-width justify-center"
        v-show="!showSteps"
        :key="`${showSteps} - No`"
      >
        <div class="cols-12">
          <q-card class="myRound">
            <q-card-section class="myCardHeader bg-primary">
              <div class="text-h4 text-center q-px-md">
                YOU ARE NOT TRACKING
                <q-icon color="warning" name="fa-solid fa-exclamation-triangle" />
              </div>
            </q-card-section>
            <q-card-section>
              <span class="text-body1"
                >Make sure you have added a Eve ESI and then selected a Character from the
                drop down in the top left.</span
              >
            </q-card-section>
          </q-card>
        </div>
      </div>
      <div
        class="row full-width"
        no-gutters
        v-show="showSteps"
        :key="`${showSteps} - Yes`"
      >
        <div class="col-12 bg-primary myRoundTop" style="height: 40px">
          <q-tabs
            align="left"
            v-model="lo"
            @update:model-value="changeclick()"
            class="text-white bg-primary full-width myRoundTop"
            ref="myTabsRef"
          >
            <q-tab v-for="(route, index) in routes" :key="`${index}-step`" :name="index">
              {{ route.current_system.name }}
            </q-tab>
          </q-tabs>
        </div>
        <div class="row full-width">
          <div class="col-12">
            <transition
              mode="out-in"
              enter-active-class="animate__animated animate__lightSpeedInRight"
              leave-active-class="animate__animated animate__lightSpeedOutLeft"
            >
              <div
                class="row full-width justify-around"
                no-gutters
                v-if="locationCount > 0"
                :key="`${lo}-row`"
              >
                <div class="col-6" v-if="showLastTable">
                  <SystemTable
                    :key="`${currentSystemRoute_id}-lastTable`"
                    :system="currentSystemRoute_last_system"
                    :type="2"
                    :routeID="currentSystemRoute_id"
                    :lo="lo"
                    :currentSystemPropID="currentSystemRoute_current_system_id"
                    :lastSystemPropID="currentSystemRoute_last_system_id"
                  ></SystemTable>
                </div>

                <div class="col-6">
                  <SystemTable
                    :key="`${currentSystemRoute_id}-currentTable`"
                    :system="currentSystemRoute_current_system"
                    :lastRouteSystemID="currentSystemRoute_last_system_id"
                    :type="1"
                    :routeID="currentSystemRoute_id"
                    :lo="lo"
                    :currentSystemPropID="currentSystemRoute_current_system_id"
                    :lastSystemPropID="currentSystemRoute_last_system_id"
                  ></SystemTable>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { useQuasar } from "quasar";
import { onMounted, onBeforeUnmount, nextTick, defineAsyncComponent, inject } from "vue";
const SystemTable = defineAsyncComponent(() =>
  import("../components/step/systemTable.vue")
);
let store = useMainStore();
const $q = useQuasar();
onMounted(async () => {
  await store.getDrifterTypeList();

  window.Echo.private("char." + store.selectedChar).listen("CharLocationUpdate", (e) => {
    if (e.flag.flag == 1) {
    }

    if (e.flag.flag == 2) {
    }

    if (e.flag.flag == 3) {
    }

    if (e.flag.flag == 4) {
    }

    if (e.flag.flag == 6) {
      esiUpdate = true;
    }

    if (e.flag.flag == 7) {
      esiUpdate = false;
    }

    if (e.flag.flag == 8) {
      testUpdateLocation();
    }
  });

  await store.clearRouteKeeptracking();
  if (store.tracking == true) {
    testUpdateLocation();
  }
});

onBeforeUnmount(async () => {
  window.Echo.leave("char." + store.selectedChar);
});

let lo = $ref(0);
let id = $ref(1);
let name = $ref(null);
let systemSelect = $ref(null);
let lastTemp = $ref(null);
let currentTemp = $ref(null);
let channelLast = $ref(null);
let channelCurrent = $ref(null);
let paste = $ref(null);
let num = $ref(1);
let snack = $ref(false);
let esiUpdate = $ref(false);

let startTracking = async () => {
  store.clearRouteKeeptracking();
  let request = {
    tracking: true,
  };
  await axios({
    method: "post", //you can set what request you want to be
    withCredentials: true,
    url: "/api/updatetrackingchar/" + store.selectedChar,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let changeclick = async () => {
  await nextTick(() => {
    testGetLocationInfo();
  });
};

let keyidcurrent = (route) => {
  var a = route.id;
  var b = lo;
  return a + "-" + b + "- currentTable";
};

let keyidcurrentRow = () => {
  return locationCount + " row ";
};

let keyidlast = (route) => {
  var a = route.id;
  var b = lo;
  return a + "-" + b + "- lastTable";
};

let channeljoin = (type) => {
  if (type == 1) {
    channelCurrent = store.currentSystemId;
    channelLast = lastSystemId;
  }

  if (type == 2) {
    channelCurrent = store.currentTempSystemID;
    channelLast = store.lastTempSystemID;
  }
};

let onPaste = (evt) => {
  paste = evt.clipboardData.getData("text");

  var request = {
    paste: evt.clipboardData.getData("text"),
    system_id: store.currentSystemId,
  };

  axios({
    method: "POST",
    withCredentials: true,
    url: "api/signature/post",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  })
    .then((res) => {
      if (res.status == 200) {
        $q.notify({
          type: "positive",
          message: "Probe Scan has been entered.",
        });
      }
    })
    .catch((res) => {
      if (res.status == 500) {
        $q.notify({
          type: "negative",
          message:
            "Something wrong with the Scan.  Make sure you copied the correct stuff.",
        });
      }
    });
};

let testUpdateLocation = async () => {
  let res = await axios({
    method: "GET",
    withCredentials: true,
    url: "api/getlocationinfo/" + store.selectedChar,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  store.testLocationUpdate(res.data);
  lo = res.data.count - 1;
};

let testGetLocationInfo = async () => {
  let res = await axios({
    method: "GET",
    withCredentials: true,
    url: "api/getlocationinfobytrackid/" + currentSystemRoute_id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  store.testLocationInfo(res.data);
  //   lo = res.data.count - 1;
};

let routes = $computed(() => {
  return store.route;
});

// let showLastTable = $computed(() => {
//   if (store.lastSystemId) {
//     return true;
//   } else {
//     return false;
//   }
// });

let currentSystemRoute_id = $computed(() => {
  var place = lo;
  var r = routes[place];
  return r.id;
});

let currentSystemRoute_last_system = $computed(() => {
  var place = lo;
  var r = routes[place];
  return r.last_system;
});

let currentSystemRoute_current_system = $computed(() => {
  var place = lo;
  var r = routes[place];
  return r.current_system;
});

let currentSystemRoute_current_system_id = $computed(() => {
  var place = lo;
  var r = routes[place];
  return r.current_system_id;
});

let currentSystemRoute_last_system_id = $computed(() => {
  var place = lo;
  var r = routes[place];
  return r.last_system_id;
});

let locationCount = $computed(() => {
  return store.getLocationCount;
});

let showSteps = $computed(() => {
  if (store.tracking) {
    return true;
  } else {
    return false;
  }
});

let showLastTable = $computed(() => {
  var num = lo + 1;
  if (lo > 0) {
    if (routes[lo]["count"] == num) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
