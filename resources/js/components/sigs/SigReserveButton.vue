<template>
  <q-btn
    v-if="count"
    rounded
    push
    outline
    color="white"
    class="text-weight-bold myOutLineButton"
    label="Reserve"
    @click="addChar()"
    size="sm"
  />
  <!-- <div>dance</div> -->
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  item: [Array, Object],
});
let addChar = async () => {
  let request = {
    user_id: store.user_id,
    sig_id: props.item.id,
  };

  await axios({
    method: "post",
    withCredentials: true,
    url: "/api/siguser",
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let count = $computed(() => {
  var users = props.item.reserve.length;
  var max = 1;
  if (props.item.signature_group_id == 4) {
    max = 25;
  }

  if (users == max) {
    return false;
  } else {
    return true;
  }
});
</script>

<style lang="scss"></style>
