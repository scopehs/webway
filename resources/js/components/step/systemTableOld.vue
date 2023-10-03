<template>
  <div class="col-xs-12 col-md-12" v-resize="onResize">
    <v-data-table
      :headers="headers"
      :items="filteredItems"
      item-key="id"
      :height="height"
      fixed-header
      dense
      :items-per-page="200"
      :sort-by="['signature_id']"
      :sort-desc="[false, true]"
      class="elevation-24 rounded-xl"
      :hide-default-footer="true"
    >
      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length" align="center">
          <div>
            <v-col class="align-center">
              <v-textarea
                v-model="item.text"
                readonly
                label="What do people think?"
                outlined
                shaped
                >{{ item.text }}</v-textarea
              >
            </v-col>
          </div>
        </td>
      </template>
      <template v-slot:top>
        <v-card :color="backColor" rounded="t-xl">
          <v-row no-gutters justify="space-around" align="center">
            <v-col cols="3">
              <v-card-text>
                Class:&nbsp;{{ classType }}
                <v-icon v-if="shatterIcon">fas fa-skull fa-xs</v-icon> <br />
                Security:&nbsp;<span :class="securityText"> {{ security }}</span
                ><br />
                Effects: &nbsp;{{ effect }}<br />
                Statics:
                <span v-if="showStatics"
                  ><span v-for="(hole, index) in system.statics" :key="index">
                    {{ hole.wormhole_type }}
                    <span :class="staticColor(hole.type.id)">
                      ({{ hole.type.name }})
                    </span>
                    &nbsp;&nbsp;
                  </span></span
                ><br />
              </v-card-text>
            </v-col>
            <v-col cols="7" align-self="start" class="pt-4">
              <v-row no-gutters justify="space-around" align="center">
                <v-col cols="12">
                  <span class="text-center">
                    <span class="h1">
                      {{ title }} -
                      <v-menu transition="fade-transition">
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            class="mx-2"
                            v-bind="attrs"
                            v-on="on"
                            :disabled="disableShowUserMenu"
                            fab
                            color="green darken-4"
                            dark
                            x-small
                          >
                            {{ userCount }}
                          </v-btn>
                        </template>
                        <v-list>
                          <v-list-item v-for="(list, index) in userlist" :key="index">
                            <v-list-item-avatar>
                              <v-img size="36" :src="url(list.id)"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-title> {{ list.name }}</v-list-item-title>
                          </v-list-item>
                        </v-list>
                      </v-menu></span
                    ><br />
                    <span class="h2">{{ system.name }}</span
                    ><span class="h2" v-if="showRegion"
                      >&nbsp;- {{ system.region.name }}</span
                    >
                  </span>
                </v-col>
              </v-row>

              <AddDrifterHole :system="system" :routeID="routeID"></AddDrifterHole>
            </v-col>
            <v-col cols="2">
              <v-row no-gutters>
                <v-col cols="12">
                  <v-card-text> Kills 24h:&nbsp;{{ killCount }} <br /> </v-card-text>
                </v-col>
              </v-row>
              <v-row no-gutters justify="end">
                <v-col cols="2">
                  <SystemNotes :system="system" :type="type"></SystemNotes>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-card>
      </template>
      <template v-slot:body="props">
        <tbody name="list" is="transition-group">
          <template>
            <tr
              class="item-row"
              v-for="(item, index) in props.items"
              v-show="item.delete == 0"
              :key="index"
            >
              <td>
                <CellSigId
                  :value="item.signature_id"
                  :sig="item"
                  :type="type"
                  :edit="edit"
                  :lastSystem="lastSystemPropID"
                  :currentSystem="currentSystemPropID"
                ></CellSigId>
              </td>
              <td>
                <CellStepGroup
                  :value="item.group.name"
                  :typeName="item.name"
                  :typeID="item.group.id"
                ></CellStepGroup>
              </td>
              <td>
                <CellWormholeTypeButton
                  v-if="showAddType(item)"
                  :sig="item"
                  :type="type"
                  :edit="edit"
                  :system="system"
                ></CellWormholeTypeButton>
              </td>
              <td>
                <VueCountUptimer
                  :start-time="moment.utc(item.life_time).unix()"
                  :end-text="'Sig Deaded'"
                  :interval="60000"
                >
                  <template slot="countup" slot-scope="scope">
                    <span class="red--text pl-3"
                      >{{ scope.props.hours }}:{{ scope.props.minutes }}</span
                    >
                  </template>
                </VueCountUptimer>
              </td>
              <td>
                <CellLinkToButton
                  :sig="item"
                  :type="type"
                  :edit="edit"
                ></CellLinkToButton>
              </td>
              <td>
                <Cell :value="infoText(item.wormhole_info_mass)" :type="2"></Cell>
              </td>
              <td>
                <Cell :value="infoText(item.wormhole_info_ship_size)" :type="1"></Cell>
              </td>
              <td>
                <Cell
                  :value="infoText(item.wormhole_info_time_till_death)"
                  :type="2"
                ></Cell>
              </td>

              <td>
                <v-row no-gutters align-content="center" justify="end" v-show="edit">
                  <v-col cols="12" class="d-flex justify-content-end align-items-center">
                    <RouteInfoPaste
                      :id="`${item.id}-infopannel`"
                      v-if="showInfoButton(item)"
                      :sig="item"
                    ></RouteInfoPaste>
                    <!-- gone = set all the delete flags to 1
                                         1 = No roles Is Owner  = normal delete
                                         2 = Has roles Is Owner = Give options to delete or say sig is gone
                                         3 = Has roles Not Owner = Say sig is gone
                                         4 = No roles Not Owner = Report sig as gone. -->
                    <SigNotes :sig="item" :type="type"></SigNotes>
                    <RouteDeleteButtonNoRolesIsOwner
                      v-if="deleteButton(item) == 1"
                      :item="item"
                    ></RouteDeleteButtonNoRolesIsOwner>
                    <RouteDeleteButtonHasRolesIsOwner
                      v-if="deleteButton(item) == 2"
                      :item="item"
                    ></RouteDeleteButtonHasRolesIsOwner>
                    <RouteDeleteButtonHasRolesNotOwner
                      v-if="deleteButton(item) == 3"
                      :item="item"
                    ></RouteDeleteButtonHasRolesNotOwner>
                    <RouteDeleteButtonNoRolesNotOwner
                      v-if="deleteButton(item) == 4"
                      :item="item"
                    ></RouteDeleteButtonNoRolesNotOwner>
                  </v-col>
                </v-row>
              </td>
            </tr>
          </template>
        </tbody>
      </template>

      <!-- <template slot="no-data">
                No SIGS Here
            </template> -->
    </v-data-table>
  </div>
