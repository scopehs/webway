<template>
  <div class="q-ma-md">
    <q-table
      class="myRound bg-webBack myGasTableTable"
      :rows="props.filterend"
      :columns="columns"
      table-class="text-webway"
      row-key="id"
      dark
      hide-bottom
      ref="tableRef"
      rounded
      :pagination="pagination"
    >
      <template v-slot:header-cell-sigCount="props">
        <q-th :props="props">
          {{ props.col.label }}
          <q-icon name="fa-solid fa-circle-question" color="primary"
            ><q-tooltip>
              <span class="text-body2">
                Shows the number of known signatures for each nebula
                <br />
                When clicked Gas Info will close and the Signatures table will be filtered
                to show you the signatures
              </span>
            </q-tooltip></q-icon
          >
        </q-th>
      </template>
      <template v-slot:body-cell-ninja="props">
        <q-td :props="props">
          <SigGasNebulasTableSigText :type="1" :num="props.row.ninja" />
        </q-td>
      </template>

      <template v-slot:body-cell-volume="props">
        <q-td :props="props">
          <SigGasNebulasTableSigText :type="2" :num="props.row.units" />
        </q-td>
      </template>

      <template v-slot:body-cell-jedi="props">
        <q-td :props="props">
          <SigGasNebulasTableSigText :type="1" :num="props.row.jedi" />
        </q-td>
      </template>

      <template v-slot:body-cell-rats="props">
        <q-td :props="props">
          <SigGasNebulasTableSigText :type="1" :num="props.row.npc" />
        </q-td>
      </template>

      <template v-slot:body-cell-sigCount="props">
        <q-td :props="props">
          <SigGasNebulasTableSigCount :item="props.row" />
        </q-td>
      </template>

      <template v-slot:body-cell-danage="props">
        <q-td :props="props">
          <SigGasNebulasDamageChip :items="props.row.damage_type" />
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
const SigGasNebulasTableSigText = defineAsyncComponent(() =>
  import("./SigGasNebulasTableSigText.vue")
);

const SigGasNebulasTableSigCount = defineAsyncComponent(() =>
  import("./SigGasNebulasTableSigCount.vue")
);

const SigGasNebulasDamageChip = defineAsyncComponent(() =>
  import("./SigGasNebulasDamageChip.vue")
);

const props = defineProps({
  filterend: [Object, Array],
});

let pagination = $ref({
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let columns = $ref([
  {
    name: "location",
    label: "Location",
    align: "left",
    field: (row) => {
      if (row.locations.length > 0) {
        return row.locations[0].name;
      } else {
        return "";
      }
    },
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "name",

    label: "Name",
    align: "left",
    field: (row) => row.name,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "volume",
    label: "Volume (m3)",
    field: (row) => row.units,
    format: (val) => `${val}`,
    align: "left",
  },
  {
    name: "danage",
    label: "Damage",
    field: (row) => row.damage_type,
    format: (val) => `${val}`,
    align: "left",
  },
  {
    name: "ninja",
    label: "Ninja",
    field: (row) => row.ninja,
    format: (val) => `${val}`,
    align: "left",
  },
  {
    name: "jedi",
    label: "Jedi",
    field: (row) => row.jedi,
    format: (val) => `${val}`,
    align: "left",
  },
  {
    name: "rats",
    label: "Rats",
    field: (row) => row.npc,
    format: (val) => `${val}`,
    align: "left",
  },
  {
    name: "sigCount",
    label: "Sig Count",
    align: "left",
    field: (row) => row.barbican ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },
]);
</script>

<style lang="scss"></style>

<style scoped>
.iconselect {
  color: #2196f3 !important;
}

.iconnotselect {
  color: #a4aaaf !important;
}
</style>
