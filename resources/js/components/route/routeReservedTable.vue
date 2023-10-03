<template>
  <div>
    <q-table
      title="Connections"
      class="myTableReservedConnections myRound"
      :rows="props.connections"
      :columns="columns"
      table-class=" text-webway"
      table-header-class=" text-weight-bolder bg-amber"
      row-key="id"
      :style="h"
      dense
      ref="tableRef"
      dark
      rounded
      color="amber"
      :pagination="pagination"
    >
      <template v-slot:top="props">
        <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
          <div class="col-12 flex flex-center">
            <span class="text-h4">Reserved Connections </span>
          </div>
        </div>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn
            color="negative"
            flat
            round
            size="md"
            padding="none"
            icon="fa-solid fa-minus-circle"
            @click="remove(props.row)"
        /></q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
const props = defineProps({
  connections: Array,
  height: Number,
});
let pagination = $ref({
  descending: false,
  page: 1,
  rowsPerPage: 10,
});
let remove = async (item) => {
  var request = {
    link: item.link,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/removereservedconnection/" + item.id,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let load = async (item) => {
  var request = {
    link: item.link,
  };

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/loadroute",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let columns = $ref([
  {
    name: "from",
    required: true,
    label: "From",
    align: "left",
    field: (row) => row.source_sig.solar_system.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "fromSig",
    required: true,
    label: "From Sig",
    align: "left",
    field: (row) => row.source_sig.signature_id,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "to",
    required: true,
    label: "To",
    align: "left",
    field: (row) => row.target_sig.solar_system.name,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "toSig",
    required: true,
    label: "To SIg",
    align: "left",
    field: (row) => row.target_sig.signature_id,
    format: (val) => `${val}`,
    sortable: false,
  },
  {
    name: "actions",
    label: "",
    align: "right",
  },
]);
let h = $computed(() => {
  let text = "height: " + props.height + "px";
  return text;
});
</script>

<style lang="sass">
.myTableReservedConnections
  /* height or max-height is important */
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
