<template>
  <div class="text-center">
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      bottom
      transition="scale-transition"
      rounded
      origin="center"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="warning" dark x-small rounded v-bind="attrs" v-on="on"> Add </v-btn>
      </template>

      <v-card max-width="200px">
        <v-card-text>
          <v-autocomplete
            autofocus
            auto-select-first
            v-model="system_id"
            :items="systemListRoute"
            label="Linked to System"
          >
          </v-autocomplete>
        </v-card-text>
        <v-card-actions>
          <v-btn text @click="closed()"> Cancel </v-btn>
          <v-btn color="primary" text @click="save()"> Save </v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>
  </div>
</template>
<script>
import { mapGetters, mapState } from "pinia";
export default {
  props: {
    sig: Object,
  },
  data: () => ({
    menu: false,
    system_id: null,
  }),

  methods: {
    closed() {
      (this.system_id = null), (this.menu = null);
    },
    async save() {
      var request = {
        modified_by_id: this.$store.state.user_id,
        modified_by_name: this.$store.state.user_name,
        current_system_id: this.system_id,
        sig_id: this.sig.id,
        last_system_id: this.$store.state.lastSystemId,
      };

      await axios({
        method: "POST",
        withCredentials: true,
        url: "api/leadsto",
        data: request,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }).then(this.closed());
    },
  },

  computed: {
    // ...mapState(["systemListRoute"]),
  },
};
</script>
