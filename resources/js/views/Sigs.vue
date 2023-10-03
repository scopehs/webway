<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      :loading="loading"
      class="myRound bg-webBack myTableAllSigs"
      :rows="filterend"
      :columns="columns"
      table-class="text-webway"
      table-header-class="bg-amber"
      row-key="id"
      dense
      dark
      :filter="search"
      ref="tableRef"
      rounded
      hide-bottom=""
      color="amber"
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-py-xs myRoundTop bg-primary">
          <div class="col-10 flex flex-center">
            <span class="text-h4">Signatures </span>
          </div>
        </div>
        <div class="row full-width q-pt-xs justify-between">
          <div class="col-auto flex q-pl-xs justify-start">
            <q-input
              rounded
              standout
              dense
              debounce="300"
              v-model="search"
              clearable
              placeholder="Search"
            >
              <template v-slot:append>
                <q-icon name="fa-solid fa-magnifying-glass" />
              </template>
            </q-input>
          </div>
          <div class="col-auto">
            <div class="row full-wdith justify-around items-center">
              <div class="col-auto">
                <span class="text-h4 text-webway"
                  >Ratting rights still apply - check them
                  <a href="https://wiki.goonfleet.com/Ratting_Rights" target="_blank">
                    here</a
                  ></span
                >
              </div>
              <div class="col-auto q-pt-xs q-pl-sm">
                <q-btn-group rounded>
                  <q-btn
                    v-for="(button, index) in filterButtons"
                    :key="index"
                    :color="button.color"
                    :label="button.label"
                    @click="setActive(button, index)"
                  ></q-btn>
                </q-btn-group>
              </div>
            </div>

            <div v-if="useSigID" class="row full-wdith">
              <div class="col-12 q-pt-sm flex flex-center">
                <q-btn
                  color="warning"
                  rounded
                  label="show all sigs"
                  @click="clearSigIDButton()"
                />
              </div>
            </div>
          </div>
          <div class="col-auto flex justify-end self-start">
            <div class="col-auto q-pt-xs q-pl-sm"><SigGasTable /></div>
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
      </template>

      <template v-slot:body-cell-region="item">
        <q-td :props="item">
          <div class="text-no-wrap">{{ item.value }}</div>
        </q-td>
      </template>

      <template v-slot:body-cell-sig="item">
        <q-td :props="item">
          <div class="text-no-wrap">{{ item.value }}</div>
        </q-td>
      </template>

      <template v-slot:body-cell-reserved="item">
        <q-td :props="item">
          <q-tabs outside-arrows style="25px; max-width: 400px; width: 400px">
            <SigReserveTag :reserves="item.row.reserve" />
          </q-tabs>
        </q-td>
      </template>

      <template v-slot:body-cell-button="item">
        <q-td :props="item">
          <SigReserveButton v-if="showReserveButton(item.row)" :item="item.row" />
        </q-td>
      </template>

      <template v-slot:body-cell-actions="item">
        <q-td :props="item">
          <div class="row justify-end q-gutter-sm">
            <div class="col-auto"><SigRouteButton :item="item.row" /></div>
            <div class="col-auto"><SigDeleteButton :item="item.row" /></div>
          </div>
        </q-td>
      </template>

      <template v-slot:body-cell-type="item">
        <q-td :props="item">
          <div class="text-no-wrap">{{ fixTypeName(item.value) }}</div>
        </q-td>
      </template>

      <template v-slot:body-cell-system="item">
        <q-td :props="item">
          <div class="text-no-wrap">{{ item.value }}</div>
        </q-td>
      </template>

      <template v-slot:body-cell-name="item">
        <q-td :props="item">
          <div class="text-no-warp">
            <SigReportedIcon :item="item" side="l" />
            {{ item.value }} <SigReportedIcon :item="item" side="r" />
          </div>
        </q-td>
      </template>

      <template v-slot:body-cell-age="item">
        <q-td :props="item">
          <VueCountUp :interval="60000" :time="item.value" v-slot="{ hours, minutes }">
            <span class="text-negative">{{ hours }}:{{ minutes }}</span>
          </VueCountUp>
        </q-td>
      </template>

      <template v-slot:body-cell-updated="item">
        <q-td :props="item">
          <VueCountUp :interval="60000" :time="item.value" v-slot="{ hours, minutes }">
            <span class="text-negative">{{ hours }}:{{ minutes }}</span>
          </VueCountUp>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, inject, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useRouter } from "vue-router";
import { date } from "quasar";
let router = useRouter();
let store = useMainStore();
let can = inject("can");
const props = defineProps({
  sigID: String,
});
const VueCountUp = defineAsyncComponent(() => import("../components/countup/index"));
const SigReportedIcon = defineAsyncComponent(() =>
  import("../components/sigs/SigReportedIcon.vue")
);
const SigReserveTag = defineAsyncComponent(() =>
  import("../components/sigs/SigReserveTag.vue")
);
const SigReserveButton = defineAsyncComponent(() =>
  import("../components/sigs/SigReserveButton.vue")
);
const SigDeleteButton = defineAsyncComponent(() =>
  import("../components/sigs/SigDeleteButton.vue")
);
const SigRouteButton = defineAsyncComponent(() =>
  import("../components/sigs/SigRouteButton.vue")
);

const SigGasTable = defineAsyncComponent(() =>
  import("../components/sigs/SigGasTable.vue")
);

document.title = "Webway - All Connections";

