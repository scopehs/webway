<template>
  <div>
    <q-btn
      color="primary"
      class="myOutLineButton"
      label="Add"
      push
      size="xs"
      rounded
      @click="showWhaleAdd = !showWhaleAdd"
    />
    <q-dialog v-model="showWhaleAdd" persistent>
      <q-card style="width: 500px" class="my-card myRoundTop">
        <q-card-section class="bg-primary text-center myCardHeader">
          <div class="text-h6">Enter All the Things Here</div>
        </q-card-section>
        <q-card-section>
          <q-select
            dense
            rounded
            input-debounce="0"
            standout
            option-value
            option-label="text"
            v-model="drifterDropDownSelected"
            :options="drifterDropDownEnd"
            label="Drifter Type"
            @filter="filterFnDrifterDropDown"
            map-options
            use-input
            hide-selected
            fill-input
          />
        </q-card-section>
        <q-card-section>
          <q-input
            v-model="drfitInfoPaste"
            type="textarea"
            label="Enter Show Info Paste Here"
            outlined
            input-style="height: 400px"
            standout
            rounded
            class="myOutLineButton"
          />
        </q-card-section>
        <q-card-section v-if="false">
          <div class="row justify-around">
            <div class="col-6 q-pr-lg">
              <q-select
                rounded
                standout
                input-debounce="0"
                dense
                option-value
                option-label="text"
                v-model="driftLife"
                :options="driftDropDownLifeEnd"
                label="Hole Life"
                @filter="filterFnDriftLife"
                map-options
                use-input
                hide-selected
                fill-input
              />
            </div>
            <div class="col-6">
              <q-select
                rounded
                standout
                dense
                option-value
                option-label="text"
                v-model="driftMass"
                :options="driftDropDownMassEnd"
                label="Hole Mass"
              />
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="center">
          <q-btn
            label="Submit"
            rounded
            color="positive"
            :push="!submitActive"
            :disabled="submitActive"
            @click="submit()"
          />
          <q-btn label="Close" rounded color="negative" @click="close()" push />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});
let showWhaleAdd = $ref(false);

let drifterDropDownText = $ref();
let drifterDropDownSelected = $ref();
let drifterDropDownStart = $ref([
  { text: "Barbican", value: 31000002 },
  { text: "Conflux", value: 31000004 },
  { text: "Redoubt", value: 31000006 },
  { text: "Sentinel", value: 31000001 },
  { text: "Vidette", value: 31000003 },
]);

let drifterDropDownEnd = $computed(() => {
  if (drifterDropDownText) {
    return drifterDropDownStart.filter(
      (d) => d.text.toLowerCase().indexOf(drifterDropDownText) > -1
    );
  }
  return drifterDropDownStart;
});

let filterFnDrifterDropDown = (val, update, abort) => {
  update(() => {
    drifterDropDownText = val.toLowerCase();
    if (drifterDropDownEnd.length > 0 && val) {
      drifterDropDownSelected = drifterDropDownEnd[0];
    }
  });
};

let driftLifeText = $ref();
let driftLife = $ref();
let driftDropDownLifeStart = $ref([
  { text: "Not Yet Begun", value: 1 },
  { text: "Beginning to decay", value: 2 },
  { text: "Reaching the end", value: 3 },
]);

let driftDropDownLifeEnd = $computed(() => {
  if (driftLifeText) {
    return driftDropDownLifeStart.filter(
      (d) => d.text.toLowerCase().indexOf(driftLifeText) > -1
    );
  }
  return driftDropDownLifeStart;
});

let filterFnDriftLife = (val, update, abort) => {
  update(() => {
    driftLifeText = val.toLowerCase();
    if (driftDropDownLifeEnd.length > 0 && val) {
      driftLife = driftDropDownLifeEnd[0];
    }
  });
};

let driftMassText = $ref();
let driftMass = $ref();
let driftDropDownMassStart = $ref([
  { text: "Not Yet", value: 1 },
  { text: "Not To critical degree", value: 2 },
  { text: "Stability critically disrupted", value: 3 },
]);

let driftDropDownMassEnd = $computed(() => {
  if (driftMassText) {
    return driftDropDownMassStart.filter(
      (d) => d.text.toLowerCase().indexOf(driftMassText) > -1
    );
  }
  return driftDropDownMassStart;
});

let filterFnMassEnd = (val, update, abort) => {
  update(() => {
    driftMass = val.toLowerCase();
    if (driftDropDownMassEnd.length > 0 && val) {
      driftMass = driftDropDownMassEnd[0];
    }
  });
};

let drfitInfoPaste = $ref();

let close = () => {
  drifterDropDownSelected = null;
  driftLife = null;
  driftMass = null;
  drfitInfoPaste = null;
  showWhaleAdd = false;
};

let submit = async () => {
  let mass = null;
  let life = null;
  if (driftMass) {
    mass = driftMass.value;
  }
  if (driftLife) {
    life = driftLife.value;
  }
  var request = {
    system_id: props.item.system_id,
    driftLife: life,
    driftMass: mass,
    drfitInfoPaste: drfitInfoPaste,
    drifterDropDownSelected: drifterDropDownSelected.value,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/jovesystemlastupdated/" + props.item.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/whaledriftadd",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(close());
};

let submitActive = $computed(() => {
  if (drifterDropDownSelected && (drfitInfoPaste || (driftLife && driftMass))) {
    return false;
  }

  return true;
});
</script>

<style lang="scss"></style>
