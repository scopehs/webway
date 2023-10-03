<template>
  <div>
    <q-menu
      class="myRound"
      :model-value="store.hotAreaConstellationShow"
      anchor="bottom start"
      self="top middle"
      :offset="[0, 20]"
      @hide="store.hotAreaConstellationShow = false"
    >
      <q-card class="my-card">
        <q-card-section>
          <q-select
            ref="dropNeb"
            autofocus
            v-model="pickedConstellation"
            :options="filterdItems"
            input-debounce="0"
            option-value="value"
            option-label="text"
            map-options
            use-input
            hide-selected
            label="System List"
            filled
            @filter="filterFn"
            fill-input
          >
          </q-select>
        </q-card-section>
        <q-card-actions horizontal align="evenly">
          <q-btn
            flat
            color="red"
            label="Cancel"
            @click="store.hotAreaConstellationShow = !store.hotAreaConstellationShow"
            v-close-popup
          />
          <q-btn flat color="green" label="Save" @click="save()" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-menu>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { useQuasar } from "quasar";
const $q = useQuasar();
let store = useMainStore();
let pickedConstellation = $ref();
let filterText = $ref();

let constellationListPickFilter = $computed(() => {
  return store.getHotConstellationList;
});

let filterdItems = $computed(() => {
  let data = [];
  if (filterText) {
    return constellationListPickFilter.filter(
      (v) => v.text.toLowerCase().indexOf(filterText) > -1
    );
  }
  return constellationListPickFilter;
});

let filterFn = (val, update, abort) => {
  update(() => {
    filterText = val.toLowerCase();
    if (filterdItems.length > 0 && val) {
      pickedConstellation = filterdItems[0];
    }
  });
};

let save = async () => {
  var request = {
    constellation_id: pickedConstellation.value,
  };

  var name = pickedConstellation.text;
  await axios({
    method: "POST",
    url: "api/hotarea",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(((pickedConstellation = null), (store.hotAreaConstellationShow = false)));
  $q.notify({
    type: "warning",
    message: name + " has heated up",
    icon: "fa-solid fa-temperature-full",
  });
};
</script>

<style lang="scss"></style>
