<template>
  <div class="pr-5 pl-5 pt-1" @paste="onPaste" v-resize="onResize">
    <transition-group
      mode="out-in"
      enter-active-class="animate__animated animate__zoomInDown"
      leave-active-class="animate__animated animate__zoomOutDown"
    >
      <v-row no-gutters v-show="!showSteps" :key="`${showSteps} - No`" class="pt-16">
        <v-col cols="12">
          <v-card>
            <v-card-title>
              YOU ARE NOT TRACKING
              <v-icon class="orange--text">fas fa-exclamation-triangle</v-icon>
            </v-card-title>
            <v-card-text
              >Make sure you have added a Eve ESI and then selected a Character from the
              drop down in the top left.</v-card-text
            >
          </v-card>
        </v-col>
      </v-row>
      <v-row no-gutters v-show="showSteps" :key="`${showSteps} - Yes`">
        <v-col cols="12">
          <v-card color="#0000" flat>
            <v-tabs v-model="lo" background-color="primary" dark center-active>
              <v-tab
                v-for="(route, index) in routes"
                :key="`${index}-step`"
                @click="changeclick()"
              >
                {{ route.current_system.name }}
              </v-tab>
            </v-tabs>
          </v-card>
          <transition
            mode="out-in"
            enter-active-class="animate__animated animate__bounceInRight animate__faster"
            leave-active-class="animate__animated animate__bounceOutLeft animate__faster"
          >
            <v-row
              no-gutters
              v-if="locationCount > 0"
              justify="center"
              :key="`${lo}-row`"
            >
              <v-col cols="12" md="6" class="px-0" sm="12" v-if="showLastTable">
                <SystemTable
                  :key="`${currentSystemRoute_id}-lastTable`"
                  :system="currentSystemRoute_last_system"
                  :type="2"
                  :routeID="currentSystemRoute_id"
                  :lo="lo"
                  :currentSystemPropID="currentSystemRoute_current_system_id"
                  :lastSystemPropID="currentSystemRoute_last_system_id"
                ></SystemTable>
              </v-col>

              <v-col cols="12" md="6" sm="12">
                <SystemTable
                  :key="`${currentSystemRoute_id}-currentTable`"
                  :system="currentSystemRoute_current_system"
                  :lastRouteSystemID="currentSystemRoute_last_system_id"
                  :type="1"
                  :routeID="currentSystemRoute_id"
                  :lo="lo"
                  :currentSystemPropID="currentSystemRoute_current_system_id"
                  :lastSystemPropID="currentSystemRoute_last_system_id"
                ></SystemTable>
              </v-col>
            </v-row>
          </transition>
        </v-col>
      </v-row>
    </transition-group>
  </div>
