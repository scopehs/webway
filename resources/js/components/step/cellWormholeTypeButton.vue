<template>
  <div>
    <transition
      mode="out-in"
      enter-active-class="animate__animated animate__zoomIn animate__faster"
      leave-active-class="animate__animated animate__zoomOut animate__faster"
    >
      <div class="row full-width justify-start">
        <div class="col-auto">
          <div :key="`${cellID}-Typebutton`" v-if="showButton">
            <AddWormholeType :sig="sig" :system="system"></AddWormholeType>
          </div>
        </div>
      </div>
    </transition>

    <transition
      mode="out-in"
      enter-active-class="animate__animated animate__zoomIn animate__faster"
      leave-active-class="animate__animated animate__zoomOut animate__faster"
    >
      <div :key="`${cellID}-TypeText`" class="text-left" v-if="!showButton">
        {{ wormholeTypeText }}
        <span :class="wormholeTypeTextLeadColor"> {{ wormholeTypeTextLeadTo }}</span>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
const AddWormholeType = defineAsyncComponent(() => import("./addWormholeType.vue"));
const props = defineProps({
  sig: Object,
  type: Number,
  edit: Boolean,
  system: Object,
});

let wormholeType = (item) => {
  if (item.wormhole_type) {
    return item.wormhole_type.wormhole_type;
  } else {
    return null;
  }
};

let showButton = $computed(() => {
  if (props.sig.wormhole_type) {
    if (props.sig.wormhole_type.wormhole_type) {
      return false;
    } else {
      if (props.edit == true) {
        return true;
      } else {
        return false;
      }
    }
  } else {
    return true;
  }
});

let wormholeTypeText = $computed(() => {
  if (props.sig.wormhole_type) {
    return props.sig.wormhole_type.wormhole_type;
  } else {
    return null;
  }
});

let wormholeTypeTextLeadTo = $computed(() => {
  var k = false;
  if (props.sig.type == 420) {
    k = true;
  }

  if (!k) {
    return props.sig.wormhole_type.type.name;
  } else {
    if (props.sig.linked_solar_system) {
      return props.sig.linked_solar_system.system_type[0]["name"];
    }

    if (props.sig.wormhole_info_leads_to_id > 0) {
      switch (props.sig.wormhole_info_leads_to_id) {
        case 1:
          return "C123";
        case 2:
          return "C45";
        case 3:
          return "C6";
        case 4:
          return "HS";
        case 5:
          return "LS";
        case 6:
          return "NS";
        case 7:
          return "Pochven";
      }
    }
  }

  return null;
});

let wormholeTypeTextLeadColor = $computed(() => {
  var k = false;
  if (props.sig.type == 420) {
    k = true;
  }

  if (!k) {
    switch (props.sig.wormhole_type.type.id) {
      // 1-6 C#
      // 7-9 H/L/N S
      // 12 Thera
      // 13-18 C#
      // 25 Trig
      case 1:
        return "text-negative";

      case 2:
        return "text-negative";

      case 3:
        return "text-negative";

      case 4:
        return "text-negative";

      case 5:
        return "text-negative";

      case 6:
        return "text-negative";

      case 7:
        return "text-primary";

      case 8:
        return "text-warning";

      case 9:
        return "text-negative";

      case 12:
        return "text-negative";

      case 13:
        return "text-negative";

      case 14:
        return "text-negative";

      case 15:
        return "text-negative";

      case 16:
        return "text-negative";

      case 17:
        return "text-negative";

      case 18:
        return "text-negative";

      case 25:
        return "text-negative";
    }
  } else {
    if (props.sig.linked_solar_system) {
      switch (props.sig.linked_solar_system.system_type[0]["id"]) {
        // 1-6 C#
        // 7-9 H/L/N S
        // 12 Thera
        // 13-18 C#
        // 25 Trig
        case 1:
          return "text-negative";

        case 2:
          return "text-negative";

        case 3:
          return "text-negative";

        case 4:
          return "text-negative";

        case 5:
          return "text-negative";

        case 6:
          return "text-negative";

        case 7:
          return "text-primary";

        case 8:
          return "text-warning";

        case 9:
          return "text-negative";

        case 12:
          return "text-negative";

        case 13:
          return "text-negative";

        case 14:
          return "text-negative";

        case 15:
          return "text-negative";

        case 16:
          return "text-negative";

        case 17:
          return "text-negative";

        case 18:
          return "text-negative";

        case 25:
          return "text-negative";
      }
    }

    if (props.sig.wormhole_info_leads_to_id > 0) {
      switch (props.sig.wormhole_info_leads_to_id) {
        case 1:
          return "text-negative";
        case 2:
          return "text-negative";
        case 3:
          return "text-negative";
        case 4:
          return "text-primary";
        case 5:
          return "text-warning";
        case 6:
          return "text-negative";
        case 7:
          return "text-negative";
      }
    }
  }
});

let cellID = $computed(() => {
  if (props.sig.wormhole_type) {
    return props.sig.id + "-" + props.sig.wormhole_type.id;
  } else {
    return props.sig.id;
  }
});
</script>

<style lang="scss"></style>
