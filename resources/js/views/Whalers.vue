<template>
  <div class="q-ma-md">
    <div class="row">
      <div class="col-12">
        <div class="row justify-around">
          <div class="col-2">
            <q-card class="my-card myRound">
              <q-card-section class="bg-primary myCardHeader">
                <div class="text-h6 text-center">Filter</div>
              </q-card-section>
              <q-card-section>
                <q-select
                  rounded
                  outlined
                  v-model="filterByRegionPicked"
                  :options="filteredDropdownEnd"
                  label="Filter By Regions"
                  option-value="value"
                  option-label="text"
                  multiple
                  @update:model-value="filterText = null"
                  use-input
                  input-debounce="0"
                  virtual-scroll-slice-size="5"
                  use-chips
                  @filter="filterFn"
                  map-options
                  stack-label
                >
                  <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
                    <q-item v-bind="itemProps">
                      <q-item-section>
                        <q-item-label v-html="opt.text" />
                      </q-item-section>
                      <q-item-section side>
                        <q-toggle
                          :model-value="selected"
                          @update:model-value="toggleOption(opt)"
                        />
                      </q-item-section>
                    </q-item>
                  </template>

                  <template v-slot:selected-item="scope">
                    <q-chip
                      removable
                      @remove="scope.removeAtIndex(scope.index)"
                      :tabindex="scope.tabindex"
                      color="webChip"
                      text-color="white"
                      class="q-ma-none"
                    >
                      <span class="text-xs"> {{ scope.opt.text }} </span>
                    </q-chip>
                  </template>
                </q-select>
              </q-card-section>
            </q-card>
          </div>
          <div class="col-9">
            <q-table
              class="myTableFishing myRound bg-webBack"
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
                    <span class="text-h4">Fishing</span>
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

              <template v-slot:body-cell-add="props">
                <q-td :props="props"> <AddDrifterWhale :item="props.row" /> </q-td>
              </template>

              <template v-slot:body-cell-check="props">
                <q-td :props="props">
                  <q-btn
                    size="xs"
                    padding="xs"
                    class="myOutLineButton"
                    color="warning"
                    rounded
                    label="Checked"
                    @click="checked(props.row)"
                  />
                </q-td>
              </template>

              <template v-slot:body-cell-updatedBy="props">
                <q-td :props="props">
                  <q-chip
                    size="sm"
                    v-if="props.row.user"
                    :class="color(props.row.user.roles)"
                  >
                    <q-avatar>
                      <img :src="url(props.row.user.main_character_id)" />
                    </q-avatar>
                    {{ props.row.user.name }}
                  </q-chip>
                </q-td>
              </template>

              <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                  <q-btn
                    push
                    padding="none"
                    size="sm"
                    text-color="negative"
                    round
                    icon="fa-solid fa-spray-can-sparkles fa-flip-horizontal"
                    @click="clear(props.row)"
                  />
                </q-td>
              </template>
            </q-table>
          </div>
        </div>
        <div class="col"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent } from "vue";
import { useMainStore } from "@/store/useMain.js";
import { useQuasar } from "quasar";
const $q = useQuasar();
let store = useMainStore();
document.title = "Webway - Whaling";

const AddDrifterWhale = defineAsyncComponent(() =>
  import("../components/whale/addDrifterWhale.vue")
);
onMounted(async () => {
  window.Echo.private("whalers").listen("WhaleUpdate", (e) => {
    if (e.flag.flag == 1) {
      store.updateJoveSystem(e.flag.message);
    }

    if (e.flag.flag == 2) {
    }
  });

  await store.getJoveRegions();
  await store.getJoveSystems().then((loading = false));
});

onBeforeUnmount(async () => {
  window.Echo.leave("Whalers");
});

let pagination = $ref({
  sortBy: "lastUpdated",
  descending: true,
  page: 1,
  rowsPerPage: 50,
});

let loading = $ref(true);

