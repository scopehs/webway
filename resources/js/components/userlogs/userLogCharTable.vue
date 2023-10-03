<template>
  <v-col cols="12" class="pa-0">
    <v-row no-gutters>
      <v-col cols="12">
        <v-list>
          <v-list-item v-for="(char, index) in chars" :key="index">
            <CharListList :item="char"></CharListList>
          </v-list-item>
        </v-list>
      </v-col>
    </v-row>
  </v-col>
</template>
<script>
import { mapGetters, mapState } from "pinia";

export default {
  props: {
    windowSize: Object,
  },
  async created() {},

  data() {
    return {
      headers: [
        {
          text: "",
          value: "img",
        },

        {
          text: "name",
          value: "name",
        },

        {
          text: "Alliance",
          value: "alliance.name",
        },

        {
          text: "Corporation",
          value: "corporation.name",
        },
        {
          text: "Current System",
          value: "current_system.name",
        },
        {
          text: "Last System",
          value: "last_system.name",
        },
        {
          text: "Ship",
          value: "ship_type.name",
        },
      ],
      loading: false,
    };
  },
  mounted() {},
  methods: {},

  computed: {
    // ...mapState(["userLogsCharList"]),
    // ...mapGetters(["getUserLogs"]),

    height() {
      let num = this.windowSize.y - 809;
      return num;
    },

    chars() {
      return this.userLogsCharList;
    },
  },

  beforeDestroy() {},
};
</script>
