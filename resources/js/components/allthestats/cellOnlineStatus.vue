<template>
  <div>
    <transition
      mode="out-in"
      enter-active-class="animate__animated animate__zoomIn animate__faster"
      leave-active-class="animate__animated animate__zoomOut animate__faster"
    >
      <div :key="`${onlineStatus}-online`" class="text-center">
        <span :class="textColor"> {{ text }}</span>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { useMainStore } from "@/store/useMain.js";
let store = useMainStore();
const props = defineProps({
  userID: Number,
});

let onlineStatus = $computed(() => {
  var online = store.getUserTrackingStatus(props.userID);
  return online;
});

let textColor = $computed(() => {
  if (onlineStatus) {
    return "text-positive";
  } else {
    return "text-negative";
  }
});

let text = $computed(() => {
  if (onlineStatus) {
    return "Online";
  } else {
    return "Offline";
  }
});
</script>

<style lang="scss"></style>
<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for <2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
.compact-checkbox {
  transform: scale(0.875);
  transform-origin: left;
}
</style>
