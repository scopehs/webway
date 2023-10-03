<template>
  <div>
    <q-chip
      v-for="(item, index) in props.items"
      :class="color(item.type)"
      :icon="icon(item.type)"
      :key="index"
      size="md"
      :label="lableText(item)"
    />
  </div>
</template>

<script setup>
const props = defineProps({
  items: Array,
});

let text = (type) => {
  if (type == "em") {
    return type.toUpperCase();
  } else {
    return type.charAt(0).toUpperCase() + type.slice(1);
  }
};

let lableText = (item) => {
  return text(item.type) + " - " + item.amount;
};

let color = (type) => {
  if (type == "thermal") {
    return "fire";
  }

  if (type == "em") {
    return "ice";
  }

  if (type == "explosive") {
    return "boom";
  }
};

let icon = (type) => {
  if (type == "thermal") {
    return "fa-solid fa-fire";
  }

  if (type == "em") {
    return "fa-solid fa-poo-storm";
  }

  if (type == "explosive") {
    return "fa-solid fa-explosion";
  }
};
</script>

<style lang="scss"></style>
<style scoped>
.q-chip.fire {
  background: linear-gradient(-45deg, #ee5252, #ee6e6e, #e71414, #b80505);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

.q-chip.boom {
  background: linear-gradient(-45deg, #ee9d52, #ffb055, #e77a14, #b87f05);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

.q-chip.ice {
  background: linear-gradient(-45deg, #52e4ee, #6eb0ee, #1470e7, #05acb8);
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.transparent-body {
  background: transparent;
}
</style>