</template>
<script>
import Axios from "axios";
import { mapGetters, mapState } from "vuex";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
import countup from "../countup/countup.vue";
export default {
  components: { countup },
  props: {
    system: Object,
    type: Number,
    lastRouteSystemID: Number,
    routeID: Number,
    lo: Number,
    currentSystemPropID: Number,
    lastSystemPropID: Number,
  },

  beforeCreate() {},

  created() {},

  mounted() {
    if (this.type == 1) {
      Echo.private("mapping." + this.system.system_id).listen("MappingUpdate", (e) => {
        if (e.flag.flag == 1) {
          this.$store.dispatch("updateCurrentSystemSigs", e.flag.message);
        }

        if (e.flag.flag == 2) {
          this.$store.dispatch("deleteCurrentSystemSig", e.flag.id);
          this.$store.dispatch("deleteLastSystemSig", e.flag.id);
        }

        if (e.flag.flag == 3) {
          this.$store.dispatch("updateJove", e.flag.message);
        }
        if (e.flag.flag == 4) {
          this.$store.dispatch("setCurrentSystemChars", e.flag.message);
        }
        if (e.flag.flag == 5) {
          this.$store.dispatch("updateCurrentKillCount", e.flag.kills);
        }
        if (e.flag.flag == 6) {
          this.$store.dispatch("getSystemNotes", {
            type: 1,
            id: this.system.system_id,
          });
        }
        if (e.flag.flag == 7) {
          this.$store.dispatch("getSigNotes", {
            type: 1,
            id: this.system.system_id,
          });
        }
      });

      Echo.private("mapping." + this.lastRouteSystemID).listen("MappingUpdate", (e) => {
        if (e.flag.flag == 1) {
          this.$store.dispatch("updateLastSystemSigs", e.flag.message);
        }
        if (e.flag.flag == 2) {
          this.$store.dispatch("deleteCurrentSystemSig", e.flag.id);
          this.$store.dispatch("deleteLastSystemSig", e.flag.id);
        }
        if (e.flag.flag == 4) {
          this.$store.dispatch("setLastSystemChars", e.flag.message);
        }
        if (e.flag.flag == 5) {
          this.$store.dispatch("updateLastKillCount", e.flag.kills);
        }
        if (e.flag.flag == 6) {
          this.$store.dispatch("getSystemNotes", {
            type: 2,
            id: this.lastSystemPropID,
          });
        }
        if (e.flag.flag == 7) {
          this.$store.dispatch("getSigNotes", {
            type: 2,
            id: this.lastSystemPropID,
          });
        }
      });
    }
    this.onResize();
    // if (this.type == 1) {
    //   this.$store.dispatch("getSystemNotes", {
    //     type: 1,
    //     id: this.system.system_id,
    //   });
    //   this.$store.dispatch("getSigNotes", {
    //     type: 1,
    //     id: this.system.system_id,
    //   });
    // } else {
    //   this.$store.dispatch("getSystemNotes", {
    //     type: 2,
    //     id: this.lastSystemPropID,
    //   });
    //   this.$store.dispatch("getSigNotes", {
    //     type: 2,
    //     id: this.lastSystemPropID,
    //   });
    // }
  },
  data() {
    return {
      headers: [
        {
          text: "ID",
          value: "signature_id",
          align: "center",
        },
        {
          text: "Group",
          value: "group.name",
          align: "center",
        },
        {
          text: "Type",
          value: "",
          align: "center",
        },
        {
          text: "Age(HH:MM)",
          value: "life_left",
          align: "center",
        },
        {
          text: "Link to",
          value: "leads_to",
          align: "center",
        },
        {
          text: "Mass",
          value: "inMass",
          align: "center",
        },

        {
          text: "Size",
          value: "InShite",
          align: "center",
        },

        {
          text: "Life",
          value: "InTTD",
          align: "center",
        },

        {
          text: "ACTION",
          value: "action",
          align: "end",
        },
      ],
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },

  methods: {
    staticColor(id) {
      switch (id) {
        // 1-6 C#
        // 7-9 H/L/N S
        // 12 Thera
        // 13-18 C#
        // 25 Trig
        case 1:
          return "red--text";

        case 2:
          return "red--text";

        case 3:
          return "red--text";

        case 4:
          return "red--text";

        case 5:
          return "red--text";

        case 6:
          return "red--text";

        case 7:
          return "blue--text";

        case 8:
          return "orange--text";

        case 9:
          return "red--text";

        case 12:
          return "red--text";

        case 13:
          return "red--text";

        case 14:
          return "red--text";

        case 15:
          return "red--text";

        case 16:
          return "red--text";

        case 17:
          return "red--text";

        case 18:
          return "red--text";

        case 25:
          return "red--text";
      }
    },

    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    showInfoButton(item) {
      if (item.type) {
        return true;
      } else {
        return false;
      }
    },
    // gone = set all the delete flags to 1
    // 1 = No roles Is Owner  = normal delete
    // 2 = Has roles Is Owner = Give options to delete or say sig is gone
    // 3 = Has roles Not Owner = Say sig is gone
    // 4 = No roles Not Owner = Report sig as gone.
    deleteButton(item) {
      var roles = false;
      var owner = false;
      if (this.$can("delete_sigs")) {
        roles = true;
      }

      if (item.created_by_id == this.$store.state.user_id) {
        owner = true;
      }

      if (roles == false && owner == true) {
        return 1;
      }

      if (roles == true && owner == true) {
        return 2;
      }

      if (roles == true && owner == false) {
        return 3;
      }

      if (roles == false && owner == false) {
        return 4;
      }
    },

    showAddType(item) {
      if (item.signature_group_id == 1) {
        return true;
      } else {
        return false;
      }
    },

    url(charID) {
      return "https://image.eveonline.com/Character/" + charID + "_128.jpg";
    },

    infoText(item) {
      if (item) {
        return item.table_text;
      } else {
        return "";
      }
    },
  },

  computed: {
    ...mapState([
      "currentSystemSigs",
      "lastSystemSigs",
      "currentSystemChars",
      "lastSystemChars",
      "currentKillCount",
      "lastKillCount",
    ]),
    ...mapGetters([
      "getLocationCount",
      "getCurrentSystemCharsCount",
      "getLastSystemCharsCount",
    ]),

    disableShowUserMenu() {
      if (this.userCount == 0) {
        return true;
      } else {
        return false;
      }
    },

    userlist() {
      if (this.type == 1) {
        return this.currentSystemChars;
      } else {
        return this.lastSystemChars;
      }
    },

    killCount() {
      if (this.type == 1) {
        if (this.currentKillCount > 0) {
          return this.currentKillCount;
        } else {
          return 0;
        }
      } else {
        if (this.lastKillCount > 0) {
          return this.lastKillCount;
        } else {
          return 0;
        }
      }
    },

    userCount() {
      if (this.type == 1) {
        return this.getCurrentSystemCharsCount;
      } else {
        return this.getLastSystemCharsCount;
      }
    },

    showRegion() {
      switch (this.system.system_type[0].id) {
        case 7:
          return true;

        case 8:
          return true;

        case 9:
          return true;

        default:
          return false;
      }
    },

    filteredItems() {
      if (this.type == 1) {
        return this.currentSystemSigs;
      } else {
        return this.lastSystemSigs;
      }
    },

    edit() {
      var num = this.lo + 1;
      if (num == this.getLocationCount) {
        return true;
      } else {
        return false;
      }
    },

    height() {
      let num = this.windowSize.y - 339;
      return num;
    },

    title() {
      switch (this.type) {
        case 1:
          return "Current System";
          break;
        default:
          return "Last System";
      }
    },

    classType() {
      return this.system.system_type[0]["name_full"];
    },

    shatterIcon() {
      if (this.system.shattered) {
        return true;
      } else {
        return false;
      }
    },

    classText() {
      if (this.system.system_type[0]["id"] < 7 || this.system.system_type[0]["id"] > 9) {
        return "blue-grey darken-2";
      }

      if (this.system.system_type[0]["id"] == 7) {
        return "blue";
      }

      if (this.system.system_type[0]["id"] == 8) {
        return "amber darken-4";
      }

      if (this.system.system_type[0]["id"] == 9) {
        return "deep-orange darken-2";
      }
    },

    security() {
      var number = this.system.security;
      var rounded = Math.round(number * 10) / 10;
      if (rounded == 1) {
        return "1.0";
      } else {
        if (rounded == -1) {
          return "-1.0";
        } else {
          return rounded;
        }
      }
    },

    securityText() {
      if (this.security <= 0.0) {
        return "red--text";
      }

      if (this.security >= 0.45) {
        return "blue--text";
      }

      return "orange--text";
    },

    effect() {
      if (this.system.effect[0]) {
        return this.system.effect[0]["name"];
      }
    },

    backColor() {
      return "blue-grey darken-4";
      // if (
      //     this.system.system_type[0]["id"] < 7 ||
      //     this.system.system_type[0]["id"] > 9
      // ) {
      //     return "blue-grey darken-2";
      // }

      // if (this.system.system_type[0]["id"] == 7) {
      //     return "blue";
      // }

      // if (this.system.system_type[0]["id"] == 8) {
      //     return "amber darken-4";
      // }

      // if (this.system.system_type[0]["id"] == 9) {
      //     return "deep-orange darken-2";
      //      }
    },

    showStatics() {
      if (this.system.statics.length > 0) {
        return true;
      } else {
        return false;
      }
    },
  },

  beforeDestroy() {
    Echo.leave("mapping." + this.system.system_id);
  },
};
</script>
<style scoped>
.list-enter-active,
.list-leave-active {
  transition: all 0.8s;
}
.list-enter,
.list-leave-to {
  opacity: 0;
  transform: translateY(100%);
}
.list-move {
  transition: transform 0.5s;
}
.item-row {
  display: table-row;
}

.compact-checkbox {
  transform: scale(0.875);
  transform-origin: left;
}

.v-list__tile {
  padding: 0;
}
</style>
