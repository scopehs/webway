<template>
  <div>
    <q-btn
      color="warning"
      outline
      size="xs"
      label="???"
      rounded
      class="myOutLineButton"
      @click="saveSingle()"
      v-show="showButton"
    />
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  sig: Object,
});

let menu = $ref(false);
let signature_id = $ref(null);

let showButton = () => {
  if (signature_id) {
    return false;
  } else {
    return true;
  }
};

let closed = () => {
  signature_id = null;
  menu = null;
};

let saveSingle = async () => {
  var request = {
    id: props.sig.id,
    signature_id: store.getCurrentSystemSigsList[0].signature_id,
    modified_by_id: store.user_id,
    modified_by_name: store.user_name,
    current_system_id: store.currentSystemId,
    last_system_id: store.lastSystemId,
    old_id: store.getCurrentSystemSigsList[0].id,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/addsigid",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(closed());
};
</script>

<style lang="scss"></style>
