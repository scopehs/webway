<template>
  <div>
    <transition-group
      mode="out-in"
      enter-active-class="animate__animated animate__zoomIn animate__faster"
      leave-active-class="animate__animated animate__zoomOut animate__faster"
    >
      <div class="row full-width justify-start" :key="`${sig.leads_to}-showSigButton`">
        <div class="col-auto">
          <div :key="`${sig.leads_to}-showSigButton`" v-if="showButton == 1">
            <AddSigId :sig="sig"></AddSigId>
          </div>
        </div>
      </div>
      <div
        class="row full-width justify-start"
        :key="`${sig.leads_to}-showSigButtonNothing`"
      >
        <div class="col-auto">
          <div :key="`${sig.leads_to}-showSigButtonNothing`" v-if="showButton == 2">
            <q-btn color="warning" size="xs" label="???" />
          </div>
        </div>
      </div>
      <div class="row full-width justify-start" :key="`${sig.signature_id}-sigText`">
        <div class="col-auto">
          <div
            :key="`${sig.signature_id}-sigText`"
            :class="textColor"
            v-if="showButton == 3"
          >
            <q-btn flat padding="none" :label="sig.signature_id" @click="button()"
              ><q-tooltip>
                {{ toolTipText }}
              </q-tooltip></q-btn
            >
          </div>
        </div>
      </div>

      <div :key="`${sig.signature_id}-showSigButtonSolo`" v-if="showButton == 4">
        <AddSigIdSingle :sig="sig"></AddSigIdSingle>
      </div>

      <div
        :key="`${sig.signature_id}-sigTextCopy`"
        :class="textColor"
        v-if="showButton == 5"
        class="text-left"
      >
        {{ sig.signature_id }}

        <q-tooltip :delay="500">
          {{ toolTipText }}
        </q-tooltip>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { useQuasar, copyToClipboard } from "quasar";
import { defineAsyncComponent } from "vue";
const AddSigId = defineAsyncComponent(() => import("./addSigId.vue"));
const AddSigIdSingle = defineAsyncComponent(() => import("./addSigIdSingle.vue"));
const $q = useQuasar();
let store = useMainStore();
const props = defineProps({
  sig: Object,
  type: Number,
  edit: Boolean,
  lastSystem: Number,
  currentSystem: Number,
});

let textColor = $computed(() => {
  if (props.sig.leads_to) {
    if (props.type == 2) {
      if (props.sig.leads_to == props.currentSystem) {
        return "text-center text-primary";
      }
    } else {
      if (props.sig.leads_to == props.lastSystem) {
        return "text-center text-primary";
      }
    }
  }

  return "text-center";
});

let showButton = $computed(() => {
  if (props.edit == true) {
    if (props.sig.signature_id) {
      if (props.sig.wormhole_info_mass_id > 0) {
        return 3;
      } else {
        return 5;
      }
    } else {
      if (props.type == 2) {
        return 2;
      } else {
        if (dropdownCount == 1) {
          return 4;
        } else {
          return 1;
        }
      }
    }
  } else {
    if (props.sig.wormhole_info_mass_id > 0) {
      return 3;
    } else {
      return 5;
    }
  }
});

