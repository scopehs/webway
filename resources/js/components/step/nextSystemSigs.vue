<template>
  <div>
    <q-btn
      text-color="primary"
      padding="none"
      icon="fa-solid fa-magnifying-glass-arrow-right"
      flat
      @click="open()"
    />
    <q-menu anchor="bottom left" self="top left">
      <q-markup-table dark class="bg-indigo-8">
        <thead>
          <tr>
            <th class="text-left">Type</th>
            <th class="text-right">Complete</th>
            <th class="text-right">Not Complete</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left">Wormhole</td>
            <td class="text-right">{{ wormholesDone }}</td>
            <td class="text-right">{{ wormholesNotDone }}</td>
          </tr>
          <tr>
            <td class="text-left">Relic Site</td>
            <td class="text-right">{{ relicDone }}</td>
            <td class="text-right">{{ relicNotDone }}</td>
          </tr>
          <tr>
            <td class="text-left">Data Site</td>
            <td class="text-right">{{ dataDone }}</td>
            <td class="text-right">{{ dataNotDone }}</td>
          </tr>
          <tr>
            <td class="text-left">Gas Site</td>
            <td class="text-right">{{ gasDone }}</td>
            <td class="text-right">{{ gasNotDone }}</td>
          </tr>
          <tr>
            <td class="text-left">Combat Site</td>
            <td class="text-right">{{ combatDone }}</td>
            <td class="text-right">{{ combatNotDone }}</td>
          </tr>
          <tr>
            <td class="text-left">Ore Site</td>
            <td class="text-right">{{ oreDone }}</td>
            <td class="text-right">{{ oreNotDone }}</td>
          </tr>
          <tr>
            <td class="text-left">Unknowen</td>
            <td class="text-right">-</td>
            <td class="text-right">{{ unknown }}</td>
          </tr>
        </tbody>
      </q-markup-table>
    </q-menu>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
let menu = $ref(false);
const props = defineProps({
  nextSigs: Object,
});

let open = () => {
  menu = true;
};

let wormholes = $computed(() => {
  return props.nextSigs.filter((obj) => obj.signature_group_id === 1);
});

let wormholesDone = $computed(() => {
  return wormholes.filter((obj) => obj.completed_by_id > 0).length;
});

let wormholesNotDone = $computed(() => {
  return wormholes.length - wormholesDone;
});

let relic = $computed(() => {
  return props.nextSigs.filter((obj) => obj.signature_group_id === 2);
});

let relicDone = $computed(() => {
  return relic.filter((obj) => obj.completed_by_id > 0).length;
});

let relicNotDone = $computed(() => {
  return relic.length - relicDone;
});

let data = $computed(() => {
  return props.nextSigs.filter((obj) => obj.signature_group_id === 3);
});

let dataDone = $computed(() => {
  return data.filter((obj) => obj.completed_by_id > 0).length;
});

let dataNotDone = $computed(() => {
  return data.length - dataDone;
});

let gas = $computed(() => {
  return props.nextSigs.filter((obj) => obj.signature_group_id === 4);
});

let gasDone = $computed(() => {
  return gas.filter((obj) => obj.completed_by_id > 0).length;
});

let gasNotDone = $computed(() => {
  return gas.length - gasDone;
});

let combat = $computed(() => {
  return props.nextSigs.filter((obj) => obj.signature_group_id === 5);
});

let combatDone = $computed(() => {
  return combat.filter((obj) => obj.completed_by_id > 0).length;
});

let combatNotDone = $computed(() => {
  return combat.length - combatDone;
});

let ore = $computed(() => {
  return props.nextSigs.filter((obj) => obj.signature_group_id === 6);
});

let oreDone = $computed(() => {
  return ore.filter((obj) => obj.completed_by_id > 0).length;
});

let oreNotDone = $computed(() => {
  return ore.length - oreDone;
});
let unknown = $computed(() => {
  return props.nextSigs.filter((obj) => obj.signature_group_id === 7).length;
});
</script>

<style lang="scss"></style>
