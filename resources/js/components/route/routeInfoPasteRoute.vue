<template>
  <div>
    <q-btn
      icon="fa-solid fa-question-circle"
      flat
      label="Update Connection Info"
      @click="open()"
    />
    <q-dialog v-model="openInfo" persistent>
      <q-card class="myRoundTop">
        <q-card-section class="row items-center bg-primary">
          <span class="text-h5"
            >Paste In Info for Connection
            {{ props.item.connection.source_sig.signature_id }} {{ textSym }}
            {{ props.item.connection.target_sig.signature_id }}.</span
          >
        </q-card-section>
        <q-card-section>
          <q-input
            input-style="height: 500px"
            v-model="editText"
            :placeholder="infoText"
            outlined
            type="textarea"
          />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            color="primary"
            label="Submit"
            @click="updatetext()"
            :disabled="submitActive"
          />
          <q-btn color="secondary" v-close-popup="2" label="Close" @click="close()" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});
const emit = defineEmits(["infoClosed"]);
let textSym = $ref(" <-> ");
let editText = $ref();
let infoText = $ref(`An unstable wormhole, deep in space. Wormholes of this kind usually collapse after a few days, and can lead to anywhere.

This wormhole seems to lead into unknown parts of space.

This wormhole is reaching the end of its natural lifetime.
This wormhole has not yet had its stability significantly disrupted by ships passing through it.
Larger ships can pass through this wormhole.

Stage 1 = Stable/Fresh
Stage 2 = Destab/Shrink
Stage 3 = Crit/EOL`);

let openInfo = $ref(false);

let close = () => {
  editText = null;

  emit("infoClosed");
  openInfo = false;
};

let open = () => {
  openInfo = true;
};

let updatetext = async () => {
  let request = {
    text: editText,
  };
  await axios({
    method: "post",
    withCredentials: true,
    url: "/api/parse_info/" + props.item.connection.source_sig.id,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });
  close();
};

let submitActive = $computed(() => {
  if (editText != null) {
    return false;
  } else {
    return true;
  }
});
</script>

<style lang="scss"></style>
