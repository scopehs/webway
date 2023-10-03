<template>
  <div>
    <div class="q-ma-md">
      <div class="row full-width full-height justify-around">
        <div class="col-7">
          <q-table
            title="Hot Areas"
            class="myTableHotAreas myRound bg-webBack"
            :rows="filterdItems"
            :columns="columns"
            table-class=" text-webway"
            table-header-class=" text-weight-bolder bg-amber"
            row-key="id"
            ref="tableRef"
            dark
            dense
            rounded
            color="amber"
            hide-bottom
            :pagination="pagination"
          >
            <template v-slot:top="props">
              <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
                <div class="col-12 flex flex-center">
                  <span class="text-h4">Hot Areas</span>
                </div>
              </div>
              <div class="row full-width justify-between items-start q-pb-xs">
                <div class="col-8 q-mt-md q-mx-md flex flex-center">
                  <q-card flat class="my-card bg-webBack">
                    <q-card-section class="q-pa-none">
                      <div class="text-h6 text-center text-webway">Make a Hotarea</div>
                    </q-card-section>
                    <q-card-actions vertical align="center">
                      <q-btn-group push rounded>
                        <q-btn
                          push
                          label="System"
                          icon="timeline"
                          color="secondary"
                          glossy
                          @click="store.hotAreaSystemShow = !store.hotAreaSystemShow"
                          ><SystemHot
                        /></q-btn>
                        <q-btn
                          push
                          label="Constellation"
                          icon="visibility"
                          color="secondary"
                          glossy
                          @click="
                            store.hotAreaConstellationShow = !store.hotAreaConstellationShow
                          "
                          ><ConstellationHot
                        /></q-btn>
                        <q-btn
                          push
                          label="Region"
                          icon="update"
                          color="secondary"
                          glossy
                          @click="store.hotAreaRegionShow = !store.hotAreaRegionShow"
                          ><RegionHot
                        /></q-btn>
                      </q-btn-group>
                    </q-card-actions>
                  </q-card>
                </div>
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
          </q-table>
        </div>
        <div class="col-4">
          <div class="row full-width full-height">
            <div class="col">
              <div class="row full-width q-mb-md">
                <div class="col">
                  <q-card class="my-card myRound">
                    <q-card-section class="bg-primary text-center text-h6 myCardHeader">
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
              </div>
              <div class="row full-width q-mb-md">
                <div class="col">
                  <q-card class="my-card myRoundTop">
                    <q-card-section class="bg-primary myCardHeader">
                      <div class="text-h6 text-center">Site Setting</div>
                    </q-card-section>
                    <q-card-section>
                      <div class="row full-width">
                        <div class="col">
                          <div class="row full-width">
                            <div class="col-12">
                              <q-toggle
                                v-model="sigSwitch"
                                @update:model-value="updateSigState()"
                                :class="openGasClass"
                                keep-color
                                :color="openGasColor"
                                ><template v-slot:default>
                                  <transition
                                    mode="out-in"
                                    enter-active-class="animate__animated animate__flash animate__slow"
                                    leave-active-class="animate__animated animate__flash animate__slow"
                                  >
                                    <span :key="sigSwitch" :class="openGasText">
                                      Sig Table: {{ sigSwitchText }}</span
                                    >
                                  </transition>
                                </template></q-toggle
                              >
                            </div>
                          </div>
                          <div class="row full-width items-center justify-start">
                            <div class="col-auto">
                              <q-toggle
                                v-model="payGasSwitch"
                                @update:model-value="updateGasState()"
                                class="text-webway q-mr-md"
                                :class="payGasClass"
                                keep-color
                                :color="payGasColor"
                              >
                                <template v-slot:default>
                                  <transition
                                    mode="out-in"
                                    enter-active-class="animate__animated animate__flash animate__slow"
                                    leave-active-class="animate__animated animate__flash animate__slow"
                                  >
                                    <span :key="payGasSwitch" :class="payGasText">
                                      Sig Table: {{ gasSwitchText }}</span
                                    >
                                  </transition>
                                </template></q-toggle
                              >
                            </div>
                            <div class="col-6 q-mr-md">
                              <q-input
                                rounded
                                standout
                                v-model.number="payGasAmount"
                                label="Amount (ISK)"
                                :disable="amountDisable"
                                type="number"
                              />
                            </div>
                            <div class="col-auto">
                              <q-btn
                                color="primary"
                                label="Update"
                                rounded
                                @click="updateGasPayment()"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </q-card-section>
                  </q-card>
                </div>
              </div>
              <div class="row full-width">
                <div class="col">
                  <q-card class="my-card myRoundTop">
                    <q-card-section class="myCardSec myRoundTop">
                      <GasHotTable />
                    </q-card-section>
                  </q-card>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useQuasar } from "quasar";

const SystemHot = defineAsyncComponent(() =>
  import("../components/hotarea/addSystemHot.vue")
);

const ConstellationHot = defineAsyncComponent(() =>
  import("../components/hotarea/addConstellationHot.vue")
);