onMounted(async () => {
  await accessCheck;
  setSigID();

  if (can("use_reserved_connection")) {
    window.Echo.private("sigsp").listen("SigspUpdate", (e) => {
      if (e.flag.flag == 1) {
        store.updateSigs(e.flag.message);
      }

      if (e.flag.flag == 2) {
        store.deleteSigs(e.flag.message);
      }

      if (e.flag.flag == 3) {
      }

      if (e.flag.flag == 4) {
      }

      if (e.flag.flag == 6) {
      }

      if (e.flag.flag == 7) {
      }

      if (e.flag.flag == 8) {
      }
    });
  } else {
    window.Echo.private("sigs").listen("SigsUpdate", (e) => {
      if (e.flag.flag == 1) {
        store.updateSigs(e.flag.message);
      }

      if (e.flag.flag == 2) {
        store.deleteSigs(e.flag.message);
      }

      if (e.flag.flag == 3) {
      }

      if (e.flag.flag == 4) {
      }

      if (e.flag.flag == 6) {
      }

      if (e.flag.flag == 7) {
      }

      if (e.flag.flag == 8) {
      }
    });
  }

  store.getSigs().then((loading = false));
});

onBeforeUnmount(async () => {
  window.Echo.leave("sigsp");
  window.Echo.leave("sigs");
});

let searchSigID = $ref(null);
let loading = $ref(true);
let accessCheck = $computed(() => {
  if (store.sigShow == 0 && can("view_sigs")) {
    router.push("/mapping");
  }
});

let setSigID = () => {
  if (props.sigID) {
    searchSigID = parseInt(props.sigID);
  }
};
let pagination = $ref({
  descending: false,
  page: 1,
  rowsPerPage: 0,
});
onMounted(async () => {
  await store.getGasInfo();
});

let clearSigID = $ref(0);

let clearSigIDButton = () => {
  clearSigID = 1;
};

let filterButtons = $ref([
  {
    label: "Combat",
    color: "webDark",
    onOff: false,
    value: 5,
  },
  {
    label: "Data",
    color: "webDark",
    onOff: false,
    value: 3,
  },
  {
    label: "Gas",
    color: "webDark",
    onOff: false,
    value: 4,
  },
  {
    label: "Ore",
    color: "webDark",
    onOff: false,
    value: 6,
  },
  {
    label: "Relic",
    color: "webDark",
    onOff: false,
    value: 2,
  },
]);

let setActive = (button, index) => {
  if (button.onOff) {
    filterButtons[index].color = "webDark";
    filterButtons[index].onOff = false;
  } else {
    filterButtons[index].color = "primary";
    filterButtons[index].onOff = true;
  }
};

let showReserveButton = (item) => {
  var reserveCount = item.reserve.length;
  var showButton = false;

  if (reservedCheck) {
    return false;
  }
  if (reserveCount > 0) {
    var reserved = item.reserve;
    reserved = reserved.filter((r) => r.user_id == store.user_id);
    if (reserved.length == 1) {
      showButton = false;
    } else {
      return true;
    }
  } else {
    showButton = true;
  }

  return showButton;
};

let tagNumber = (item) => {
  if (showReserveButton(item)) {
    return 10;
  } else {
    return 12;
  }
};

let fixTypeName = (name) => {
  var ret = name.replace("Site", "");
  return ret;
};

let filteredItemsMid = $computed(() => {
  let data = [];
  let check = [];
  check = filterButtons.filter((f) => f.onOff == true);
  if (check.length != 0) {
    check.forEach((p) => {
      let pick = store.sigs.filter((f) => f.group.id == p.value);
      if (pick != null) {
        pick.forEach((pk) => {
          data.push(pk);
        });
      }
    });
    return data;
  }
  return store.sigs;
});

let useSigID = $computed(() => {
  if (clearSigID == 0 && searchSigID) {
    return true;
  } else {
    return false;
  }
});

let filterend = $computed(() => {
  if (useSigID) {
    return store.sigs.filter((s) => s.id == searchSigID);
  } else {
    return filteredItemsMid;
  }
});

let reservedCheck = $computed(() => {
  var data = [];
  store.sigs.forEach((s) => {
    var count = s.reserve.length;
    if (count > 0) {
      s.reserve.forEach((r) => {
        var id = r.user_id;
        data.push(id);
      });
    }
  });

  data = data.filter(function (item, pos) {
    return data.indexOf(item) == pos;
  });

  var check = data.filter((d) => d == store.user_id);
  if (check == 0) {
    return false;
  } else {
    return true;
  }
});

let search = $computed({
  get() {
    return store.sigTableSearch;
  },
  set(newValue) {
    return store.updateSigTableSearch(newValue);
  },
});

let overlay = $computed(() => {
  get: () => {
    return store.gasSigInfoShow;
  };
  set: (newValue) => {
    return store.updateGasInfoShow(newValue);
  };
});

let columns = $ref([
  {
    name: "region",
    label: "Region",
    align: "left",
    field: (row) => row.solar_system.region.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "system",
    label: "System",
    align: "left",
    field: (row) => row.solar_system.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "sig",
    label: "Sig",
    align: "left",
    field: (row) => row.signature_id,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "type",
    label: "Type",
    align: "left",
    field: (row) => row.group.name,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "name",
    label: "Name",
    align: "left",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "reserved",
    label: "Reserved",
    headerStyle: "max-width: 50px",
    align: "left",
    field: (row) => row.reserve,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "button",
    label: "",
    align: "left",
    sortable: true,
  },

  {
    name: "age",
    label: "Age",
    align: "left",
    field: (row) => row.created_at,
    format: (val) => {
      let date = new Date(val);
      let timestamp = date.getTime();
      return timestamp;
    },
    sortable: true,
  },

  {
    name: "updated",
    label: "Updated",
    align: "left",
    field: (row) => row.updated_at,
    format: (val) => {
      let date = new Date(val);
      let timestamp = date.getTime();
      return timestamp;
    },
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