let button = () => {
  var drifter = false;
  var infoType = "";
  var sys = "???";
  var sigID = props.sig.signature_id;
  var life = "";
  var mass = "";
  var ship = "";
  if (props.sig.leads_to > 0) {
    if (
      props.sig.linked_solar_system.system_type[0]["id"] == 14 ||
      props.sig.linked_solar_system.system_type[0]["id"] == 15 ||
      props.sig.linked_solar_system.system_type[0]["id"] == 16 ||
      props.sig.linked_solar_system.system_type[0]["id"] == 17 ||
      props.sig.linked_solar_system.system_type[0]["id"] == 18
    ) {
      var p1 = null;
      switch (props.sig.linked_solar_system.system_type[0]["id"]) {
        case 14:
          p1 = "Sentinel Beacon ";
          break;

        case 15:
          p1 = "Barbican Beacon ";
          break;

        case 16:
          p1 = "Vidette Beacon ";
          break;

        case 17:
          p1 = "Conflux Beacon ";
          break;

        case 18:
          p1 = "Redoubt Beacon ";
          break;
      }

      var nameDrift = props.sig.signature_id;
      var explode = nameDrift.split("-");
      var numText = explode[1];

      infoType = p1 + numText;
      drifter = true;
    } else {
      infoType = props.sig.linked_solar_system.system_type[0]["name"];
    }
  } else {
    if (props.sig.type != 420) {
      if (props.sig.wormhole_type) {
        if (props.sig.wormhole_type.type) {
          switch (props.sig.wormhole_type.type.id) {
            case 1:
              infoType = "C1";
              break;

            case 2:
              infoType = "C2";
              break;

            case 3:
              infoType = "C3";
              break;

            case 4:
              infoType = "C4";
              break;

            case 5:
              infoType = "C5";
              break;

            case 6:
              infoType = "C6";
              break;

            case 7:
              infoType = "HS";
              break;

            case 8:
              infoType = "LS";
              break;

            case 9:
              infoType = "NS";
              break;

            case 12:
              infoType = "Thera";
              break;

            case 13:
              infoType = "C13";
              break;

            case 14:
              p1 = "Sentinel Beacon ";
              var nameDrift = props.sig.signature_id;
              var exploade = nameDrift.split("-");
              var numText = exploade[1];
              infoType = p1 + numText;
              drifter = true;
              break;

            case 15:
              p1 = "Barbican Beacon ";
              var nameDrift = props.sig.signature_id;
              var exploade = nameDrift.split("-");
              var numText = exploade[1];
              infoType = p1 + numText;
              drifter = true;
              break;

            case 16:
              p1 = "Vidette Beacon ";
              var nameDrift = props.sig.signature_id;
              var exploade = nameDrift.split("-");
              var numText = exploade[1];
              infoType = p1 + numText;
              drifter = true;
              break;

            case 17:
              p1 = "Conflux Beacon ";
              var nameDrift = props.sig.signature_id;
              var exploade = nameDrift.split("-");
              var numText = exploade[1];
              infoType = p1 + numText;
              drifter = true;
              break;

            case 18:
              p1 = "Redoubt Beacon ";
              var nameDrift = props.sig.signature_id;
              var exploade = nameDrift.split("-");
              var numText = exploade[1];
              infoType = p1 + numText;
              drifter = true;
              break;

            case 25:
              infoType = "Poch";
              break;
          }
        }
      }
    } else {
      if (props.sig.wormhole_info_leads_to_id == 1) {
        if (props.sig.wormhole_type) {
          if (props.sig.wormhole_type.type) {
            if (
              props.sig.wormhole_type.type.id == 14 ||
              props.sig.wormhole_type.type.id == 15 ||
              props.sig.wormhole_type.type.id == 16 ||
              props.sig.wormhole_type.type.id == 17 ||
              props.sig.wormhole_type.type.id == 18
            ) {
              var p1 = null;
              switch (props.sig.wormhole_type.type.id) {
                case 14:
                  p1 = "Sentinel Beacon ";
                  break;

                case 15:
                  p1 = "Barbican Beacon ";
                  break;

                case 16:
                  p1 = "Vidette Beacon ";
                  break;

                case 17:
                  p1 = "Conflux Beacon ";
                  break;

                case 18:
                  p1 = "Redoubt Beacon ";
                  break;
              }

              var nameDrift = props.sig.signature_id;
              var explode = nameDrift.split("-");
              var numText = explode[1];

              infoType = p1 + numText;
              drifter = true;
            }
          } else {
            infoType = "C123";
          }
        } else {
          infoType = "C123";
        }
      }
      if (props.sig.wormhole_info_leads_to_id == 2) {
        infoType = "C45";
      }
      if (props.sig.wormhole_info_leads_to_id == 3) {
        infoType = "C6";
      }
      if (props.sig.wormhole_info_leads_to_id == 4) {
        infoType = "HS";
      }
      if (props.sig.wormhole_info_leads_to_id == 5) {
        infoType = "LS";
      }
      if (props.sig.wormhole_info_leads_to_id == 6) {
        infoType = "NS";
      }
      if (props.sig.wormhole_info_leads_to_id == 7) {
        infoType = "Thera";
      }
    }
  }
  if (props.sig.linked_solar_system) {
    if (
      props.sig.linked_solar_system.region_id > 10010000 &&
      props.sig.linked_solar_system.region_id != 11000031
    ) {
      str = props.sig.linked_solar_system.name;
      sys = str.slice(-3);
    } else {
      sys = props.sig.linked_solar_system.name;
    }
  }

  if (props.sig.wormhole_info_mass_id > 0) {
    if (props.sig.wormhole_info_time_till_death_id == 3) {
      life = "(EOL)";
    }
    if (props.sig.wormhole_info_mass_id == 3) {
      mass = "(VOC)";
    }
    if (props.sig.wormhole_info_mass_id == 2) {
      mass = "(Stage2)";
    }
    if (props.sig.wormhole_info_ship_size_id == 4) {
      ship = "(F)";
    }
  }

  if (drifter) {
    var str = infoType;
    if (life) {
      str = str + " " + life;
    }

    if (mass) {
      str = str + " " + mass;
    }

    if (ship) {
      str = str + " " + ship;
    }
  } else {
    var str = infoType + "/" + sys + " " + sigID;
    if (life) {
      str = str + " " + life;
    }

    if (mass) {
      str = str + " " + mass;
    }

    if (ship) {
      str = str + " " + ship;
    }
  }
  var text = str + " copied to your clipboard";

  copyToClipboard(str)
    .then(() => {
      $q.notify({
        type: "info",
        message: text,
      });
    })
    .catch(() => {
      // fail
    });
};

let toolTipText = $computed(() => {
  if (props.sig.name_id) {
    return props.sig.name_id;
  }
  return null;
});

let filterItems = $computed(() => {
  return store.getCurrentSystemSigsList;
});

let dropdownCount = $computed(() => {
  return store.getCurrentSystemSigsListCount;
});
</script>

<style lang="scss"></style>
