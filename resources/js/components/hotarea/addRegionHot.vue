<template>
  <div>
    <q-menu
      class="myRound"
      :model-value="store.hotAreaRegionShow"
      anchor="bottom start"
      self="top middle"
      :offset="[0, 20]"
      @hide="store.hotAreaRegionShow = false"
    >
      <q-card class="my-card">
        <q-card-section>
          <q-select
            ref="dropNeb"
            autofocus
            v-model="pickedRegion"
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
            @click="store.hotAreaRegionShow = !store.hotAreaRegionShow"
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
let pickedRegion = $ref();
let filterText = $ref();

let regionListPickFilter = $computed(() => {
  return store.getHotRegionList;
});

let filterdItems = $computed(() => {
  let data = [];
  if (filterText) {
    return regionListPickFilter.filter(
      (v) => v.text.toLowerCase().indexOf(filterText) > -1
    );
  }
  return regionListPickFilter;
});

let filterFn = (val, update, abort) => {
  update(() => {
    filterText = val.toLowerCase();
    if (filterdItems.length > 0 && val) {
      pickedRegion = filterdItems[0];
    }
  });
};

let save = async () => {
  var request = {
    region_id: pickedRegion.value,
  };

  var name = pickedRegion.text;
  await axios({
    method: "POST",
    url: "api/hotarea",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(((pickedRegion = null), (store.hotAreaRegionShow = false)));
  $q.notify({
    type: "warning",
    message: name + " has heated up",
    icon: "fa-solid fa-temperature-full",
  });
};
</script>

<style lang="scss"></style>
