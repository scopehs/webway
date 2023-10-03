<template>
  <v-col cols="12">
    <v-row no-gutters>
      <v-col cols="12">
        <v-card elevation="10" rounded="xl" class="mb-5">
          <v-card-title class="justify-center primary pa-3">Signatures</v-card-title
          ><v-card-text>
            <v-data-table
              :headers="headers"
              :loading="loading"
              :items="filteredItems"
              fixed-header
              :height="height"
              :search="search"
              item-key="id"
              dense
              :items-per-page="50"
              class="rounded-xl full-width"
              :footer-props="{
                'items-per-page-options': [10, 20, 30, 50, 100, -1],
              }"
            >
              <template v-slot:[`item.created_at`]="{ item }">
                <VueCountUptimer
                  :start-time="moment.utc(item.created_at).unix()"
                  :end-text="'Sig Deaded'"
                  :interval="1000"
                >
                  <template slot="countup" slot-scope="scope">
                    <span class="red--text pl-3"
                      >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                        scope.props.seconds
                      }}</span
                    >
                  </template>
                </VueCountUptimer>
              </template>
              <template v-slot:[`item.updated_at`]="{ item }">
                <VueCountUptimer
                  :start-time="moment.utc(item.updated_at).unix()"
                  :end-text="'Sig Deaded'"
                  :interval="1000"
                >
                  <template slot="countup" slot-scope="scope">
                    <span class="red--text pl-3"
                      >{{ scope.props.hours }}:{{ scope.props.minutes }}:{{
                        scope.props.seconds
                      }}</span
                    >
                  </template>
                </VueCountUptimer>
              </template>

              <template v-slot:[`item.reserve`]="{ item }">
                <v-row no-gutters align="center" class="justify-content-center">
                  <v-col
                    :cols="tagNumber(item)"
                    class="d-flex justify-content-center"
                    v-if="showTags(item)"
                  >
                    <SigReserveTag :reserves="item.reserve"
                  /></v-col>
                  <v-col cols="2" v-if="showReserveButton(item)">
                    <SigReserveButton :item="item" class="py-1"
                  /></v-col>
                </v-row>
              </template>
              <!-- <template v-slot:[`item.signal_strength`]="{ item }">
                <SigPercentNumber :percent="item.signal_strength" />
              </template> -->
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-col>
</template>
<script>

import { mapGetters, mapState } from "pinia";
export default {
  props: {
    windowSize: Object,
    loading: Boolean,
  },
  data() {
    return {
      headers: [
        {
          text: "Region",
          value: "solar_system.region.name",
        },
        {
          text: "Constellation",
          value: "solar_system.constellation.name",
        },

        {
          text: "Sig",
          value: "name_id",
        },
        {
          text: "Type",
          value: "group.name",
        },
        {
          text: "Name",
          value: "name",
        },

        { text: "Resvered", value: "reserve", align: "center" },

        // {
        //   text: "Scanned",
        //   value: "signal_strength",
        // },

        {
          text: "age",
          value: "created_at",
        },

        {
          text: "updated",
          value: "updated_at",
        },
      ],
      search: "",

      connectionTypeItem: [
        { text: "Gate", value: 1 },
        { text: "WormHole", value: 2 },
        { text: "Jump Bridge", value: 3 },
      ],
      connectionType: [],
      jumpBridges: null,
      metroCookie: null,
    };
  },
  mounted() {},
  methods: {
    showReserveButton(item) {
      var reserveCount = item.reserve.length;
      var showButton = false;
      if (reserveCount > 0) {
        var reserved = item.reserve;
        reserved = reserved.filter((r) => r.user_id == this.$store.state.user_id);
        if (reserved.length == 1) {
          showButton = false;
        } else {
          return true;
        }
      } else {
        showButton = true;
      }

      return showButton;
    },

    tagNumber(item) {
      if (this.showReserveButton(item)) {
        return 10;
      } else {
        return 12;
      }
    },

    showTags(item) {
      var reserveCount = item.reserve.length;
      if (reserveCount > 0) {
        return true;
      } else {
        return false;
      }
    },
  },
  computed: {
    // ...mapState(["sigs"]),

    filteredItems() {
      return this.sigs;
    },
    height() {
      let num = this.windowSize.y - 253;
      return num;
    },
  },
};
</script>
