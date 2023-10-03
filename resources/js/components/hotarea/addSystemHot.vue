<template>
  <div>
    <q-menu
      class="myRound"
      :model-value="store.hotAreaSystemShow"
      anchor="bottom start"
      self="top middle"
      :offset="[0, 20]"
      @hide="store.hotAreaSystemShow = false"
    >
      <q-card class="my-card">
        <q-card-section>
          <q-select
            ref="dropNeb"
            autofocus
            v-model="pickedSystem"
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
            @click="store.hotAreaSystemShow = !store.hotAreaSystemShow"
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
let pickedSystem = $ref();
let filterText = $ref();

let systemlistPickFilter = $computed(() => {
  return store.getHotAreaSystemList;
});

let filterdItems = $computed(() => {
  let data = [];
  if (filterText) {
    return systemlistPickFilter.filter(
      (v) => v.text.toLowerCase().indexOf(filterText) > -1
    );
  }
  return systemlistPickFilter;
});

let filterFn = (val, update, abort) => {
  update(() => {
    filterText = val.toLowerCase();
    if (filterdItems.length > 0 && val) {
      pickedSystem = filterdItems[0];
    }
  });
};

let save = async () => {
  var request = {
    system_id: pickedSystem.value,
  };

  var name = pickedSystem.text;
  await axios({
    method: "POST",
    url: "api/hotarea",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(((pickedSystem = null), (store.hotAreaSystemShow = false)));
  $q.notify({
    type: "warning",
    message: name + " has heated up",
    icon: "fa-solid fa-temperature-full",
  });
};
</script>

<style lang="scss"></style>
