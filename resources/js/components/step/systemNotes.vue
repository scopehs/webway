<template>
  <div>
    <q-btn text-color="primary" :icon="icon" round padding="none" @click="open()" />
    <q-dialog v-model="openMessage" persistent @before-hide="close()">
      <q-card style="max-width: 1200px; max-height: 1200px" class="myRoundTop">
        <q-card-section class="myCardHeader bg-primary text-h5 text-center">
          Notes for {{ system.name }}</q-card-section
        >
        <q-card-section>
          <q-table
            class="myRound bg-webBack myStepMessageTable"
            :rows="filteredItems"
            :columns="columns"
            style="width: 1000px"
            table-class="text-webway"
            table-header-class="bg-amber"
            row-key="id"
            dense
            dark
            ref="tableRef"
            rounded
            color="amber"
            :pagination="pagination"
          >
            <template v-slot:top-right="props">
              <q-btn
                flat
                padding="none"
                round
                dense
                :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                @click="props.toggleFullscreen"
                class="q-ml-md"
              />
            </template>

            <template v-slot:body-cell-actions="props">
              <q-td :props="props"
                ><DeleteSystemNotes :item="props.row"></DeleteSystemNotes>
              </q-td>
            </template>
          </q-table>
        </q-card-section>
        <q-card-section v-if="props.type == 1">
          <q-input
            input-style="height: 100px min-width:700px"
            v-model="editText"
            autofocus
            placeholder="Enter notes here"
            outlined
            type="textarea"
          />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            v-if="props.type == 1"
            color="positive"
            label="Submit"
            @click="updatetext()"
            v-close-popup
            :disabled="submitActive"
            rounded
          />
          <q-btn color="secondary" rounded label="Close" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
import { defineAsyncComponent } from "vue";
import { date } from "quasar";
let store = useMainStore();
const props = defineProps({
  system: Object,
  type: Number,
});

const DeleteSystemNotes = defineAsyncComponent(() => import("./deleteSystemNotes.vue"));
let pagination = $ref({
  sortBy: "time",
  descending: true,
  page: 1,
  rowsPerPage: 50,
});

let openMessage = $ref(false);
let editText = $ref(null);

let open = () => {
  openMessage = true;
};

let close = () => {
  editText = null;
  openMessage = false;
};

let updatetext = async () => {
  let request = {
    system_id: props.system.system_id,
    text: editText,
  };

  await axios({
    method: "post",
    withCredentials: true,
    url: "/api/addsystemnotes",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  editText = null;
};

let filteredItems = $computed(() => {
  if (props.type == 1) {
    return store.currentSystemNotes;
  } else {
    return store.lastSystemNotes;
  }
});

let icon = $computed(() => {
  if (props.type == 1) {
    if (store.currentSystemNotes.length == 0) {
      return "fa-regular fa-message";
    } else {
      return "fa-solid fa-message";
    }
  } else {
    if (store.lastSystemNotes.length == 0) {
      return "fa-regular fa-message";
    } else {
      return "fa-solid fa-message";
    }
  }

  // return "far fa-question-circle";
});

let submitActive = $computed(() => {
  if (editText != null) {
    return false;
  } else {
    return true;
  }
});

let columns = $ref([
  {
    name: "text",
    label: "Text",
    align: "left",
    field: (row) => row.text,
    format: (val) => `${val}`,
  },
  {
    name: "user",
    label: "User",
    align: "left",
    field: (row) => row.user.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "time",
    label: "Time",
    align: "left",
    field: (row) => row.created_at,
    format: (val) => {
      let format = date.formatDate(val, "YYYY-MM-DD - HH:mm");
      return format;
    },
    sortable: true,
  },

  {
    name: "actions",
    label: "",
    align: "right",
  },
]);
let h = $computed(() => {
  let mins = 50;
  let window = store.size.height;

  return window - mins + "px";
});
</script>

<style lang="sass">
.myStepMessageTable
  /* height or max-height is important */
  height: v-bind(h) !important

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
<style lang="scss" scoped>
.my-class,
.q-toggle__thumb {
  border-color: currentColor;
}
</style>
