<template>
  <div>
    <q-btn
      flat
      icon="fa-solid fa-comment-alt"
      label="Connection Feedback"
      @click="open()"
    />
    <q-dialog v-model="openMessage" persistent>
      <q-card class="myRoundTop" style="width: 1200px; max-width: 1200px">
        <q-card-section class="bg-primary text-h4 text-center">
          Rate this Connection
        </q-card-section>
        <q-card-section>
          <q-table
            v-if="can('view_connection_notes')"
            title="Connections"
            class="myConnectionFeedBackTable myRound"
            :rows="filteredItems"
            flat
            hide-bottom
            :columns="columns"
            table-class=" text-webway"
            table-header-class=" text-weight-bolder bg-amber"
            row-key="id"
            ref="tableRef"
            dark
            rounded
            color="webwayback"
            :pagination="pagination"
          >
            <template v-slot:top="props"> </template>
            <template v-slot:body-cell-actions="props">
              <q-td :props="props">
                <DeleteConnectionNotes :item="props.row" />
              </q-td>
            </template>
          </q-table>
        </q-card-section>
        <q-card-section>
          <q-input
            input-style="width: 1200px"
            v-model="editText"
            placeholder="Enter notes here"
            outlined
            type="textarea"
          />
        </q-card-section>
        <q-card-section class="row items-center justify-between">
          <div class="col-auto">
            <q-rating v-model="rating" size="xl" icon="star" />
          </div>
          <div class="col-auto q-gutter-md">
            <q-btn
              color="primary"
              rounded
              label="Submit"
              @click="updatetext()"
              :disabled="submitActive"
            />

            <q-btn color="secondary" label="Close" rounded @click="close()" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { date } from "quasar";
let can = inject("can");
const DeleteConnectionNotes = defineAsyncComponent(() =>
  import("./deleteConnectionotes.vue")
);
let store = useMainStore();
const props = defineProps({
  item: Object,
});
const emit = defineEmits(["feedbackclosed"]);
let pagination = $ref({
  sortBy: "time",
  descending: true,
  page: 1,
  rowsPerPage: 0,
});
onMounted(async () => {
  window.Echo.private("connectionnotes." + props.item.connection.id).listen(
    "ConnectionNotesUpdate",
    (e) => {
      if (e.flag.flag == 1) {
        store.getConnectionNotes(props.item.connection.id);
      }
    }
  );

  await store.getConnectionNotes(props.item.connection.id).then((loading = false));
});

onBeforeUnmount(async () => {
  window.Echo.leave("connectionnotes." + props.item.connection.id);
});

let openMessage = $ref(false);
let editText = $ref(null);
let rating = $ref(0);
let loading = $ref(true);

let open = () => {
  openMessage = true;
};

let close = () => {
  editText = null;
  rating = 0;
  openMessage = false;
  emit("feedbackclosed");
};

let updatetext = async () => {
  let request = {
    connection_id: props.item.connection.id,
    text: editText,
    rating: rating,
  };

  await axios({
    method: "post",
    withCredentials: true,
    url: "/api/addconnectionnote",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  editText = null;
  rating = 0;
  emit("feedbackclosed");
};

let filteredItems = $computed(() => {
  return store.connectionNotes;
});

let submitActive = $computed(() => {
  if (editText != null || rating != 0) {
    return false;
  } else {
    return true;
  }
});

let columns = $ref([
  {
    name: "test",
    required: true,
    label: "Text",
    align: "left",
    field: (row) => row.text,
    format: (val) => `${val}`,
  },
  {
    name: "user",
    required: true,
    label: "User",
    align: "left",
    field: (row) => row.user_madeby.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "time",
    required: true,
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
    name: "rating",
    required: true,
    label: "Rating",
    align: "left",
    field: (row) => row.rating,
    format: (val) => `${val}`,
  },
  {
    name: "actions",
    label: "",
    align: "right",
  },
]);
</script>

<style lang="sass">
.myConnectionFeedBackTable
  /* height or max-height is important */
  height: 500px


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
