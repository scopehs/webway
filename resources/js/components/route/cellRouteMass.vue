<template>
  <div v-if="type">
    <span :class="textColor">
      {{ text }}
      <q-tooltip :delay="1000" transition-show="rotate" transition-hide="rotate">
        <div class="text-center">
          MASS <br />
          {{ to }}T up to {{ from }}T
        </div>
      </q-tooltip></span
    >
  </div>
  <div v-else>
    <span :class="textColor">{{ text }}</span>
  </div>
</template>

<script setup>
const props = defineProps({
  item: Object,
});

let type = $computed(() => {
  if (props.item.connection) {
    if (
      props.item.connection.type.id == 2 ||
      props.item.connection.type.id == 4 ||
      props.item.connection.type.id == 5
    ) {
      if (props.item.solar_system.system_id == props.item.connection.source_system_id) {
        if (props.item.connection.source_sig.wormhole_info_mass_id > 0) {
          return props.item.connection.source_sig.wormhole_info_mass_id;
        }
      } else {
        if (props.item.connection.target_sig.wormhole_info_mass_id > 0) {
          return props.item.connection.target_sig.wormhole_info_mass_id;
        }
      }
    }
  }
  return null;
});

let text = $computed(() => {
  if (type == 1) {
    return props.item.connection.source_sig.wormhole_info_mass.table_text;
  }
  if (type == 2) {
    return props.item.connection.source_sig.wormhole_info_mass.table_text;
  }
  if (type == 3) {
    return props.item.connection.source_sig.wormhole_info_mass.table_text;
  }

  return null;
});

let textColor = $computed(() => {
  if (type == 1) {
    return "text-positive";
  }
  if (type == 2) {
    return "text-warning";
  }
  if (type == 3) {
    return "text-negative";
  }
  return null;
});

let from = $computed(() => {
  if (type) {
    if (props.item.connection.source_sig.type == 420) {
      var base = props.item.connection.target_sig.wormhole_type.mass;
      var stage = props.item.connection.target_sig.wormhole_info_mass_id;
    } else {
      var base = props.item.connection.source_sig.wormhole_type.mass;
      var stage = props.item.connection.source_sig.wormhole_info_mass_id;
    }

    var half = (base / 100) * 50;
    var ten = (base / 100) * 10;
    if (stage == 1) {
      return Number(base).toLocaleString();
    }

    if (stage == 2) {
      return Number(half).toLocaleString();
    }

    if (stage == 3) {
      return Number(ten).toLocaleString();
    }
  } else {
    return "no";
  }
});

let to = $computed(() => {
  if (type) {
    if (props.item.connection.source_sig.type == 420) {
      var base = props.item.connection.target_sig.wormhole_type.mass;
      var stage = props.item.connection.target_sig.wormhole_info_mass_id;
    } else {
      var base = props.item.connection.source_sig.wormhole_type.mass;
      var stage = props.item.connection.source_sig.wormhole_info_mass_id;
    }

    var half = (base / 100) * 50;
    var ten = (base / 100) * 10;
    if (stage == 1) {
      return Number(half).toLocaleString();
    }

    if (stage == 2) {
      return Number(ten).toLocaleString();
    }

    if (stage == 3) {
      return Number(0).toLocaleString();
    }
  } else {
    return "no";
  }
});
</script>

<style lang="scss"></style>
