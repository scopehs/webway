<template>
  <div>
    <q-btn
      v-if="deleteButton"
      color="negative"
      icon="fa-solid fa-minus-circle"
      flat
      size="sm"
      round
      @click="deletenote()"
    />
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { inject } from "vue";
const $q = useQuasar();
const props = defineProps({
  item: Object,
});
let can = inject("can");

let deletenote = async () => {
  await axios({
    method: "delete",
    withCredentials: true,
    url: "/api/deleteconnectionnote/" + props.item.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  var text = "Note deleted";
  $q.notify({
    type: "warning",
    message: text,
  });
};

let deleteButton = $computed(() => {
  if (can("delete_connection_notes")) {
    return true;
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
