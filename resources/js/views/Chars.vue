<template>
  <div class="q-ma-md">
    <q-table
      title="Connections"
      class="my-sticky-header-table myRound bg-webBack"
      :rows="filteredItems"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder"
      row-key="id"
      ref="tableRef"
      dark
      rounded
      color="amber"
      hide-bottom
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Chars</span>
          </div>
        </div>
        <div class="row full-width justify-between items-center q-pb-xs">
          <div class="col-8 q-mt-md q-mx-md">
            <q-btn
              color="positive"
              glossy
              rounded
              icon="fa-solid fa-user-astronaut"
              label="Add"
              href="/esi/add"
            />
          </div>
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
      <template v-slot:body-cell-avatar="props">
        <q-td :props="props">
          <div>
            <q-avatar>
              <img :src="props.value" />
            </q-avatar>
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref, computed, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
document.title = "Webway - My Chars";
let store = useMainStore();
let can = inject("can");
onMounted(async () => {
  await store.getAllByUserId();
  window.Echo.private("user." + store.user_id).listen("CharUpdate", (e) => {
    store.updateChars(e.flag.message);
  });
});
onBeforeUnmount(async () => {
  window.Echo.leave("user." + store.user_id);
});

let pagination = $ref({
  sortBy: "Updated",
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let columns = $ref([
  {
    name: "avatar",
    required: true,
    label: "",
    align: "left",
    style: "width: 1px",
    headerStyle: "width: 1px",
    field: (row) => row.esi_char.avatar,
    format: (val) => `${val}`,
    sortable: true,
  },
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
    name: "last",
    required: true,
    label: "Last System",
    align: "left",
    field: (row) => {
      if (row.last_system) {
        return row.last_system.name;
      } else {
        return "";
      }
    },
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "current",
    required: true,
    label: "Current System",
    align: "left",
    field: (row) => {
      if (row.current_system) {
        return row.current_system.name;
      } else {
        {
          return "";
        }
      }
    },
    format: (val) => `${val}`,
    sortable: true,
  },
]);

let filteredItems = $computed(() => {
  return store.characters.filter((char) => char.esi_char.active == 1);
});
let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.my-sticky-header-table
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
