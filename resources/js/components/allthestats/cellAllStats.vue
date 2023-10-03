<template>
  <div>
    <span class="text-primary">
      <transition
        mode="out-in"
        enter-active-class="animate__animated animate__flash animate__faster"
        leave-active-class="animate__animated animate__flash animate__faster"
      >
        <span :key="`${total}-${userID}-${group}-Total`">{{ total }} </span>
      </transition>
    </span>
    <span v-if="type == 1">
      (
      <span class="text-positive">
        <transition
          mode="out-in"
          enter-active-class="animate__animated animate__flash animate__faster"
          leave-active-class="animate__animated animate__flash animate__faster"
        >
          <span :key="`${done}-${userID}-${group}-Done`">{{ done }}</span>
        </transition>
      </span>
      -
      <span class="text-negative">
        <transition
          mode="out-in"
          enter-active-class="animate__animated animate__flash animate__faster"
          leave-active-class="animate__animated animate__flash animate__faster"
        >
          <span :key="`${part}-${userID}-${group}-Part`">{{ part }}</span>
        </transition>
      </span>
      )
    </span>
  </div>
</template>

<script setup>
const props = defineProps({
  total: Number,
  done: Number,
  part: Number,
  type: Number,
  group: Number,
  userID: Number,
});

let showTotal = $computed(() => {
  if (props.type == 3) {
    return Number(props.total).toLocaleString();
  } else {
    return props.total;
  }
});
</script>

<style lang="scss"></style>
