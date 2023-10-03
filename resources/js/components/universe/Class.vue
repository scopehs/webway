<template>
    <div>{{ text }}</div>
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
        text() {
            var check = this.item.system_type;
            var count = check.length;
            if (count > 0) {
                return this.item.system_type[0]["name"];
            } else {
                return "None";
            }
        }
    }
};
</script>
