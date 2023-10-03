<template>
  <v-col cols="12" class="pa-0">
    <v-row no-gutters>
      <v-col cols="12">
        <v-data-table
          :headers="headers"
          :loading="loading"
          :items="filter_end"
          fixed-header
          :height="height"
          item-key="id"
          dense
          :sort-by="['created_at']"
          :sort-desc="[true, false]"
          :items-per-page="50"
          :footer-props="{
            'items-per-page-options': [50, -1],
          }"
          class="elevation-24 rounded-xl full-width"
        >
          <!-- <template v-slot:[`item.text`]="{ item }"> "lalalalal" </template> -->
          <template v-slot:[`item.created_at`]="{ item }">
            <CellLogTime :item="item"></CellLogTime>
          </template>
          <template v-slot:[`item.text`]="{ item }">
            <CellLogText :item="item"></CellLogText>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-col>
</template>
<script>
import { mapGetters, mapState } from "pinia";

export default {
  props: {
    windowSize: Object,
    fTypes: Array,
  },
  async created() {},

  data() {
    return {
      headers: [
        {
          text: "Type",
          value: "description_type.name",
        },

        {
          text: "Text",
          value: "text",
        },

        {
          text: "Date",
          value: "created_at",
        },
      ],
      loading: false,
    };
  },
  mounted() {},
  methods: {},

  computed: {
    // ...mapState(["userLogs"]),
    // ...mapGetters(["getUserLogs"]),

    height() {
      let num = this.windowSize.y - 239;
      return num;
    },

    filterdItems() {
      var check = this.getUserLogs.hasOwnProperty("logs");
      if (check) {
        return this.getUserLogs.logs;
      } else {
        return [];
      }
    },

    filter_end() {
      let data = [];

      if (this.fTypes.length != 0) {
        this.fTypes.forEach((p) => {
          let pick = this.filterdItems.filter((f) => f.description_id == p);
          if (pick != null) {
            pick.forEach((pk) => {
              data.push(pk);
            });
          }
        });
        return data;
      }
      return this.filterdItems;
    },
  },

  beforeDestroy() {},
};
</script>
