<template>
  <div>
    <transition-group
      mode="out-in"
      enter-active-class="animate__animated animate__zoomIn animate__faster"
      leave-active-class="animate__animated animate__zoomOut animate__faster"
    >
      <div
        :key="`${sig.leads_to}-${sig.signature_id}-button`"
        v-show="showButton"
        :id="`${sig.id}-button`"
      >
        <q-btn
          color="warning"
          size="xs"
          class="myOutLineButton"
          label="Link"
          @click="addSystem()"
          rounded
        />
      </div>

      <div
        :key="`${sig.leads_to}-${sig.signature_id}-text`"
        class="text-left"
        v-show="showText"
        :id="`${sig.leads_to}-text`"
      >
        {{ text }}
        <q-tooltip :delay="800">
          {{ toolTipText }}
        </q-tooltip>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  sig: Object,
  type: Number,
  edit: Boolean,
});

let addSystem = async () => {
  {
    var request = {
      modified_by_id: store.user_id,
      modified_by_name: store.user_name,
      current_system_id: store.currentSystemId,
      wormhole_info_leads_to_id: props.sig.wormhole_info_leads_to_id,
      wormhole_info_mass_id: props.sig.wormhole_info_mass_id,
      wormhole_info_ship_size_id: props.sig.wormhole_info_ship_size_id,
      wormhole_info_time_till_death_id: props.sig.wormhole_info_time_till_death_id,
      sig_id: props.sig.id,
      last_system_id: store.lastSystemId,
      life_time: props.sig.life_time,
      drift_number: store.getDrifterCount,
    };

    await axios({
      method: "POST",
      withCredentials: true,
      url: "api/leadsto",
      data: request,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
  }
};

let showButton = $computed(() => {
  if (props.sig.leads_to || props.type == 1 || !props.sig.type || !props.edit) {
    return false;
  } else {
    return true;
  }
});

let showText = $computed(() => {
  if (props.sig.leads_to > 0) {
    return true;
  } else {
    return false;
  }
});

let text = $computed(() => {
  if (props.sig.linked_solar_system) {
    return props.sig.linked_solar_system.name;
  } else {
    return null;
  }
});

let toolTipText = $computed(() => {
  if (props.sig.linked_solar_system) {
    return (
      props.sig.linked_solar_system.system_type[0]["name"] +
      ": " +
      props.sig.linked_solar_system.region.name +
      " -> " +
      props.sig.linked_solar_system.constellation.name
    );
  } else {
    return null;
  }
});
</script>

<style lang="scss"></style>