let url = (userID) => {
  return "https://image.eveonline.com/Character/" + userID + "_128.jpg";
};

let color = (roles) => {
  var count = 0;
  count = count + 1;
  if (roles.filter((r) => r.name == "Coord").legnth > 0) {
    count = count + 1;
  }

  if (roles.filter((r) => r.name == "Whalers").legnth > 0) {
    count = count + 1;
  }

  if (roles.filter((r) => r.name == "GuardBee").legnth > 0) {
    count = count + 1;
  }

  if (count > 0) {
    return "greenfade";
  } else {
    return "bluefade";
  }
};

let checked = async (item) => {
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/jovesystemlastupdated/" + item.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let clear = async (item) => {
  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/jovesystemlastupdated/" + item.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  await axios({
    method: "POST",
    withCredentials: true,
    url: "api/jovesystemclearSigs/" + item.system_id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  $q.notify({
    type: "positive",
    message: item.system.name + " has been cleared of drifter holes",
    progress: true,
    position: "top-right",
    icon: "fa-solid fa-spray-can-sparkles",
  });
};

let filterText = $ref();
let filterByRegionPicked = $ref([]);

let filteredDropdownStart = $computed(() => {
  return store.joveRegionList;
});

let filteredDropdownEnd = $computed(() => {
  let data = [];
  if (filterText) {
    return filteredDropdownStart.filter(
      (v) => v.text.toLowerCase().indexOf(filterText) > -1
    );
  }

  return store.joveRegionList;
});

let filterFn = (val, update, abort) => {
  update(() => {
    filterText = val.toLowerCase();
    if (filterText === "") {
      return store.joveRegionList;
    } else {
      return filteredDropdownEnd;
    }
  });
};

let filteredItems = $computed(() => {
  return store.joveSystems;
});

let filterEnd = $computed(() => {
  let data = [];
  let picked = filterByRegionPicked;
  if (picked.length != 0) {
    picked.forEach((p) => {
      let pick = filteredItems.filter((f) => f.system.region_id == p.value);
      if (pick != null) {
        pick.forEach((pk) => {
          data.push(pk);
        });
      }
    });
    return data;
  }
  return filteredItems;
});

let columns = $ref([
  {
    name: "region",
    required: true,
    label: "Region",
    align: "left",
    field: (row) => row.system.region.name ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "system",
    required: true,
    label: "System",
    align: "left",
    field: (row) => row.system.name ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "add",
    label: "Add",
    align: "left",
  },
  {
    name: "check",
    align: "left",
    label: "Check",
  },
  {
    name: "lastUpdated",
    label: "Last Updated",
    align: "left",
    field: (row) => {
      if (row.last_updated) {
        return row.last_updated;
      } else {
        return "";
      }
    },
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "updatedBy",
    label: "By",
    align: "left",
    field: (row) => {
      if (row.user) {
        return row.user.name;
      } else {
        return "";
      }
    },
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "count",
    label: "Count",
    align: "center",
    field: (row) => row.sigs_count ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "barbican",
    label: "Barbican",
    align: "center",
    field: (row) => row.barbican ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "conflux",
    label: "Conflux",
    align: "center",
    field: (row) => row.conflux ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "redoubt",
    label: "Redoubt",
    align: "center",
    field: (row) => row.redoubt ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "sentinel",
    label: "Sentinel",
    align: "center",
    field: (row) => row.sentinel ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },
  {
    name: "vidette",
    label: "Vidette",
    align: "center",
    field: (row) => row.vidette ?? null,
    format: (val) => `${val}`,
    sortable: true,
  },

  {
    name: "actions",
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

.myTableFishing
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

<style scoped>
.q-chip.greenfade {
  background: linear-gradient(-45deg, #e952ee, #d755ff, #e014e7, #9a05b8);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

.q-chip.bluefade {
  background: linear-gradient(-45deg, #52e4ee, #6eb0ee, #1470e7, #05acb8);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.transparent-body {
  background: transparent;
}
</style>
