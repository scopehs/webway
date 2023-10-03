<template>
  <div>
    <q-btn
      text-color="negative"
      icon="fa-solid fa-circle-minus"
      flat
      size="sm"
      padding="none"
      @click="deleteMenu = true"
    />
  </div>
  <div>
    <q-menu v-model="deleteMenu">
      <q-list style="min-width: 100px">
        <q-item clickable v-close-popup @click="sigGone()">
          <q-item-section>Gone</q-item-section>
        </q-item>
        <q-separator />
        <q-item clickable v-close-popup @click="remove()">
          <q-item-section>Delete</q-item-section>
        </q-item>
      </q-list>
    </q-menu>
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { useMainStore } from "@/store/useMain.js";
const $q = useQuasar();
let store = useMainStore();
const props = defineProps({
  item: Object,
});

let deleteMenu = $ref(false);

let sigGone = async () => {
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
  });

  deleteMenu = false;
};

let remove = async () => {
  var sigName = props.item.name_id;
  var request = {
    delete: 1,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/sigssdelete/" + props.item.id,
    data: request,
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

  var text = "Sig " + sigName + " has been deleted";
  $q.notify({
    type: "warning",
    message: text,
  });
};
</script>

<style lang="scss"></style>
