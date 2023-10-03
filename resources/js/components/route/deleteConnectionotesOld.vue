<template>
  <div>
    <v-btn
      v-if="deleteButton"
      color="red"
      icon
      x-small
      dark
      @click="deletenote()"
    >
      <v-icon>fas fa-trash-alt</v-icon>
    </v-btn>
  </div>
</template>

<script>
// import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
  props: {
    item: Object,
  },
  data() {
    return {};
  },

  async created() {},

  methods: {
    async deletenote() {
      await axios({
        method: "delete",
        withCredentials: true,
        url: "/api/deleteconnectionnote/" + this.item.id,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      });

      var text = "Note deleted";

      this.$toast.error(text, {
        position: "bottom-center",
        timeout: 1000,
        closeOnClick: true,
        pauseOnFocusLoss: false,
        pauseOnHover: false,
        draggable: false,
        draggablePercent: 0.6,
        showCloseButtonOnHover: false,
        hideProgressBar: false,
        closeButton: "button",
        icon: "fas fa-route fa-lg",
        rtl: false,
      });
    },
  },

  computed: {
    deleteButton() {
      if ($can("delete_connection_notes")) {
        return true;
      } else {
        return false;
      }
    },
  },

  beforeDestroy() {},
};
</script>

<style></style>
