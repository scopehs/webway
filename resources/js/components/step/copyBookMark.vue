<template>
  <div>
    <button v-clipboard="() => button">
      {{ sig.signature_id }}
    </button>
    <v-snackbar v-model="snack" :timeout="3000" color="green">
      {{ snackText }}

      <template v-slot:action="{ attrs }">
        <v-btn v-bind="attrs" text @click="snack = false"> Copied </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
// import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    sig: Object,
  },
  data() {
    return {
      snack: false,
      snackText: null,
    };
  },

  async created() {},

  methods: {
    button() {
      var infoType = "";
      if (this.sig.leads_to > 0) {
        infoType = this.sig.warmhole_type.type.name;
      } else {
        if (wormhole_info_leads_to_id == 1) {
          infoType = "C123";
        }
        if (wormhole_info_leads_to_id == 2) {
          infoType = "C5";
        }
        if (wormhole_info_leads_to_id == 3) {
          infoType = "C6";
        }
        if (wormhole_info_leads_to_id == 4) {
          infoType = "HS";
        }
        if (wormhole_info_leads_to_id == 5) {
          infoType = "LS";
        }
        if (wormhole_info_leads_to_id == 6) {
          infoType = "NS";
        }
        if (wormhole_info_leads_to_id == 7) {
          infoType = "Thera";
        }
      }

      this.snackText = this.clipText + " Copied to clipboard";
      return this.clipText;
    },

    clipText() {
      var type = "";
      var sys = "";
      var sigID = this.sig.signature_id;
      var life = "";
      var mass = "";
      var ship = "";

      if (this.sig.leads_to) {
        type = this.sig.warmhole_type.type.name;
      } else {
        if ((wormhole_info_leads_to_id = 1)) {
          type = "C123";
        }
        if ((wormhole_info_leads_to_id = 2)) {
          type = "C5";
        }
        if ((wormhole_info_leads_to_id = 3)) {
          type = "C6";
        }
        if ((wormhole_info_leads_to_id = 4)) {
          type = "HS";
        }
        if ((wormhole_info_leads_to_id = 5)) {
          type = "LS";
        }
        if ((wormhole_info_leads_to_id = 6)) {
          type = "NS";
        }
        if ((wormhole_info_leads_to_id = 7)) {
          type = "Thera";
        }
      }

      if (
        this.sig.solar_system.region_id > 10010000 &&
        this.sig.solar_system.region_id != 11000031
      ) {
        str = this.sig.solar_system.name;
        sys = str.slice(-3);
      } else {
        sys = this.sig.solar_system.name;
      }

      if (this.sig.wormhole_info_time_till_death_id == 3) {
        life = "(EOL)";
      }

      if (this.sig.wormhole_info_mass_id == 3) {
        mass = "(VOC)";
      }

      if (this.sig.wormhole_info_mass_id == 2) {
        mass = "(Stage2)";
      }

      if (this.sig.wormhole_info_ship_size_id == 1) {
        ship = "(F)";
      }

      return type + "/" + sys + " " + sigID + life + " " + mass + " " + ship;
    },

    testType() {
      var infoType = "";
      if (this.sig.leads_to > 0) {
        infoType = this.sig.warmhole_type.type.name;
      } else {
        if (wormhole_info_leads_to_id == 1) {
          infoType = "C123";
        }
        if (wormhole_info_leads_to_id == 2) {
          infoType = "C5";
        }
        if (wormhole_info_leads_to_id == 3) {
          infoType = "C6";
        }
        if (wormhole_info_leads_to_id == 4) {
          infoType = "HS";
        }
        if (wormhole_info_leads_to_id == 5) {
          infoType = "LS";
        }
        if (wormhole_info_leads_to_id == 6) {
          infoType = "NS";
        }
        if (wormhole_info_leads_to_id == 7) {
          infoType = "Thera";
        }
      }

      return infoType;
    },

    testSys() {
      var sys = "";
      if (
        this.sig.solar_system.region_id > 10010000 &&
        this.sig.solar_system.region_id != 11000031
      ) {
        str = this.sig.solar_system.name;
        sys = str.slice(-3);
      } else {
        sys = this.sig.solar_system.name;
      }

      return sys;
    },
    testlife() {
      var life = "";
      if (this.sig.wormhole_info_time_till_death_id == 3) {
        life = "(EOL)";
      }
      return life;
    },

    testMass() {
      var mass = "";
      if (this.sig.wormhole_info_mass_id == 3) {
        mass = "(VOC)";
      }

      if (this.sig.wormhole_info_mass_id == 2) {
        mass = "(Stage2)";
      }

      return mass;
    },

    testship() {
      var ship = "";
      if (this.sig.wormhole_info_ship_size_id == 3) {
        ship = "(F)";
      }

      return ship;
    },
  },

  computed: {},

  beforeDestroy() {},
};
</script>

<style></style>
