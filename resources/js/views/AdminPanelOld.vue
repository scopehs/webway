<template>
  <v-row class="pr-16 pl-16 pt-16">
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="8">
          <v-card rounded="xl">
            <v-data-table
              :headers="headers"
              :items="filteredItems"
              item-key="id"
              :loading="loading"
              :sort-by="['name']"
              :items-per-page="20"
              :search="search"
              :footer-props="{
                'items-per-page-options': [10, 20, 50, 100, -1],
              }"
              class="elevation-5 rounded-xl"
            >
              <template v-slot:[`item.roles`]="{ item }">
                <div class="d-inline-flex" v-if="$can('super_admin')">
                  <v-menu rounded="xl">
                    <template v-slot:activator="{ on, attrs }">
                      <div>
                        <v-btn icon v-bind="attrs" v-on="on" color="success">
                          <v-icon>fas fa-plus</v-icon>
                        </v-btn>
                      </div>
                    </template>

                    <v-list rounded>
                      <v-list-item
                        v-for="(list, index) in filterDropdownList(item.roles)"
                        :key="index"
                        @click="(userAddRoleText = list.id), userAddRole(item)"
                      >
                        <v-list-item-title>{{ list.name }}</v-list-item-title>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                </div>

                <div class="d-inline-flex">
                  <div
                    v-for="(role, index) in filterRoles(item.roles)"
                    :key="index"
                    class="pr-2"
                  >
                    <v-chip
                      pill
                      :close="pillClose(role.name)"
                      dark
                      @click:close="(userRemoveRoleText = role.id), userRemoveRole(item)"
                    >
                      <span> {{ role.name }}</span>
                    </v-chip>
                  </div>
                </div>
              </template>

              <template v-slot:top>
                <v-row no-gutters>
                  <v-col
                    cols="12"
                    align-self="center"
                    class="justify-around d-flex flex-col"
                  >
                    <v-card tile flat class="d-inline-flex align-content-start">
                      <v-card-title>Add/Remove Roles</v-card-title>
                    </v-card>
                    <v-spacer></v-spacer>
                    <v-card tile flat class="align-start">
                      <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search for Users"
                        single-line
                        hide-details
                      ></v-text-field>
                    </v-card>
                  </v-col>
                </v-row>
              </template>
              <template slot="no-data"> Nothing matches your filters </template>
            </v-data-table>
          </v-card>
        </v-col>
        <v-col cols="4" class="pl-4 d-lg-flex flex-column">
          <v-card elevation="10" rounded="xl" class="mb-5">
            <v-card-title class="pa-3 primary text-center text-capitalize" rounded="t-xl">
              Roles
            </v-card-title>

            <v-card-text class="pa-4">
              <v-chip-group active-class="primary--text" column v-model="wroles">
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
  </v-row>
</template>
<script>
// import moment, { now, utc } from "moment";
import { mapState } from "pinia";
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

export default {
  title() {
    return `Webway - Admin Pannel`;
  },
  data() {
    return {
      //timersAll: [],

      headers: [
        { text: "Name", value: "name" },
        { text: "Roles", value: "roles", width: "75%" },
        { text: "", value: "actions" },
      ],
      loading: true,
      toggle_exclusive: 0,
      search: "",
      addShown: false,
      userAddRoleText: "",
      userRemoveRoleText: "",
      wroles: 0,
      logs: false,
    };
  },

  async created() {
    await this.$store.dispatch("getUsers");
    await this.$store.dispatch("getRoles");
    this.loading = false;
  },

  async mounted() {},

  methods: {
    showClassButton() {
      if (this.wroles.length > 0) {
        return true;
      }
      return false;
    },
    filterRoles(roles) {
      // console.log(roles);
      return roles.filter((r) => r.name != "Super Admin");
    },
    async refresh() {},

    filterDropdownList(item) {
      let roleID = item.map((i) => i.id);

      var filter = this.rolesList.filter((r) => !roleID.includes(r.id));
      filter = filter.filter((r) => r.name != "All");
      if ($can("super_admin")) {
        return filter;
      }
    },

    pillClose(name) {
      if ($can("super_admin")) {
        return true;
      } else {
        return false;
      }
    },

    fliterFleets(fleets) {
      return fleets;
    },

    clearClass() {
      this.wroles = [];
    },

    async userAddRole(item) {
      var request = {
        roleId: this.userAddRoleText,
        userId: item.id,
      };

      await axios({
        method: "put", //you can set what request you want to be
        withCredentials: true,
        url: "/api/rolesadd",
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.$store.dispatch("getUsers");
    },

    async userRemoveRole(item) {
      var request = {
        roleId: this.userRemoveRoleText,
        userId: item.id,
      };

      await axios({
        method: "put", //you can set what request you want to be
        withCredentials: true,
        url: "/api/rolesremove",
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
      this.$store.dispatch("getUsers");
    },
  },

  computed: {
    // ...mapState(["users", "rolesList"]),
    filteredItems() {
      var roleid = this.wroles;
      if (this.wroles != 0) {
        return this.users.filter(function (u) {
          return u.roles.some(function (role) {
            return role.id == roleid;
          });
        });
      } else {
        return this.users;
      }
      // return this.users;
    },

    buttonList() {
      var list = this.rolesList;
      var data = {
        id: 0,
        name: "All",
      };
      list.push(data);
      list.sort(function (a, b) {
        return a.id - b.id || a.name.localeCompare(b.name);
      });

      return list;
    },
  },
  beforeDestroy() {},
};
</script>
