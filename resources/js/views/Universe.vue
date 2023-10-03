<template>
  <v-row class="pr-16 pl-16 pt-5" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="12">
          <p>THIS IS A TITLE AREA</p>
        </v-col>
      </v-row>
      <v-row no-gutters>
        <v-col cols="8">
          <v-data-table
            :headers="headers"
            :items="filteredItems"
            item-key="id"
            :height="height"
            fixed-header
            :search="search"
            :class="scrollbarTheme"
            dense
            class="elevation-24 rounded-xl"
            :hide-default-footer="true"
            :footer-props="{
              'items-per-page-options': [-1],
            }"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title class="rounded-t-xl full-width">
                  <v-row class="w-full">
                    <v-col cols="4" align="center"> Universe </v-col>
                    <v-spacer></v-spacer>
                    <v-col cols="8" justify="end" align="end">
                      <v-text-field
                        v-model="search"
                        append-icon="fas fa-search fa-xs"
                        label="Search"
                        single-line
                        rounded
                        hide-details
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-toolbar-title>
              </v-toolbar>
            </template>
            <template v-slot:[`item.inConst`]="{ item }">
              {{ item.constellation.name }}
            </template>
            <template v-slot:[`item.inRegion`]="{ item }">
              {{ item.region.name }}
            </template>
            <template v-slot:[`item.inClass`]="{ item }">
              <Class :item="item"></Class>
            </template>
            <template v-slot:[`item.inStatic`]="{ item }">
              <span v-for="(hole, index) in item.statics" :key="index">
                {{ hole.wormhole_type }} &nbsp;&nbsp;
              </span>
            </template>
          </v-data-table>
        </v-col>
        <v-col cols="4" class="pl-4 d-lg-flex flex-column">
          <v-card elevation="10" rounded="xl" class="mb-5">
            <v-card-title
              class="pa-3 primary text-center text-capitalize"
              dark
              rounded="t-xl"
            >
              Worm Home Class
            </v-card-title>

            <v-card-text class="pa-4">
              <v-chip-group active-class="primary--text" column v-model="wclass" multiple>
                <v-chip
                  v-for="(list, index) in wclassItem"
                  :key="index"
                  filter
                  :value="list.value"
                  outlined
                  small
                >
                  {{ list.text }}
                </v-chip>
              </v-chip-group>
            </v-card-text>
            <v-card-actions class="justify-content-center">
              <v-btn @click="clearClass" color=" warning" rounded v-if="showClassButton"
                >Clear</v-btn
              >
            </v-card-actions>
          </v-card>
          <v-card elevation="10" rounded="xl" class="mb-5">
            <v-card-title class="pa-3 primary" dark rounded="t-xl">
              <span class="text-center text-capitalize"> With Effect</span>
            </v-card-title>

            <v-card-text class="pa-4">
              <v-chip-group active-class="primary--text" column v-model="effect" multiple>
                <v-chip
                  v-for="(list, index) in effectItem"
                  :key="index"
                  filter
                  :value="list.value"
                  outlined
                  small
                >
                  {{ list.text }}
                </v-chip>
              </v-chip-group>
            </v-card-text>
            <v-card-actions class="justify-content-center">
              <v-btn @click="clearEffect" color=" warning" rounded v-if="showEffectButton"
                >Clear</v-btn
              >
            </v-card-actions>
          </v-card>
          <v-card elevation="10" rounded="xl" class="mb-5">
            <v-card-title
              class="pa-3 primary text-center text-capitalize"
              dark
              rounded="t-xl"
            >
              With Static
            </v-card-title>

            <v-card-text class="pa-4">
              <v-chip-group
                active-class="primary--text"
                column
                v-model="wstatic"
                multiple
              >
                <v-chip
                  v-for="(list, index) in wstaticItem"
                  :key="index"
                  filter
                  :value="list.value"
                  outlined
                  small
                >
                  {{ list.text }}
                </v-chip>
              </v-chip-group>
            </v-card-text>
            <v-card-actions class="justify-content-center">
              <v-btn @click="clearStatic" color=" warning" rounded v-if="showStaticButton"
                >Clear</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>
<script>

import { mapGetters, mapState } from "pinia";
export default {
  title() {
    return `Webway - Universe`;
  },
  async created() {
    await this.$store.dispatch("getUniverseList");
  },
  data() {
    return {
      windowSize: {
        x: 0,
        y: 0,
      },
      wclass: [],
      effect: [],
      wstatic: [],
      search: "",
      wclassItem: [
        { text: "Class C1", value: 1 },
        { text: "Class C2", value: 2 },
        { text: "Class C3", value: 3 },
        { text: "Class C4", value: 4 },
        { text: "Class C5", value: 5 },
        { text: "Class C6", value: 6 },
        { text: "Shattered C1", value: 7 },
        { text: "Shattered C2", value: 8 },
        { text: "Shattered C3", value: 9 },
        { text: "Shattered C4", value: 10 },
        { text: "Shattered C5", value: 11 },
        { text: "Shattered C6", value: 12 },
        { text: "Frig-WH", value: 13 },
        { text: "Drifters-WH", value: 14 },
      ],
      effectItem: [
        { text: "No effect", value: 1 },
        { text: "Black Hole", value: 2 },
        { text: "Cataclysmic Variable", value: 3 },
        { text: "Magnetar", value: 4 },
        { text: "Pulsar", value: 5 },
        { text: "Red Giant", value: 6 },
        { text: "Wolf-Rayet", value: 7 },
      ],
      wstaticItem: [
        { text: "Class 1", value: 1 },
        { text: "Class 2", value: 2 },
        { text: "Class 3", value: 3 },
        { text: "Class 4", value: 4 },
        { text: "Class 5", value: 5 },
      ],
      headers: [
        {
          text: "System",
          value: "name",
        },
        {
          text: "Const",
          value: "inConst",
        },

        {
          text: "Region",
          value: "inRegion",
        },
        {
          text: "Class",
          value: "inClass",
        },
        {
          text: "Effect",
          value: "",
        },

        {
          text: "Static",
          value: "inStatic",
        },
      ],
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
      this.wclass = [];
    },

    clearEffect() {
      this.effect = [];
    },

    clearStatic() {
      this.wstatic = [];
    },
  },

  computed: {
    // ...mapState(["universeList"]),
    height() {
      let num = this.windowSize.y - 307;
      return num;
    },

    showClassButton() {
      if (this.wclass.length > 0) {
        return true;
      }
      return false;
    },

    showEffectButton() {
      if (this.effect.length > 0) {
        return true;
      }
      return false;
    },

    showStaticButton() {
      if (this.wstatic.length > 0) {
        return true;
      }
      return false;
    },

    filteredItems() {
      return this.universeList;
    },

    scrollbarTheme() {
      return this.$vuetify.theme.dark ? "dark" : "light";
    },
  },
};
</script>

<style lang="scss" scoped>
.dark::-webkit-scrollbar {
  width: 15px;
}

.dark::-webkit-scrollbar-track {
  background: #202020;
  border-left: 1px solid #2c2c2c;
}

.dark::-webkit-scrollbar-thumb {
  background: #3e3e3e;
  border: solid 3px #202020;
  border-radius: 7px;
}

.dark::-webkit-scrollbar-thumb:hover {
  background: white;
}
</style>
