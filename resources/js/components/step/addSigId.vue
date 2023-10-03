<template>
  <div>
    <q-btn color="warning" class="myOutLineButton" size="sm" outline rounded label="???">
      <q-menu persistent :model-value="menu" class="myRound">
        <q-card class="my-card">
          <q-card-section>
            <q-select
              v-if="dropdownCount > 0"
              rounded
              dense
              autofocus
              v-model="signature_id"
              input-debounce="0"
              :options="filterItems"
              label="Sig ID"
              label-color="webway"
              standout
              ref="sigIDRef"
              @filter="filterFnSigID"
              map-options
              use-input
              hide-selected
              fill-input
              :key="`${sig.id} - dropdown`"
              option-label="signature_id"
              option-value="id"
              @update:model-value="save()"
              @keyup.enter="save()"
            />
            <span :key="`${sig.id} - text`" v-else>
              You need to have a wormhole sig in the table before you can link it.
            </span>
          </q-card-section>
          <q-card-actions align="around">
            <q-btn color="warning" flat label="Cancel" @click="closed()" v-close-popup />
            <q-btn
              color="primary"
              flat
              label="Save"
              @click="save()"
              v-close-popup
              :disabled="showButton()"
            />
          </q-card-actions>
        </q-card>
      </q-menu>
    </q-btn>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  sig: Object,
});

let menu = $ref(false);

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

let save = async () => {
  var request = {
    id: props.sig.id,
    signature_id: signature_id.signature_id,
    modified_by_id: store.user_id,
    modified_by_name: store.user_name,
    current_system_id: store.currentSystemId,
    last_system_id: store.lastSystemId,
    old_id: signature_id.id,
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

let sigIDText = $ref();
let signature_id = $ref(null);
let filterItems = $computed(() => {
  if (sigIDText) {
    return store.getCurrentSystemSigsList.filter(
      (d) => d.signature_id.toLowerCase().indexOf(sigIDText) > -1
    );
  }
  return store.getCurrentSystemSigsList;
});

let filterFnSigID = (val, update, abort) => {
  update(() => {
    sigIDText = val.toLowerCase();
    if (filterItems.length > 0 && val) {
      signature_id = filterItems[0];
    }
  });
};

let dropdownCount = $computed(() => {
  return store.getCurrentSystemSigsListCount;
});
</script>

<style lang="scss"></style>
