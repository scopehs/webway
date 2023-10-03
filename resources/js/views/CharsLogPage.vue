<template>
  <div class="pr-5 pl-5 pt-1" v-resize="onResize">
    <v-row no-gutters justify="space-around">
      <v-col cols="5">
        <v-row no-gutters justify="space-around">
          <v-col cols="12">
            <v-card elevation="10" rounded="xl" class="mb-5">
              <v-card-title class="primary pa-3">
                <v-row no-gutters justify="start">
                  <v-col cols="1"> User - </v-col>
                  <v-col cols="3">
                    <v-autocomplete
                      color="grey darken-4"
                      v-model="selectedUser"
                      :items="userList"
                      solo-inverted
                      auto-select-first
                      dense
                      hide-details
                      hide-selected
                      rounded
                      return-object
                      @change="getLogs()"
                    >
                    </v-autocomplete>
                  </v-col>
                </v-row>
              </v-card-title>
              <v-card-text class="pt-2">
                <v-row no-gutters>
                  <v-col cols="3">
                    <v-avatar color="grey darken-4" size="164" tile>
                      <v-img :src="url"></v-img>
                    </v-avatar>
                  </v-col>
                  <v-col cols="8">
                    <UserLogCharTable :windowSize="windowSize"></UserLogCharTable>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
            <v-card elevation="10" rounded="xl" class="mb-5">
              <v-card-title class="primary pa-3">Filter</v-card-title>
              <v-card-text class="pt-2">
                <v-chip-group
                  active-class="primary--text"
                  column
                  v-model="fTypes"
                  multiple
                >
                  <v-chip
                    v-for="(list, index) in buttonList"
                    :key="index"
                    filter
                    :value="list.id"
                    outlined
                    small
                  >
                    {{ list.name }}
                  </v-chip>
                </v-chip-group>
              </v-card-text>
              <v-card-actions class="justify-content-center">
                <v-btn @click="clearClass" color=" warning" rounded v-if="showClassButton"
                  >Clear</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
      <v-col cols="6">
        <v-card elevation="10" rounded="xl" class="mb-5">
          <v-card-title class="justify-center primary pa-3">Logs</v-card-title>
          <v-card-text class="pa-0">
            <UserLogTable :windowSize="windowSize" :fTypes="fTypes"> </UserLogTable>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import { mapGetters, mapState } from "pinia";

export default {
  title() {
    return `Webway - User Logs`;
  },
  async created() {
    Echo.private("charlogs").listen("CharLogPageUpdate", (e) => {
      if (e.flag.flag == 1) {
      }
      if (e.flag.flag == 2) {
      }
      if (e.flag.flag == 3) {
      }
    });

    this.$store.dispatch("getUserList");
    this.$store.dispatch("getActivityTypes");
  },

  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },

      selectedUser: null,
      fTypes: [],
    };
  },
  mounted() {
    this.onResize();
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },
    clearClass() {
      this.fTypes = [];
    },
    showClassButton() {
      if (this.fTypes.length > 0) {
        return true;
      }

      return false;
    },
    getLogs() {
      this.$store.dispatch("getLogsByUser", this.selectedUser.value);
    },
  },

  computed: {
    // ...mapState(["userList", "activityTypes"]),
    // ...mapGetters(["getUserURL"]),
    url() {
      if (this.selectedUser) {
        return this.getUserURL;
      }
      return null;
    },

    buttonList() {
      return this.activityTypes;
    },
  },

  beforeDestroy() {
    Echo.leave("charlogs");
  },
};
</script>
