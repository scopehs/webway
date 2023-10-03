<template>
  <div>
    <q-table
      title="Connections"
      :loading="loading"
      class="myRound bg-webBack myTableRouter"
      :rows="props.route"
      :columns="columns"
      table-class="text-webway"
      table-header-class="bg-amber"
      row-key="id"
      dense
      dark
      ref="tableRef"
      rounded
      color="amber"
      :pagination="pagination"
    >
      <template v-slot:bottom="items">
        <div class="row full-width justify-around align-baseline">
          <div class="col flex items-baseline q-pt-sm">
            You are avoiding {{ props.avoidCount }} connections
            <q-btn
              flat
              class="q-ml-xs"
              padding="none"
              no-caps
              color="info"
              small
              v-if="props.avoidCount > 0"
              label=" - clear"
              @click="clearAvoid()"
            />
          </div>
          <div class="col">
            <div class="row full-width justify-end q-gutter-sm">
              <div class="col-auto">
                <q-btn color="positive" label="share" rounded @click="button()" />
              </div>
              <div class="col-auto">
                <q-btn color="primary" label="save" rounded @click="saveButton()" />
              </div>
              <div class="col-auto">
                <q-btn color="warning" label="copy" rounded @click="copyText()" />
              </div>
            </div>
          </div>
        </div>
      </template>

      <template v-slot:top="props">
        <div class="row full-width flex-center q-py-xs myRoundTop bg-primary">
          <div class="col-10 flex flex-center">
            <span class="text-h4">Route </span
            ><span class="text-h6" v-if="savedJumps > 0">
              - Saved a total of {{ savedJumps }} Jumps.</span
            >
          </div>
          <div class="col-2 flex justify-end">
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
        <div class="row full-width justify-between items-center q-pb-xs"></div>
      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="jumpNumber" :props="props">
            <CellRouteJumpNumber :item="props.row"></CellRouteJumpNumber>
          </q-td>
          <q-td key="system" :props="props">
            <CellRouteName :item="props.row"></CellRouteName>
          </q-td>
          <q-td key="region" :props="props">
            <CellRouteRegion :item="props.row"></CellRouteRegion>
          </q-td>
          <q-td key="type" :props="props">
            <CellRouteSystemType :item="props.row"></CellRouteSystemType>
          </q-td>
          <q-td key="jumpType" :props="props">
            <CellRouteJumpType :item="props.row"></CellRouteJumpType>
          </q-td>
          <q-td key="sig" :props="props">
            <CellRouteSig :item="props.row"> </CellRouteSig>
          </q-td>
          <q-td key="age" :props="props">
            <CellRouteAge :item="props.row"></CellRouteAge>
          </q-td>
          <q-td key="ship" :props="props">
            <CellRouteShipSize :item="props.row"></CellRouteShipSize>
          </q-td>
          <q-td key="life" :props="props">
            <CellRouteLife :item="props.row"></CellRouteLife>
          </q-td>
          <q-td key="mass" :props="props">
            <CellRouteMass :item="props.row"></CellRouteMass>
          </q-td>
          <q-td key="actions" :props="props">
            <RouteButton
              :item="props.row"
              @avoidConnection="connectionAvoid($event)"
              @routeReserved="routeReserved($event)"
            ></RouteButton>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { copyToClipboard, useQuasar } from "quasar";
import { useMainStore } from "@/store/useMain.js";
import { inject } from "vue";
import { defineAsyncComponent } from "vue";
const emit = defineEmits(["clearAvoid", "routeReserved", "avoidConnection"]);
let store = useMainStore();
const $q = useQuasar();
let can = inject("can");
const props = defineProps({
  route: Array,
  setting: Object,
  savedLink: String,
  loading: Boolean,
  avoidCount: Number,
  text: String,
  savedJumps: Number,
});

const CellRouteAge = defineAsyncComponent(() => import("../route/cellRouteAge.vue"));
const CellRouteJumpNumber = defineAsyncComponent(() =>
  import("../route/cellRouteJumpNumber.vue")
);
const CellRouteJumpType = defineAsyncComponent(() =>
  import("../route/cellRouteJumpType.vue")
);
const CellRouteLife = defineAsyncComponent(() => import("../route/cellRouteLife.vue"));
const CellRouteMass = defineAsyncComponent(() => import("../route/cellRouteMass.vue"));
const CellRouteName = defineAsyncComponent(() => import("../route/cellRouteName.vue"));
const CellRouteRegion = defineAsyncComponent(() =>
  import("../route/cellRouteRegion.vue")
);
const CellRouteShipSize = defineAsyncComponent(() =>
  import("../route/cellRouteShipSize.vue")
);
const CellRouteSig = defineAsyncComponent(() => import("../route/cellRouteSig.vue"));
const CellRouteSystemType = defineAsyncComponent(() =>
  import("../route/cellRouteSystemType.vue")
);
const RouteButton = defineAsyncComponent(() => import("../route/routeButton.vue"));

let test = $ref("dadada");

let pagination = $ref({
  sortBy: "jumpNumber",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let clearAvoid = () => {
  emit("clearAvoid");
};

let showMenuButton = (item) => {
  if (item.connection) {
    return true;
  } else {
    return false;
  }
};

let copyText = () => {
  copyToClipboard(props.text).then(() => {
    $q.notify({
      type: "info",
      message: props.text + " Copied to your clipboard to paste into eve",
    });
  });
};

let routeReserved = ($event) => {
  emit("routeReserved", $event);
};

let connectionAvoid = ($event) => {
  emit("avoidConnection", $event);
};

let button = () => {
  var text = "https://webway.apps.gnf.lt/routing?share=" + props.savedLink;
  copyToClipboard(text).then(() => {
    $q.notify({
      type: "info",
      message: text,
    });
  });
};

let saveButton = async () => {
  var request = {
    link: props.savedLink,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/saveroute",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    $q.notify({
      type: "positive",
      message: "Route has been Saved",
    });
  });
};

let disableButtons = $computed(() => {
  if (props.route && props.savedLink) {
    return false;
  } else {
    return true;
  }
});

let columns = $ref([
  {
    name: "jumpNumber",
    label: "*",
    align: "left",
  },
  {
    name: "system",
    label: "System",
    align: "left",
  },
  {
    name: "region",
    label: "Region",
    align: "left",
  },
  {
    name: "type",
    label: "Type",
    align: "left",
  },
  {
    name: "jumpType",
    label: "Jump",
    align: "left",
  },
  {
    name: "sig",
    label: "Sig",
    align: "left",
  },
  {
    name: "age",
    label: "Age",
    align: "left",
  },
  {
    name: "ship",
    label: "Ship",
    align: "left",
  },
  {
    name: "life",
    label: "Life (H)",
    align: "left",
  },
  {
    name: "mass",
    label: "Mass (%)",
    align: "left",
  },
  {
    name: "actions",
    label: "",
    align: "right",
  },
]);
let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myTableRouter
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
