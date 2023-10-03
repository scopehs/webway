<template>
  <div>
    <q-btn color="primary" rounded label="Gas Info" @click="showMenu = true" />

    <q-dialog v-model="showMenu">
      <q-table
        title="Connections"
        class="myRound bg-webBack myGasTableTable"
        :rows="filterend"
        style="width: 1200px; max-width: 1200px; height: 800px"
        :columns="columns"
        table-class="text-webway"
        table-header-class="bg-amber"
        row-key="id"
        dense
        :filter="search"
        dark
        hide-header
        ref="tableRef"
        rounded
        color="amber"
        v-model:expanded="expanded"
        :pagination="pagination"
      >
        <template v-slot:bottom="props">
          <div class="row full-width justify-around align-baseline">
            <!-- <div class="col flex items-baseline q-pt-sm">dwdw</div> -->
          </div>
        </template>

        <template v-slot:top="props">
          <div class="row full-width flex-center q-pt-xs myRoundTop bg-primary">
            <div class="col-12 flex flex-center">
              <span class="text-h6">Gas Info</span>
            </div>
          </div>
          <div class="row justify-around full-width bg-webBack q-pt-xs">
            <div class="col-5">
              <q-input
                standout
                clearable
                rounded
                dense
                debounce="300"
                v-model="search"
                type="text"
                label="Search for Gas Type"
              />
            </div>
            <div class="col-5">
              <q-input
                standout
                clearable
                rounded
                debounce="300"
                dense
                v-model="searchSigs"
                type="text"
                label="Search for Nebula Name"
              />
            </div>
          </div>
          <div class="row justify-around full-width bg-webBack q-pt-xs">
            <div class="col-auto">
              <q-toggle
                :true-value="1"
                :false-value="0"
                toggle-indeterminate
                v-model="damage"
                left-label
                ><template v-slot:default>
                  <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__flash "
                  >
                    <span :class="toggleTextColor(damage)" :key="`damage-${damage}`"
                      >Damage</span
                    >
                  </transition>
                </template>
              </q-toggle>
              <transition
                mode="out-in"
                enter-active-class="animate__animated animate__flash "
              >
                <span :class="toggleTextColor(damage)" :key="`damage-${damage}`">
                  - {{ toggleText(damage) }}</span
                >
              </transition>
            </div>
            <div class="col-auto">
              <q-toggle
                :true-value="1"
                :false-value="0"
                toggle-indeterminate
                v-model="npc"
                left-label
                ><template v-slot:default>
                  <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__flash "
                  >
                    <span :class="toggleTextColor(npc)" :key="`npc-${npc}`">Rats</span>
                  </transition>
                </template>
              </q-toggle>
              <transition
                mode="out-in"
                enter-active-class="animate__animated animate__flash "
              >
                <span :class="toggleTextColor(npc)" :key="`npc-${npc}`">
                  - {{ toggleText(npc) }}</span
                >
              </transition>
            </div>
            <div class="col-auto">
              <q-toggle
                :true-value="1"
                :false-value="0"
                toggle-indeterminate
                v-model="ninja"
                left-label
                ><template v-slot:default>
                  <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__flash "
                  >
                    <span :class="toggleTextColor(ninja)" :key="`ninja-${ninja}`"
                      >Ninja</span
                    >
                  </transition>
                </template>
              </q-toggle>
              <transition
                mode="out-in"
                enter-active-class="animate__animated animate__flash "
              >
                <span :class="toggleTextColor(ninja)" :key="`ninja-${ninja}`">
                  - {{ toggleText(ninja) }}</span
                >
              </transition>
            </div>

            <div class="col-auto">
              <q-toggle
                :true-value="1"
                :false-value="0"
                toggle-indeterminate
                v-model="jedi"
                left-label
                ><template v-slot:default>
                  <transition
                    mode="out-in"
                    enter-active-class="animate__animated animate__flash "
                  >
                    <span :class="toggleTextColor(jedi)" :key="`jedi-${jedi}`">Jedi</span>
                  </transition>
                </template>
              </q-toggle>
              <transition
                mode="out-in"
                enter-active-class="animate__animated animate__flash "
              >
                <span :class="toggleTextColor(jedi)" :key="`jedi-${jedi}`">
                  - {{ toggleText(jedi) }}</span
                >
              </transition>
            </div>
          </div>
        </template>

        <template v-slot:body="props">
          <q-tr no-hover="" :props="props">
            <q-td key="name" :props="props"
              ><span class="text-h6 text-center">{{ props.row.name }}</span></q-td
            >
          </q-tr>

          <q-tr no-hover="" v-show="props.expand" :props="props" class="myclass">
            <q-td colspan="100%">
              <SigGasNebulasTable :filterend="props.row.nebulas" />
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </q-dialog>
  </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
