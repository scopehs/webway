<template>
  <transition-group
    enter-active-class="animate__animated animate__flash animate__faster"
    leave-active-class="animate__animated animate__flash animate__faster"
  >
    <div v-show="showDrift" :key="`${showDrift}-Drift`">
      Drifter
      <q-btn
        color="primary"
        class="myOutLineButton"
        label="Add"
        @click="click()"
        rounded
        size="xs"
      />
      <q-btn
        color="negative"
        class="myOutLineButton"
        label="No"
        @click="clickNo()"
        rounded
        size="xs"
      />
    </div>
    <div v-show="showJove" :key="`${showJove}-Jove`">
      <div class="row"><div class="col">Jove Observatrory</div></div>
      <div class="row">
        <div class="col">
          <q-btn color="primary" label="Yes" @click="yes()" rounded size="xs" />
          <q-btn color="negative" label="No" @click="no()" rounded size="xs" />
        </div>
      </div>
    </div>
  </transition-group>
</template>

<script setup>
import { date } from "quasar";
import { useMainStore } from "@/store/useMain.js";
import { useQuasar } from "quasar";
const $q = useQuasar();
let store = useMainStore();
const props = defineProps({
  system: Object,
  routeID: Number,
});

let click = () => {
  let now = date.buildDate(true);
  //2022-11-19 01:31:31
  now = date.formatDate(now, "YYYY-MM-DD HH:mm:ss");
  var sig_id = "DRIFT-" + getDrifterNumber;
  var request = {
    signature_id: sig_id,
    name_id: "DRIFT",
    system_id: props.system.system_id,
    signature_group_id: 1,
    name: "Unstable Wormhole",
    life_time: now,
    created_by_name: store.user_name,
    modified_by_id: store.user_id,
  };

  axios({
    method: "POST",
    withCredentials: true,
    url: "api/adddrifter/" + props.system.system_id,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let yes = () => {
  var request = {
    drifter: 1,
    system_id: props.system.system_id,
  };

  var data = {
    drifter: 1,
    id: props.routeID,
  };

  store.updateJove(data);

  axios({
    method: "POST",
    withCredentials: true,
    url: "api/addjove/" + props.routeID,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let no = () => {
  var request = {
    drifter: 0,
    system_id: props.system.system_id,
  };

  var data = {
    drifter: 0,
    id: props.routeID,
  };

  store.updateJove(data);

  axios({
    method: "POST",
    withCredentials: true,
    url: "api/addjove/" + props.routeID,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let clickNo = () => {
  axios({
    method: "POST",
    withCredentials: true,
    url: "api/mainjovesystemno/" + props.system.system_id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  $q.notify({
    type: "positive",
    message: "Thanks for the info",
    position: "top",
    icon: "fa-regular fa-thumbs-up",
  });
};

let showDrift = $computed(() => {
  if (props.system.system_id == store.currentSystemId) {
    if (props.system.jove) {
      if (props.system.jove.drifter == 1) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  } else {
    return false;
  }
});

let getDrifterNumber = $computed(() => {
  return store.getDrifterCount;
});

let classText = $computed(() => {
  var type = props.system.system_type[0]["id"];

  if (type == 7 || type == 8 || type == 9) {
    return true;
  } else {
    return false;
  }
});

let showJove = $computed(() => {
  if (props.system.system_id == store.currentSystemId) {
    if (props.system.jove) {
      return false;
    } else {
      if (classText) {
        return true;
      } else {
        return false;
      }
    }
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for <2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
.compact-checkbox {
  transform: scale(0.875);
  transform-origin: left;
}
</style>
