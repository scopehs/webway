<template>
  <div class="row q-pa-md justify-around max-h-screen">
    <div class="col-8">
      <q-table
        title="Messages"
        class="myMessageTable myRound"
        :rows="store.supportRooms"
        :columns="columns"
        table-class=" text-webway"
        table-header-class=" text-weight-bolder"
        row-key="id"
        dense
        ref="tableRef"
        dark
        :filter="filterText"
        rounded
        color="amber"
        hide-bottom
        v-model:pagination="pagination"
      >
        <template v-slot:top="props">
          <div class="row full-width justify-between">
            <div class="col-6">
              <span class="text-h6">Messages</span>
            </div>
            <div class="col-3 flex justify-end">
              <q-input
                borderless
                dense
                debounce="300"
                v-model="filterText"
                placeholder="Search"
              >
                <template v-slot:append>
                  <q-icon q-icon name="fa-solid fa-magnifying-glass" />
                </template>
              </q-input>
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
        <template v-slot:body-cell-actions="item">
          <q-td :props="item">
            <div class="row justify-end q-gutter-sm items-baseline">
              <div class="col-auto"><ChatWindow :item="item.row" /></div>
              <div class="col-auto">
                <q-btn
                  text-color="negative"
                  round
                  flat
                  padding="none"
                  icon="fa-solid fa-trash-can"
                  @click="clickDelete(item.row.id)"
                />
              </div>
            </div>
          </q-td> </template
      ></q-table>
    </div>
    <div class="col-3">
      <q-card class="my-card myRound">
        <q-card-section class="bg-primary myCardHeader text-center text-h6">
          Users
        </q-card-section>
        <q-card-section>
          <q-select
            v-model="selectedUser"
            option-value="value"
            option-label="text"
            @filter="filterFn"
            input-debounce="0"
            map-options
            use-input
            hide-selected
            :options="dropDownOptionEnd"
            label="Standard"
            filled
            fill-input
            @update:model-value="changedChar()"
        /></q-card-section>
        <q-card-actions align="center">
          <q-btn flat label="add" @click="changedChar()" />
        </q-card-actions>
      </q-card>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
const ChatWindow = defineAsyncComponent(() =>
  import("../components/chat/chatWindow.vue")
);
let store = useMainStore();
let pagination = $ref({
  sortBy: "updatedAt",
  descending: true,
  page: 1,
  rowsPerPage: 0,
});
onMounted(async () => {
  await window.Echo.private("rooms").listen("RoomsUpdate", (e) => {
    if (e.flag.flag == 1) {
      store.updateRoom(e.flag.message);
    }

    if (e.flag.flag == 2) {
      store.updateWebWayMessage(e.flag.message, e.flag.room_id);
    }
    if (e.flag.flag == 3) {
    }

    if (e.flag.flag == 4) {
    }
  });

  await store.getSupportRooms();
  await store.getFullUserList();
});

onBeforeUnmount(async () => {
  await window.Echo.leave("rooms");
});

let filterText = $ref("");
let userFilterText = $ref();
let selectedUser = $ref();

let dropDownOptionStart = $computed(() => {
  return store.userList;
});

let dropDownOptionEnd = $computed(() => {
  let data = [];
  if (userFilterText) {
    return dropDownOptionStart.filter(
      (v) => v.text.toLowerCase().indexOf(userFilterText) > -1
    );
  }

  return store.userList;
});

let filterFn = (val, update, abort) => {
  update(() => {
    userFilterText = val.toLowerCase();
    if (dropDownOptionEnd.length > 0 && val) {
      selectedUser = dropDownOptionEnd[0];
    }
  });
};

let changedChar = async () => {
  await axios({
    method: "post", //you can set what request you want to be
    withCredentials: true,
    url: "/api/support/makeroom/" + selectedUser.value,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let clickDelete = async (id) => {
  await axios({
    method: "delete", //you can set what request you want to be
    withCredentials: true,
    url: "/api/support/delete/" + id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  store.supportRooms = store.supportRooms.filter((r) => r.id != id);
};

let columns = $ref([
  {
    name: "name",
    label: "Name",
    align: "left",
    field: (row) => row.user.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "updatedAt",
    label: "Last Updated",
    align: "left",
    field: (row) => row.updated_at,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "actions",
    label: "Actions",
    align: "right",
  },
]);
let h = $computed(() => {
  let mins = 100;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myMessageTable
  /* height or max-height is important */
  height: v-bind(h)

  .q-table__top,
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