const RegionHot = defineAsyncComponent(() =>
  import("../components/hotarea/addRegionHot.vue")
);

const GasHotTable = defineAsyncComponent(() =>
  import("../components/hotarea/GasHotTable.vue")
);
const $q = useQuasar();
let store = useMainStore();
onMounted(async () => {
  window.Echo.private("hotarea").listen("HotAreaUpdate", (e) => {
    if (e.flag.flag == 1) {
      store.updateHotArea(e.flag.message);
    }
    if (e.flag.flag == 2) {
      payGas = parseInt(e.flag.message);
    }
    if (e.flag.flag == 3) {
      payGasAmount = parseInt(e.flag.message);
    }
    if (e.flag.flag == 4) {
      store.getNebulaHot();
    }

    if (e.flag.flag == 5) {
      store.getNebulaList();
    }
  });

  await store.setHotArea();
  await store.getRegionList();
  await store.getSystemList();
  await store.getConstellationList();
  await store.getNebulaList();
  await store.getNebulaHot();
  await sigSwitchGet;
  await getPayGasStats();
});
document.title = "Webway - Hot Hot Baby";
onBeforeUnmount(async () => {
  window.Echo.leave("hotarea");
});
let pagination = $ref({
  sortBy: "system",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});
let loading = $ref(true);
let jumpBridges = $ref(null);
let sigSwitch = $ref(null);
let payGas = $ref(null);
let payGasSwitch = $ref(null);
let payGasAmount = $ref(null);
let systemShow = $ref(false);

let getPayGasStats = async () => {
  let res = await axios({
    method: "get",
    withCredentials: true,
    url: "api/paygas",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  payGas = res.data.num;

  let res2 = await axios({
    method: "get",
    withCredentials: true,
    url: "api/paygasamount",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  payGasAmount = res2.data.num;

  if (payGas == 1) {
    payGasSwitch = true;
  } else {
    payGasSwitch = false;
  }
};

let updateSigState = async () => {
  if (sigSwitch) {
    var state = 1;
  } else {
    var state = 0;
  }
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/setSigShow/" + state,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let updateGasState = async () => {
  if (payGasSwitch) {
    var state = 1;
  } else {
    var state = 0;
  }
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/paygas/" + state,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let updateGasPayment = async () => {
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/paygasamount/" + payGasAmount,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(
    $q.notify({
      type: "positive",
      message: "Price Per Gas Site Updated.",
    })
  );
};

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
  });
};

let remove = async (item) => {
  var name = item.name;
  await axios({
    method: "delete",
    withCredentials: true,
    url: "api/hotarea/" + item.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then();
  $q.notify({
    type: "info",
    message: "Area has been cooled",
    icon: "fa-solid fa-temperature-low",
  });

  await store.setHotArea();
};

const sigSwitchGet = $computed(() => {
  if (store.sigShow == 1) {
    sigSwitch = true;
  } else {
    sigSwitch = false;
  }
});

let filterdItems = $computed(() => {
  return store.hotArea;
});

let sigSwitchText = $computed(() => {
  if (store.sigShow == 1) {
    return "Open";
  } else {
    return "Close";
  }
});

let gasSwitchText = $computed(() => {
  if (payGas == 1) {
    return "Paying";
  } else {
    return "Not Paying";
  }
});

let amountDisable = $computed(() => {
  if (payGas == 1) {
    return false;
  } else {
    return true;
  }
});

let columns = $ref([
  {
    name: "system",
    label: "System",
    align: "left",
    field: (row) => {
      if (row.system) {
        return row.system.name;
      } else {
        return "";
      }
    },
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "constellation",
    label: "Constellation",
    align: "left",
    field: (row) => {
      if (row.constellation) {
        return row.constellation.name;
      } else {
        return "";
      }
    },
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "region",
    label: "Region",
    align: "left",
    field: (row) => {
      if (row.region) {
        return row.region.name;
      } else {
        return "";
      }
    },
    format: (val) => `${val}`,
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
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});

let payGasText = $computed(() => {
  return payGasSwitch ? "text-primary" : "text-warning text-strike";
});

let payGasClass = $computed(() => {
  return payGasSwitch ? "" : "my-class";
});

let openGasText = $computed(() => {
  return sigSwitch ? "text-primary" : "text-warning text-strike";
});

let openGasClass = $computed(() => {
  return sigSwitch ? "" : "my-class";
});

let openGasColor = $computed(() => {
  return sigSwitch ? "primary" : "warning";
});

let payGasColor = $computed(() => {
  return payGasSwitch ? "primary" : "warning";
});
</script>

<style lang="scss" scoped>
.my-class,
.q-toggle__thumb {
  border-color: currentColor;
}
</style>

<style lang="sass">
.myCardHeader
    padding-top: 5px !important
    padding-left: 0 !important
    padding-right: 0 !important
    padding-bottom: 5px !important


.myCardSec
    padding-top: 0 !important
    padding-left: 0 !important
    padding-right: 0 !important
    padding-bottom: 0 !important

.myTableHotAreas
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

<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