const SigGasNebulasTable = defineAsyncComponent(() => import("./SigGasNebulasTable.vue"));
let store = useMainStore();
let searchSigs = $ref("");
let search = $ref("");
let showMenu = $ref(false);
let columns = $ref([
  {
    name: "name",
    align: "center",
    field: (row) => row.name,
    format: (val) => `${val}`,
    label: "",
    style: "width: 900px; max-width: 900px",
    headerStyle: "width: 900px; max-width: 900px",
  },
]);

let pagination = $ref({
  descending: false,
  page: 1,
  rowsPerPage: 0,
});

let toggleText = (item) => {
  if (item == 1) {
    return "Yes";
  } else if (item == 0) {
    return "No";
  } else {
    return "???";
  }
};

let toggleTextColor = (item) => {
  if (item == 1) {
    return "text-positive";
  } else if (item == 0) {
    return "text-negative";
  } else {
    return "text-webway";
  }
};

let filterendDamage = $computed(() => {
  if (store.gasFilterDamage == 1 || store.gasFilterDamage == 0) {
    let data = [];
    var info = JSON.parse(JSON.stringify(store.gasInfo));
    info.forEach((g) => {
      var q = g.nebulas.filter((n) => n.damage == store.gasFilterDamage);
      g.nebulas = q;
      data.push(g);
    });

    return data;
  } else {
    return store.gasInfo;
  }
});

let filterendNPC = $computed(() => {
  if (store.gasFilterNPC == 1 || store.gasFilterNPC == 0) {
    let data = [];
    var info = JSON.parse(JSON.stringify(filterendDamage));
    info.forEach((g) => {
      var q = g.nebulas.filter((n) => n.npc == store.gasFilterNPC);
      g.nebulas = q;
      data.push(g);
    });

    return data;
  } else {
    return filterendDamage;
  }
});

let filterendNinja = $computed(() => {
  if (store.gasFilterNinja == 1 || store.gasFilterNinja == 0) {
    let data = [];
    var info = JSON.parse(JSON.stringify(filterendNPC));
    info.forEach((g) => {
      var q = g.nebulas.filter((n) => n.ninja == store.gasFilterNinja);
      g.nebulas = q;
      data.push(g);
    });

    return data;
  } else {
    return filterendNPC;
  }
});

let filterendJedi = $computed(() => {
  if (store.gasFilterJedi == 1 || store.gasFilterJedi == 0) {
    let data = [];
    var info = JSON.parse(JSON.stringify(filterendNPC));
    info.forEach((g) => {
      var q = g.nebulas.filter((n) => n.jedi == store.gasFilterJedi);
      g.nebulas = q;
      data.push(g);
    });

    return data;
  } else {
    return filterendNinja;
  }
});

let filterendSearch = $computed(() => {
  var l = searchSigs.length;
  if (l > 0) {
    let data = [];
    var info = JSON.parse(JSON.stringify(filterendJedi));
    info.forEach((g) => {
      var q = g.nebulas.filter((n) => {
        if (n.name.toLowerCase().includes(searchSigs.toLowerCase())) {
          return true;
        } else {
          return false;
        }
      });
      g.nebulas = q;
      data.push(g);
    });

    return data;
  } else {
    return filterendJedi;
  }
});

let filterend = $computed(() => {
  return filterendSearch.filter((f) => {
    let count = f.nebulas.length;

    if (count > 0) {
      return true;
    } else {
      return false;
    }
  });
});

let expanded = $computed(() => {
  let data = [];
  filterend.forEach((f) => {
    data.push(f.id);
  });

  return data;
});

let damage = $computed({
  get() {
    return store.gasFilterDamage;
  },
  set(newValue) {
    return store.setGasFilterDamage(newValue);
  },
});

let npc = $computed({
  get() {
    return store.gasFilterNPC;
  },
  set(newValue) {
    return store.setGasFilterNPC(newValue);
  },
});

let ninja = $computed({
  get() {
    return store.gasFilterNinja;
  },
  set(newValue) {
    return store.setGasFilterNinja(newValue);
  },
});

let jedi = $computed({
  get() {
    return store.gasFilterJedi;
  },
  set(newValue) {
    return store.setGasFilterJedi(newValue);
  },
});
</script>

<style lang="sass">
.myGasTableTable
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
