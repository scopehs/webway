<template>
    <div>
        <v-card>
            <v-card-title>TITLE</v-card-title>
            <v-card-text>
                <v-icon>fab fa-galactic-senate </v-icon>
                <v-btn>WHAT IS THIS</v-btn>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>


export default {
    props: ["option", "node", "value"],
    data() {
        return {
            id: 2,
            name: null,
            sigs: null
        };
    },
    mounted() {},
    methods: {}
};
</script>
