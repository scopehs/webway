<template>
  <div>
    <q-btn color="webway" round flat padding="none" icon="fa-solid fa-ellipsis-vertical">
      <q-menu v-model="menu">
        <q-list style="min-width: 100px">
          <q-item v-if="showWayPoint(item)" v-close-popup class="q-pa-none">
            <q-btn
              flat
              icon="fa-solid fa-map-pin"
              label="Set Waypoint"
              @click="setWayPoint(item)"
              v-close-popup
            />
          </q-item>

          <q-item class="q-pa-none" v-if="showReserved(item)">
            <q-btn
              icon="fa-solid fa-code-branch"
              flat
              label="Reserve Connection"
              @click="reserveConnection(item)"
              v-close-popup
            />
          </q-item>
          <q-item class="q-pa-none" v-if="showAvoid(item)">
            <q-btn
              icon="fa-solid fa-database"
              flat
              label="Avoid Connection"
              @click="connectionAvoid(item)"
              v-close-popup
            />
          </q-item>
          <q-item class="q-pa-none" v-if="showReserved(item)">
            <q-item-section>
              <ConnectionRating
                @feedbackclosed="menu = false"
                :item="item"
              ></ConnectionRating>
            </q-item-section>
          </q-item>
          <q-item class="q-pa-none" v-if="showReserved(item)">
            <q-item-section>
              <RouteInfoPasteRoute @infoClosed="test()" :item="item"></RouteInfoPasteRoute
            ></q-item-section>
          </q-item>
          <q-item class="q-pa-none" v-if="showDelete(item)">
            <q-btn
              icon="fa-solid fa-minus-circle"
              color="warning"
              flat
              label="Connection Gone"
              @click="deleteConnection(item)"
              v-close-popup
            />
            <q-item-section></q-item-section>
          </q-item>
          <q-item class="q-pa-none" v-if="showReport(item)">
            <q-btn
              icon="fa-solid fa-minus-circle"
              color="warning"
              flat
              label="Connection Gone"
              @click="reportConnection(item)"
              v-close-popup
            />
          </q-item>
        </q-list>
      </q-menu>
    </q-btn>
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { inject, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
const $q = useQuasar();
let store = useMainStore();
const props = defineProps({
  item: Object,
});
const RouteInfoPasteRoute = defineAsyncComponent(() =>
  import("../route/routeInfoPasteRoute.vue")
);
const ConnectionRating = defineAsyncComponent(() =>
  import("../route/connectionRate.vue")
);
let can = inject("can");
const emit = defineEmits(["avoidConnection"]);
let menu = $ref(false);
let rating = $ref(0);
let showWayPoint = (item) => {
  if (props.item.solar_system) {
    switch (props.item.solar_system.system_type[0]["name"]) {
      case "NS":
        return true;

      case "LS":
        return true;

      case "HS":
        return true;

      case "Poch":
        return true;
    }
  }

  return false;
};

let connectionAvoid = (item) => {
  emit("avoidConnection", item.connection.id);
};

let test = () => {
  menu = false;
};

let reserveConnection = async (item) => {
  menu = false;
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/reserveconnection/" + props.item.connection.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    this.$emit("routeReserved", props.item.jump);
  });
};

let deleteConnection = async (item) => {
  await axios({
    method: "DELETE",
    withCredentials: true,
    url: "api/deleteConnection/" + props.item.connection.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    $q.notify({
      type: "info",
      message: "Connection removed and route recalculated.",
    });
  });
  connectionAvoid(item);
};

let reportConnection = async (item) => {
  await axios({
    method: "GET",
    withCredentials: true,
    url: "api/reportConnection/" + props.item.connection.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    $q.notify({
      type: "info",
      message: "Connection reported, added to your avoid list and route recalculated.",
    });
  });
  connectionAvoid(item);
};

let showReserved = (item) => {
  if (can("make_reserved_connection")) {
    if (props.item.connection) {
      switch (item.connection.type.id) {
        case 2:
          return true;

        case 4:
          return true;

        case 5:
          return true;
      }
    }
  }

  return false;
};

let showDelete = (item) => {
  if (can("delete_connections")) {
    if (props.item.connection) {
      switch (props.item.connection.type.id) {
        case 2:
          return true;

        case 4:
          return true;

        case 5:
          return true;
      }
    }
  }

  return false;
};

let showReport = (item) => {
  if (!can("delete_connections")) {
    if (props.item.connection) {
      switch (props.item.connection.type.id) {
        case 2:
          return true;

        case 4:
          return true;

        case 5:
          return true;
      }
    }
  }

  return false;
};

let showAvoid = (item) => {
  if (props.item.connection) {
    switch (props.item.connection.type.id) {
      case 2:
        return true;

      case 3:
        return true;

      case 4:
        return true;

      case 5:
        return true;
    }
  }

  return false;
};

let setWayPoint = async (item) => {
  menu = false;
  var request = {
    system_id: props.item.solar_system.system_id,
    character_id: store.selectedChar,
    add_to_beginning: false,
    clear_other_waypoints: true,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/setwaypoint",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then((res) => {
    var text = "Waypoint set to " + props.item.solar_system.name;

    $q.notify({
      type: "info",
      message: text,
    });
  });
};
</script>

<style lang="scss"></style>
