<template>
  <div>
    <q-btn
      color="webway"
      icon="fa-solid fa-bugs"
      label="Bug Report"
      flat
      @click="showNewFeedBack = true"
    />
    <q-dialog v-model="showNewFeedBack" @before-hide="close()">
      <q-card class="myRoundTop" style="width: 500px">
        <q-card-section class="bg-primary text-h5 text-center">
          <h4 class="no-margin">Give your feed back here</h4>
        </q-card-section>
        <q-card-section>
          <div>
            <q-input
              class="q-mb-md"
              v-model="titleText"
              type="text"
              label="Title"
              rounded
              dense
              outlined=""
              autofocus
            />
          </div>
          <div>
            <q-input
              input-style="height: 500px"
              v-model="mainText"
              clearable
              outlined
              rounded
              dense
              type="textarea"
              label="Describe your bug/feedback here"
            />
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancel" color="warning" @click="close()" />
          <q-btn
            flat
            label="Submit"
            color="primary"
            :disable="submitActive"
            @click="submit()"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
// import { mapState, mapGetters } from "vuex";

import { ref, computed } from "vue";
import { date } from "quasar";
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();

let showNewFeedBack = $ref(false);
let titleText = $ref(null);
let mainText = $ref(null);

let close = () => {
  titleText = null;
  mainText = null;
  showNewFeedBack = false;
};

let submit = () => {
  mainText = mainText + "\n";
  let timestamp = date.buildDate(true);
  let timeformat = date.formatDate(timestamp, "YYYY-MM-DD HH:mm:ss");
  var note = timeformat + " - " + store.user_name + ": " + mainText;
  var request = {
    user_id: store.user_id,
    text: note,
    title: titleText,
  };

  axios({
    method: "POST",
    url: "api/feedback",
    withCredentials: true,
    data: request,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  }).then(close());
};

let submitActive = $computed(() => {
  if (titleText && mainText) {
    return false;
  }

  return true;
});
</script>

<style scoped>
.q-card {
  border-radius: 20px;
}
</style>
