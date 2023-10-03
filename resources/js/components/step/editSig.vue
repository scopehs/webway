<template>
    <div>
        <v-dialog
            max-width="700px"
            z-index="0"
            v-model="showEditSig"
            @click:outside="close()"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    rounded
                    small
                    color="orange"
                    v-bind="attrs"
                    v-on="on"
                    @click="editSig()"
                >
                    Edit
                </v-btn>
            </template>

            <v-card
                tile
                max-width="700px"
                min-height="200px"
                max-height="700px"
                class=" d-flex flex-column"
            >
                <v-card-title> </v-card-title>
                <v-card-text> </v-card-text>
                ><v-card-actions>
                    <v-btn
                        class="white--text"
                        color="green"
                        @click="updateSig()"
                        :disabled="submitActive"
                    >
                        Submit
                    </v-btn>

                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn></v-card-actions
                >
            </v-card>

            <!-- <showEditSig
                :nodeNoteItem="nodeNoteItem"
                v-if="$can('super')"
                @closeMessage="showEditSig = false"
            >
            </showEditSig> -->
        </v-dialog>
    </div>
</template>

<script>
// import { mapState, mapGetters } from "vuex";
import moment from "moment";
export default {
    props: {
        item: Object
    },
    data() {
        return {
            showEditSig: false
        };
    },

    async created() {},

    methods: {
        close() {
            this.showEditSig = false;
        },

        open() {}
    },

    computed: {},

    beforeDestroy() {}
};
</script>

<style></style>
