<template>
    <v-row no-gutter>
        <v-col cols="12">
            {{ item.jump }} - {{ item.solar_system.name }}
            {{ item.solar_system.system_type[0]["name"] }}
            {{ connectionsType }} {{ sig }}
        </v-col>
    </v-row>
</template>
<script>

export default {
    props: {
        item: Object
    },
    data() {
        return {};
    },
    mounted() {},
    methods: {},

    computed: {
        connectionsType() {
            if (this.item.connection) {
                return "(" + this.item.connection.type.name + ")";
            } else {
                return null;
            }
        },

        sig() {
            if (this.item.connection) {
                if (
                    this.item.connection.type.id == 2 ||
                    this.item.connection.type.id == 4 ||
                    this.item.connection.type.id == 5
                ) {
                    if (
                        this.item.solar_system.system_id ==
                        this.item.connection.source_system_id
                    ) {
                        return (
                            "- " + this.item.connection.source_sig.signature_id
                        );
                    } else {
                        return (
                            "- " + this.item.connection.target_sig.signature_id
                        );
                    }
                }

                return null;
            }
        }
    }
};
</script>
