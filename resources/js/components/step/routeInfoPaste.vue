<template>
  <div>
    <q-btn text-color="primary" padding="none" :icon="icon" flat @click="open()" />

    <q-dialog v-model="showRouteInfoPaste" persistent @before-hide="close()">
      <q-card class="myRoundTop" style="width: 500px">
        <q-card-section class="bg-primary text-h5 text-center">
          Paste Info for {{ sig.signature_id }}.
        </q-card-section>
        <q-card-section>
          <q-input
            input-style="height: 500px"
            v-model="editText"
            :placeholder="infoText"
            autofocus
            outlined
            type="textarea"
            @keyup.enter="updatetext()"
        /></q-card-section>
        <q-card-actions align="right">
          <q-btn
            color="positive"
            icon="check"
            label="Submit"
            @click="updatetext()"
            :disabled="submitActive"
            v-close-popup
          />
          <q-btn label="Close" color="secondary" @click="close()" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  sig: Object,
});

let editText = $ref(null);
let infoText = $ref(`An unstable wormhole, deep in space. Wormholes of this kind usually collapse after a few days, and can lead to anywhere.

This wormhole seems to lead into unknown parts of space.

This wormhole is reaching the end of its natural lifetime.
This wormhole has not yet had its stability significantly disrupted by ships passing through it.
Larger ships can pass through this wormhole.

Stage 1 = Stable/Fresh
Stage 2 = Destab/Shrink
Stage 3 = Crit/EOL`);
let openInfo = $ref(false);

let close = () => {
  editText = null;
  store.updateShowInfoPannel(null);
  openInfo = false;
};

let open = () => {
  openInfo = true;
  store.updateShowInfoPannel(props.sig.id);
};

let updatetext = async () => {
  let request = {
    text: editText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    url: "/api/parse_info/" + props.sig.id,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  close();
};

let icon = $computed(() => {
  if (props.sig.wormhole_info_mass_id == null) {
    return "fa-regular fa-circle-question";
  } else {
    return "fa-solid fa-circle-question";
  }
});

let showRouteInfoPaste = $computed(() => {
  if (!openInfo) {
    if (!props.sig.wormhole_info_mass) {
      return store.getCheckOpenInfoPannel(props.sig.id);
    } else {
      return false;
    }
  } else {
    return true;
  }
});

let submitActive = $computed(() => {
  if (editText != null) {
    return false;
  } else {
    return true;
  }
});
</script>

<style lang="scss"></style>
