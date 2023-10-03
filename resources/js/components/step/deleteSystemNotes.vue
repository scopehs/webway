<template>
  <div>
    <q-btn
      v-if="deleteButton"
      text-color="negative"
      icon="fa-solid fa-trash-can"
      round
      size="sm"
      padding="none"
      @click="deletenote()"
    />
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { inject } from "vue";
let can = inject("can");
const $q = useQuasar();
const props = defineProps({
  item: Object,
});

let deletenote = async () => {
  await axios({
    method: "delete",
    withCredentials: true,
    url: "/api/deletesystemnotes/" + props.item.id,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  $q.notify({
    type: "positive",
    message: "Note deleted.",
  });
};

let deleteButton = $computed(() => {
  if (can("delete_system_logs") || state.user_id == props.item.user_id) {
    return true;
  } else {
    return false;
  }
});
</script>

<style lang="scss"></style>
