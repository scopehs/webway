<template>
  <div>
    <q-btn
      text-color="negative"
      icon="fa-solid fa-circle-minus"
      flat
      size="sm"
      padding="none"
      @click="done()"
    />
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { useMainStore } from "@/store/useMain.js";
const $q = useQuasar();
let store = useMainStore();
const props = defineProps({
  item: Object,
  type: Number,
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

  var data = {
    id: props.item.id,
    delete: 1,
  };
  if (props.type == 1) {
    store.updateCurrentSystemSigs(data);
  } else {
    store.updateLastSystemSigs(data);
  }

  var text = "Sig " + sigName + " has been removed";
  $q.notify({
    type: "positive",
    message: text,
  });
};
</script>

<style lang="scss"></style>
