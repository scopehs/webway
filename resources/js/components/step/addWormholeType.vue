<template>
  <div>
    <q-btn
      color="warning"
      class="myOutLineButton"
      size="xs"
      outline
      rounded
      @click="showing = true"
      ><template v-slot:default> <span class="">Add</span></template></q-btn
    >
  </div>
  <div>
    <q-menu v-model="showing" persistent class="myRound">
      <q-card class="my-card">
        <q-card-section>
          <q-select
            autofocus
            rounded
            dense
            label-color="webway"
            v-model="wormhole_type_id"
            @filter="filterFnTypeID"
            :options="dropDownList"
            label="Wormhole Type"
            map-options
            input-debounce="0"
            use-input
            hide-selected
            fill-input
            standout
            option-label="wormhole_type"
            option-value="id"
            @update:model-value="save()"
            @keyup.enter="save()"
          />
        </q-card-section>
        <q-card-actions align="around">
          <q-btn @click="closed()" flat label="Cancel" />
          <q-btn flat color="primary" @click="save()" label="save" />
        </q-card-actions>
      </q-card>
    </q-menu>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  sig: Object,
  system: Object,
});

let showing = $ref(false);

let typeIDText = $ref();
let wormhole_type_id = $ref(null);

let dropDownList = $computed(() => {
  if (props.sig.name_id == "DRIFT") {
    if (typeIDText) {
      return store.drifterTypeList.filter(
        (d) => d.wormhole_type.toLowerCase().indexOf(typeIDText) > -1
      );
    } else {
      return store.drifterTypeList;
    }
  }
  if (typeIDText) {
    return store.wormholeTypeList.filter(
      (d) => d.wormhole_type.toLowerCase().indexOf(typeIDText) > -1
    );
  } else {
    return store.wormholeTypeList;
  }
});

let filterFnTypeID = (val, update, abort) => {
  update(() => {
    typeIDText = val.toLowerCase();
    if (dropDownList.length > 0 && val) {
      wormhole_type_id = dropDownList[0];
    }
  });
};

let closed = () => {
  wormhole_type_id = null;
  showing = false;
  store.updateShowInfoPannel(props.sig.id);
};

let open = () => {
  showing = true;
};

let save = async () => {
  var request = {
    modified_by_id: store.user_id,
    modified_by_name: store.user_name,
    type: wormhole_type_id.id,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/sigupdate/" + props.sig.id,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(closed());
};
</script>

<style lang="scss"></style>
