<template>
  <div>
    <q-btn
      text-color="negative"
      icon="fa-solid fa-trash-can"
      round
      flat
      size="sm"
      padding="none"
      @click="deletenote()"
    />
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
const $q = useQuasar();
const props = defineProps({
  item: Object,
});
let deletenote = async () => {
  await axios({
    method: "delete",
    withCredentials: true,
    url: "/api/deletesignote/" + props.item.id,
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
</script>

<style lang="scss"></style>
