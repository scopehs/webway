<template>
  <div v-if="showButton">
    <q-btn
      v-if="showDelete"
      flat
      round
      push
      size="sm"
      padding="none"
      color="warning"
      icon="fa-solid fa-trash-alt"
      @click="done()"
    />
    <q-btn
      v-else
      flat
      round
      push
      size="sm"
      padding="none"
      color="warning"
      icon="fa-solid fa-trash-alt"
      @click="report()"
    />
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let can = inject("can");
const $q = useQuasar();
let store = useMainStore();
const props = defineProps({
  item: [Array, Object],
});

let done = async () => {
  var sigName = props.item.name_id;
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/sigdone/" + props.item.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  var text = "Sig " + sigName + " has been removed";
  $q.notify({
    type: "positive",
    message: text,
    position: "top-right",
  });
};

let report = async () => {
  var sigName = props.item.name_id;
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/sigreport/" + props.item.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  var text = "Sig " + sigName + " has been reported as expired";
  $q.notify({
    type: "positive",
    message: text,
    position: "top-right",
  });
};

let showButton = $computed(() => {
  if (store.routeCurrentSystemID != props.item.system_id && !can("delete_sigs")) {
    return false;
  }

  if (props.item.jumps > 0 || can("delete_sigs")) {
    return true;
  } else {
    return false;
  }
});

let showDelete = $computed(() => {
  if (can("delete_sigs")) {
    return true;
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
