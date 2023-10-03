<template>
  <q-chip
    v-for="(reserve, index) in props.reserves"
    :removable="showClosed(reserve)"
    :key="index"
    color="webChip"
    @remove="close(reserve)"
    size="md"
  >
    <q-avatar>
      <img :src="url(reserve.user.main_character_id)" />
    </q-avatar>
    {{ reserve.user.name }}
  </q-chip>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  reserves: Array,
});

let url = (userID) => {
  return "https://image.eveonline.com/Character/" + userID + "_128.jpg";
};

let close = async (reserve) => {
  await axios({
    method: "delete",
    withCredentials: true,
    url: "/api/siguser/" + reserve.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let showClosed = (reserve) => {
  if (store.user_id == reserve.user_id) {
    return true;
  } else {
    return false;
  }
};

let count = $computed(() => {
  return props.reserves.length;
});
</script>

<style lang="scss"></style>
