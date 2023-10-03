<template>
  <div class="q-ma-md">
    <div class="row">
      <div class="col-12">
        <div class="row justify-around">
          <div class="col-8">
            <q-table
              class="myTableAdminPannel myRound bg-webBack"
              :rows="filterEnd"
              :columns="columns"
              table-class=" text-webway"
              table-header-class=" text-weight-bolder"
              row-key="id"
              dense
              virtual-scroll
              ref="tableRef"
              dark
              rounded
              color="webway"
              :pagination="pagination"
            >
              <template v-slot:top="props">
                <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
                  <div class="col-12 flex flex-center">
                    <span class="text-h4">Users</span>
                  </div>
                </div>
                <div class="row full-width justify-end items-center q-pb-xs">
                  <div class="col-3 flex justify-end">
                    <q-btn
                      flat
                      round
                      dense
                      :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                      @click="props.toggleFullscreen"
                      class="q-ml-md"
                    />
                  </div>
                </div>
              </template>
              <template v-slot:body-cell-roles="props">
                <q-td :props="props">
                  <q-chip
                    color="webChip"
                    text-color="white"
                    class="q-ma-none q-mr-xs"
                    v-for="(role, index) in props.row.roles"
                    :key="index"
                  >
                    {{ role.name }}
                  </q-chip>
                </q-td>
              </template>
            </q-table>
          </div>
          <div class="col-3">
            <q-card class="my-card myRoundTop">
              <q-card-section class="bg-primary myCardHeader">
                <div class="text-h4 text-center">Filter</div>
              </q-card-section>
              <q-card-section class="q-py-sm">
                <div class="row">
                  <div class="col">
                    <div class="text-subtitle2 text-webway">Avoid System Type</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <q-chip
                      v-for="(list, index) in allRoles"
                      :key="index"
                      v-model:selected="list.selected"
                      clickable
                      dense
                      :color="selectedColor(list.selected)"
                      outline
                    >
                      <template v-slot:default>
                        <span :class="selctedTextColor(list.selected)">{{
                          list.name
                        }}</span>
                      </template>
                    </q-chip>
                  </div>
                </div>
                <div class="row flex-center q-mt-md" v-if="selectedRoles > 0">
                  <q-btn
                    color="warning"
                    rounded
                    push
                    class="myOutLineButton"
                    dense
                    padding="md"
                    label="Clear"
                    @click="clearSelcted"
                  ></q-btn>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, inject, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
document.title = "Webway - Admin Pannel";
let store = useMainStore();
let can = inject("can");

onMounted(async () => {
  await store.getUsers();
  await store.getRoles();
});

onBeforeUnmount(async () => {});

let pagination = $ref({
  sortBy: "name",
  descending: false,
  page: 1,
  rowsPerPage: 50,
});

let filteredItems = $computed(() => {
  if (selectedRoles > 0) {
    const selectedRoleIds = new Set(
      store.roles.filter((role) => role.selected).map((role) => role.id)
    );
    const filteredUsers = store.usersroles.filter((user) =>
      user.roles.some((role) => selectedRoleIds.has(role.id))
    );

    return filteredUsers;
  }
  return store.usersroles;
});

let allRoles = $computed(() => {
  return store.roles;
});

let selectedRoles = $computed(() => {
  let count = store.roles.filter((r) => r.selected == true);
  return count.length;
});

let clearSelcted = () => {
  store.roles.forEach((role) => {
    if (role.selected) {
      role.selected = false;
    }
  });
};

let selectedColor = (selected) => {
  if (selected) {
    return "primary";
  } else {
    return "webChip";
  }
};

let selctedTextColor = (selected) => {
  if (selected) {
    return "text-primary text-weight-thin";
  } else {
    return "text-white text-weight-thin";
  }
};

let filterEnd = $computed(() => {
  //   let data = [];
  //   let picked = filterByRegionPicked;
  //   if (picked.length != 0) {
  //     picked.forEach((p) => {
  //       let pick = filteredItems.filter((f) => f.system.region_id == p.value);
  //       if (pick != null) {
  //         pick.forEach((pk) => {
  //           data.push(pk);
  //         });
  //       }
  //     });
  //     return data;
  //   }
  return filteredItems;
});

let columns = $ref([
  {
    name: "name",
    required: true,
    label: "Name",
    align: "left",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "roles",
    required: true,
    label: "Roles",
    align: "left",
    field: (row) => row.roles,
    format: (val) => `${val}`,
    sortable: true,
  },
]);

let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">

.myTableAdminPannel
  /* height or max-height is important */
  height: v-bind(h)

  .q-table__top
    padding-top: 0 !important
    padding-left: 0 !important
    padding-right: 0 !important



  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: #202020

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
