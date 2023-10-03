<template>
  <div>
    {{ text }}
  </div>
</template>
<script>

import moment from "moment";
export default {
  props: {
    item: Object,
  },
  data() {
    return {};
  },

  methods: {},

  computed: {
    text() {
      var text = null;
      switch (this.item.description_id) {
        case 1:
          if (this.item.subject) {
            text = this.item.subject.solar_system.name;
            return text;
          } else {
            return null;
          }
        case 2:
          if (this.item.subject) {
            text =
              this.item.subject.solar_system.name +
              " - " +
              this.item.subject.signature_id +
              " leads to " +
              this.item.subject.linked_solar_system.name +
              " - " +
              this.item.subject.target_connection.source_sig.signature_id;
            return text;
          } else {
            return null;
          }
        case 3:
          if (this.item.subject) {
            text =
              this.item.subject.solar_system.name +
              " leads to " +
              this.item.subject.linked_solar_system.name;
            return text;
          } else {
            return null;
          }
        case 4:
          if (this.item.subject) {
            text =
              this.item.subject.group.name +
              " - " +
              this.item.subject.signature_id +
              " in " +
              this.item.subject.solar_system.name;
            return text;
          } else {
            return null;
          }
        case 5:
          if (this.item.subject) {
            text =
              this.item.subject.solar_system.name +
              " - " +
              this.item.subject.signature_id;
            return text;
          } else {
            return null;
          }
        case 9:
          if (this.item.subject) {
            text =
              this.item.subject.solar_system.name +
              " - " +
              this.item.subject.source_sig.signature_id +
              " connected to " +
              this.item.target_solar_system.name +
              " - " +
              this.item.subject.target_sig.signature_id;
            return text;
          } else {
            return null;
          }
        case 13:
          text = null;
          return text;
        case 16:
          if (this.item.subject) {
            text =
              "From " +
              this.item.subject.start_system.name +
              " to " +
              this.item.subject.end_system.name;
            return text;
          } else {
            return null;
          }
        case 18:
          text = null;
          return text;
        case 20:
          if (this.item.subject) {
            text =
              this.item.subject.group.name +
              " - " +
              this.item.subject.signature_id +
              " in " +
              this.item.subject.solar_system.name;
            return text;
          } else {
            return null;
          }
        case 21:
          text = null;
          return text;
        case 22:
          if (this.item.subject) {
            text = this.item.subject.system.name;
            return text;
          } else {
            return null;
          }
        case 24:
          if (this.item.subject) {
            text = this.item.subject.solar_system.name;
            return text;
          } else {
            return null;
          }
        case 27:
          if (this.item.subject) {
            text =
              this.item.subject.name +
              " - " +
              this.item.subject.alliance.name +
              " - " +
              this.item.subject.corporation.name;
            return text;
          } else {
            return null;
          }
        case 28:
          if (this.item.subject) {
            text =
              this.item.subject.source_system.name +
              " - " +
              this.item.subject.source_sig.signature_id +
              " to " +
              this.item.subject.target_system.name +
              " - " +
              this.item.subject.target_sig.signature_id;
            return text;
          } else {
            return null;
          }
        case 29:
          if (this.item.subject) {
            if (this.item.subject.system_id) {
              text = this.item.subject.system.name;
            }
            if (this.item.subject.constellation_id) {
              text = this.item.subject.constellation.name;
            }
            if (this.item.subject.region_id) {
              text = this.item.subject.region.name;
            }
          } else {
            return null;
          }
        case 30:
          if (this.item.subject) {
            if (this.item.subject.system_id) {
              text = this.item.subject.system.name;
            }
            if (this.item.subject.constellation_id) {
              text = this.item.subject.constellation.name;
            }
            if (this.item.subject.region_id) {
              text = this.item.subject.region.name;
            }
          } else {
            return null;
          }
        case 31:
          text = null;
          return text;
        case 32:
          text = null;
          return text;
        case 33:
          text = null;
          return text;
        case 34:
          text = null;
          return text;
        case 35:
          if (this.item.subject) {
            text =
              this.item.subject.solar_system.name +
              "-" +
              this.item.subject.signature_id;
            return text;
          } else {
            return null;
          }
        case 36:
          if (this.item.subject) {
            text =
              this.item.subject.source_system.name +
              " - " +
              this.item.subject.source_sig.signature_id +
              " connected to " +
              this.item.subject.target_system.name +
              " - " +
              this.item.subject.target_sig.signature_id;
            return text;
          } else {
            return null;
          }
        case 37:
          if (this.item.subject) {
            text =
              this.item.subject.group.name +
              " - " +
              this.item.subject.signature_id +
              " in " +
              this.item.subject.solar_system.name;
            return text;
          } else {
            return null;
          }
        case 39:
          if (this.item.subject) {
            text =
              this.item.subject.source_system.name +
              " - " +
              this.item.subject.source_sig.signature_id +
              " to " +
              this.item.subject.target_system.name +
              " - " +
              this.item.subject.target_sig.signature_id;
            return text;
          } else {
            return null;
          }

        case 40:
          if (this.item.subject) {
            text = this.item.subject.system.name;
            return text;
          } else {
            return null;
          }

        case 41:
          if (this.item.subject) {
            text = this.item.subject.system.name;
            return text;
          } else {
            return null;
          }

        case 42:
          if (this.item.subject) {
            text = this.item.subject.system.name;
            return text;
          } else {
            return null;
          }

        case 43:
          if (this.item.subject) {
            text = this.item.subject.system.name;
            return text;
          } else {
            return null;
          }
      }
    },
  },
};
</script>

<style scoped></style>
