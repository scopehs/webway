<template>
  <q-btn
    color="webway"
    icon="fa-solid fa-map-location-dot"
    label="Mapping"
    flat
    @click="clickOpen()"
  />
  <q-dialog v-model="open" @hide="turnOnForce">
    <q-card
      class="no-margin"
      style="max-width: 1500px; width: 2500px; height: 900px"
      flat
      dark
      square
    >
      <q-card-section class="row items-center">
        <v-network-graph
          ref="mapRef"
          v-model:zoom-level="zoomLevel"
          class="graph"
          :nodes="store.routeNodes"
          :layouts="layouts"
          :edges="store.routeLinks"
          :configs="configs"
        />
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { onMounted, inject, reactive, ref } from "vue";
import { useMainStore } from "@/store/useMain.js";
import * as vNG from "v-network-graph";
import { ForceLayout } from "v-network-graph/lib/force-layout";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
let store = useMainStore();
let can = inject("can");
let nodeFixed = $ref(false);
onMounted(async () => {});

const zoomLevel = ref(1.5);

let open = $ref(false);
let mapRef = $ref();
let type = $ref(true);

let turnOffForce = () => {
  d3ForceEnabled = false;
};

let turnOnForce = () => {
  d3ForceEnabled = true;
};

let layouts = $ref({
  nodes: {
    // Unaffected by force
  },
});

let d3ForceEnabled = $computed({
  get: () => configs.view.layoutHandler instanceof ForceLayout,
  set: (value) => {
    if (value) {
      configs.view.layoutHandler = new ForceLayout();
    } else {
      configs.view.layoutHandler = new vNG.SimpleLayout();
    }
  },
});

const configs = reactive(
  vNG.defineConfigs({
    node: {
      label: { direction: "center", color: "#fff" },
      normal: {
        color: (n) => (n.current === true ? "#ff0000" : "#4466cc"),
      },
    },
    view: {
      layoutHandler: new ForceLayout(),
      scalingObjects: true,
      minZoomLevel: 0.1,
      maxZoomLevel: 16,
    },

    edge: {
      normal: {
        color: "#aaa",
        width: 3,
      },
    },
  })
);

let clickOpen = async () => {
  await store.setRouteMapping().then((open = true));
  setTimeout(() => {
    turnOffForce();
  }, 1000);
};
</script>

<style>
.graph {
  width: 90%;
  height: 800px;
}
</style>

<style scoped>
#jim {
  position: absolute;
  top: 0px;
  bottom: 0px;
  width: 100%;
}
</style>