</template>
<script>
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
export default {
  title() {
    return `Webway - I AM LOST`;
  },
  async created() {
    this.$store.dispatch("getDrifterTypeList");
    Echo.private("char." + this.$store.state.selectedChar).listen(
      "CharLocationUpdate",
      (e) => {
        if (e.flag.flag == 1) {
        }

        if (e.flag.flag == 2) {
        }

        if (e.flag.flag == 3) {
        }

        if (e.flag.flag == 4) {
        }

        if (e.flag.flag == 6) {
          this.esiUpdate = true;
        }

        if (e.flag.flag == 7) {
          this.esiUpdate = false;
        }

        if (e.flag.flag == 8) {
          this.testUpdateLocation();
        }
      }
    );
    // this.enterTracking();
    this.$store.dispatch("clearRouteKeeptracking");
    if (this.$store.state.tracking == true) {
      this.testUpdateLocation();
    }

    // if (this.$store.state.tracking == true) {
    //   this.startTracking();
    // }
  },

  mounted() {
    this.onResize();
    //
  },
  data: () => ({
    lo: 0,
    id: 1,
    name: null,
    systemSelect: null,
    lastTemp: null,
    currentTemp: null,
    channelLast: null,
    channelCurrent: null,
    paste: null,
    windowSize: {
      x: 0,
      y: 0,
    },
    num: 1,
    snack: false,
    esiUpdate: false,
    negNum: 375,
  }),

  methods: {
    // async enterTracking() {
    //   await axios({
    //     method: "post", //you can set what request you want to be
    //     withCredentials: true,
    //     url: "/api/entertracking/" + this.$store.state.selectedChar,
    //     headers: {
    //       Accept: "application/json",
    //       "Content-Type": "application/json",
    //     },
    //   });

    //   this.lo = this.tabLocation;
    //   this.id = this.idCount;
    // },
    async startTracking() {
      this.$store.dispatch("clearRouteKeeptracking");
      let request = {
        tracking: true,
      };
      await axios({
        method: "post", //you can set what request you want to be
        withCredentials: true,
        url: "/api/updatetrackingchar/" + this.$store.state.selectedChar,
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },

    async changeclick() {
      //   this.$nextTick(() => {
      //     var place = this.lo;
      //     this.currentTemp = this.routes[place]["current_system_id"];
      //     this.lastTemp = this.routes[place]["last_system_id"];
      //   });
      //   this.$nextTick(() => {
      //     this.$store.dispatch("setTempCurrentSigsBySystemID", this.currentTemp);
      //   });

      //   this.$nextTick(() => {
      //     this.$store.dispatch("getCurrentTempSystemChars", this.currentTemp);
      //   });
      //   if (this.lastTemp) {
      //     this.$nextTick(() => {
      //       this.$store.dispatch("setTempLastSigsBySystemID", this.lastTemp);
      //     });

      //     this.$nextTick(() => {
      //       this.$store.dispatch("getLastTempSystemChars", this.lastTemp);
      //     });
      //   }

      //   this.$nextTick(() => {
      //     var data = {
      //       currentTempSystemID: this.currentTemp,
      //       lastTempSystemID: this.lastTemp,
      //     };
      //     this.$store
      //       .dispatch("setTempSystemids", data)
      //       .then(this.channeljoin(2));
      //   });

      //   this.$nextTick(() => {
      //     this.currentTemp = null;
      //     this.lastTemp = null;
      //   });
      this.$nextTick(() => {
        this.testGetLocationInfo();
      });
    },

    keyidcurrent(route) {
      var a = route.id;
      var b = this.lo;
      return a + "-" + b + "- currentTable";
    },

    keyidcurrentRow() {
      return this.locationCount + " row ";
    },

    keyidlast(route) {
      var a = route.id;
      var b = this.lo;
      return a + "-" + b + "- lastTable";
    },

    channeljoin(type) {
      if (type == 1) {
        this.channelCurrent = this.currentSystemId;
        this.channelLast = this.lastSystemId;
      }

      if (type == 2) {
        this.channelCurrent = this.$store.state.currentTempSystemID;
        this.channelLast = this.$store.state.lastTempSystemID;
      }
    },
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    onPaste(evt) {
      this.paste = evt.clipboardData.getData("text");

      var request = {
        paste: evt.clipboardData.getData("text"),
        system_id: this.currentSystemId,
      };

      axios({
        method: "POST",
        withCredentials: true,
        url: "api/signature/post",
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      })
        .then((res) => {
          if (res.status == 200) {
            this.$toast.success("Probe Scan has been entered", {
              position: "bottom-center",
              timeout: 2000,
              closeOnClick: true,
              pauseOnFocusLoss: true,
              pauseOnHover: true,
              draggable: false,
              draggablePercent: 0.6,
              showCloseButtonOnHover: false,
              hideProgressBar: false,
              closeButton: "button",
              icon: "fas fa-globe fa-lg",
              rtl: false,
            });
          }
        })
        .catch((res) => {
          console.log(res);
          if (res.status == 500) {
            this.$toast.error(
              "Something wrong with the Scan.  Make sure you copied the correct stuff.",
              {
                position: "bottom-center",
                timeout: 2000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: false,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: "button",
                icon: "fas fa-globe fa-lg",
                rtl: false,
              }
            );
          }
        });
    },

    async testUpdateLocation() {
      let res = await axios({
        method: "GET",
        withCredentials: true,
        url: "api/getlocationinfo/" + this.$store.state.selectedChar,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      //   console.log(res.data);
      this.$store.dispatch("testLocationUpdate", res.data);
      this.lo = res.data.count - 1;
    },

    async testGetLocationInfo() {
      let res = await axios({
        method: "GET",
        withCredentials: true,
        url: "api/getlocationinfobytrackid/" + this.currentSystemRoute_id,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      //   console.log(res.data);
      this.$store.dispatch("testLocationInfo", res.data);
      this.lo = res.data.count - 1;
    },
  },

  computed: {
    ...mapState([
      "route",
      "userLocation",
      "systemlist",
      "currentSystemId",
      "lastSystemId",
      "tracking",
      "",
    ]),

    ...mapGetters(["getLocationCount", "getCurrentRoute"]),

    routes() {
      return this.route;
    },

    showLastTable() {
      if (this.$store.state.lastSystemId) {
        return true;
      } else {
        return false;
      }
    },

    showSystemInput() {
      if (this.$can("super_admin") && this.$store.state.showSystemInput) {
        this.negNum = 500;
        return true;
      } else {
        this.negNum = 375;
        return false;
      }
    },

    currentSystemRoute_id() {
      var place = this.lo;
      var r = this.routes[place];
      return r.id;
    },

    currentSystemRoute_last_system() {
      var place = this.lo;
      var r = this.routes[place];
      return r.last_system;
    },

    currentSystemRoute_current_system() {
      var place = this.lo;
      var r = this.routes[place];
      return r.current_system;
    },

    currentSystemRoute_current_system_id() {
      var place = this.lo;
      var r = this.routes[place];
      return r.current_system_id;
    },

    currentSystemRoute_last_system_id() {
      var place = this.lo;
      var r = this.routes[place];
      return r.last_system_id;
    },

    height() {
      let num = this.windowSize.y - this.negNum;
      return num;
    },
    locationCount() {
      return this.getLocationCount;
    },

    tabLocation() {
      var num = this.getLocationCount;
      num = num - 1;
      return num;
    },

    idCount() {
      var num = this.getLocationCount;
      num = num + 1;
      return num;
    },

    channel() {
      return this.currentSystemId;
    },

    showLastTable() {
      var num = this.lo + 1;
      if (this.lo > 0) {
        if (this.route[this.lo]["count"] == num) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    },

    showCurrentTable() {
      var num = this.lo + 1;
      if (this.route[this.lo]["count"] == num) {
        return true;
      } else {
        return false;
      }
    },

    showSteps() {
      if (this.tracking) {
        return true;
      } else {
        return false;
      }
    },
  },
  beforeDestroy() {
    Echo.leave("char." + this.$store.state.selectedChar);
  },
};
</script>
<style scoped>
.clearBack {
  background-color: #0000;
}
</style>
