<template>
  <div>
    <div v-if="showAddBtn">
      <q-btn
        color="primary"
        class="myOutLineButton"
        outline
        rounded
        label="Claim"
        @click="onClick"
      />
    </div>
    <q-chip :removable="showRemovable" v-else color="accent" @remove="removeClaim()">
      <q-avatar>
        <img :src="url(props.item.broken_claim.user.main_character_id)" />
      </q-avatar>
      {{ props.item.broken_claim.user.name }}
    </q-chip>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  item: Object,
});

let url = (userID) => {
  return "https://image.eveonline.com/Character/" + userID + "_128.jpg";
};

let showRemovable = $computed(() => {
  if (props.item.broken_claim) {
    if (props.item.broken_claim.user.id == store.user_id) {
      return true;
    } else {
      return false;
    }
  }
});

let onClick = async () => {
  await axios({
    method: "POST",
    url: "api/broken/claim/" + props.item.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let removeClaim = async () => {
  await axios({
    method: "delete",
    url: "api/broken/removeclaim/" + props.item.broken_claim.id,
    withCredentials: true,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
};

let showAddBtn = $computed(() => {
  if (props.item.broken_claim) {
    return false;
  } else {
    return true;
  }
});
</script>

<style lang="scss"></style>
