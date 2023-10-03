<template>
    <div>
        <v-dialog persistent v-model="overlay" max-width="500px" z-index="0">
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    class="mr-4"
                    color="green lighten-1"
                    v-bind="attrs"
                    v-on="on"
                    >ADD</v-btn
                >
            </template>

            <v-card tile max-width="500px" min-height="200px">
                <v-card-title class="d-flex justify-space-between align-center">
                    <div>Add Char</div>
                    <v-card
                        width="500"
                        tile
                        flat
                        color="#121212"
                        class="align-start"
                    >
                    </v-card>
                </v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="value"
                        dense
                        filled
                        label="Char ID"
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-btn class="white--text" color="teal" @click="done()">
                        Done
                    </v-btn>
                    <v-btn class="white--text" color="teal" @click="close()">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
export default {
    data() {
        return {
            value: null,
            overlay: null,
        };
    },
    mounted() {},
    methods: {
        close() {
            this.value = null;
            this.overlay = false;
        },

        async done() {
            await axios({
                method: "put",
                url: "/api/addchar/" + this.value,
                withCredentials: true,
                // url: "/api/addchar",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            }).then(close());
        },
    },
};
</script>
