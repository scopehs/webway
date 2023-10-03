<template>
  <v-row class="pr-16 pl-16 pt-5" no-gutters v-resize="onResize" justify="center">
    <v-col cols="12">
      <v-row no-gutters>
        <v-col cols="12">
          <NewFeedBackButton></NewFeedBackButton>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :items="filteredItems"
            :expanded.sync="expanded"
            item-key="id"
            show-expand
            :items-per-page="25"
            :footer-props="{
              'items-per-page-options': [15, 25, 50, 100, -1],
            }"
            :sort-by="['created']"
            :sort-desc="[true, false]"
            multi-sort
            class="elevation-1 rounded-xl"
          >
            <template v-slot:[`item.inUserName`]="{ item }">
              {{ item.user.name }}
            </template>

            <template v-slot:expanded-item="{ headers, item }">
              <td :colspan="headers.length" align="center">
                <div>
                  <v-col cols="12" class="align-center">
                    <v-textarea
                      v-model="item.text"
                      readonly
                      label="What do people think?"
                      outlined
                      shaped
                      >{{ item.text }}</v-textarea
                    >
                  </v-col>
                  <v-col cols="12">
                    <v-btn @click="remove(item)"> Delete </v-btn>
                  </v-col>
                </div>
              </td>
            </template>

            <template slot="no-data"> No feed back atm </template>
            <template v-slot:top>
              <v-row no-gutters>
                <v-col cols="12">
                  <v-card color="#607d8b">
                    <v-card-title> Feedback</v-card-title>
                  </v-card>
                </v-col>
              </v-row>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>
<script>

import { mapState } from "pinia";
export default {
  title() {
    return `Webway - Feedback`;
  },
  async mounted() {
    await this.$store.dispatch("getAllFeedBack");
    this.onResize();
  },

  data() {
    return {
      headers: [
        { text: "User", value: "inUserName" },
        { text: "Title", value: "title" },
        { text: "Date", value: "created_at" },
        { text: "Actions", value: "actions", align: "start" },

        // { text: "Vulernable End Time", value: "vulnerable_end_time" }
      ],
      expanded: [],
      expanded_id: 0,
      windowSize: {
        x: 0,
        y: 0,
      },
    };
  },
  methods: {
    onResize() {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight };
    },

    async remove(item) {
      this.$store.dispatch("deleteFeedback", item.id);
      await axios({
        method: "DELETE",
        withCredentials: true,
        url: "api/feedback/" + item.id,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });
    },
  },

  computed: {
    // ...mapState(["feedback"]),

    filteredItems() {
      return this.feedback;
    },

    height() {
      let num = this.windowSize.y - 307;
      return num;
    },
  },
};
</script>
