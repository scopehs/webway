<template>
  <div>
    <q-btn
      text-color="positive"
      :icon="messageIcon"
      round
      flat
      @click="showChatWindow = true"
      ><q-badge v-if="messageCount" color="red" floating>{{
        messageCount
      }}</q-badge></q-btn
    >
    <q-dialog v-model="showChatWindow" @before-hide="close()" @before-show="open()">
      <q-card
        class="my-card myRoundTop"
        style="width: 1000px; max-height: 900px; height: 900px"
      >
        <q-card-section class="bg-primary myCardHeader text-center">
          <div class="text-h6">Support Chat</div>
        </q-card-section>
        <q-card-section id="messages" class="overflow-auto" style="height: 600px">
          <!-- <q-chat-message
            name="me"
            avatar="https://cdn.quasar.dev/img/avatar4.jpg"
            :text="['hey, how are you?']"
            sent
            stamp="7 minutes ago"
          /> -->
          <transition-group enter-active-class="animate__animated animate__zoomIn">
            <q-chat-message
              v-for="(message, index) in messages"
              :key="`${message.id}-message`"
              :name="name(message.user.name, message.user_id)"
              :avatar="url(message)"
              :text="[message.message]"
              :sent="sent(message.user_id)"
              :bg-color="messageColor(message.user_id)"
            >
              <template :key="`${message.id}-stamp`" v-slot:stamp
                >Sent:
                <VueCountUp
                  :key="`${message.id}-time`"
                  :interval="60000"
                  :time="age(message.created_at)"
                  v-slot="{ days, hours, minutes, seconds }"
                >
                  <span v-if="days != '00'">{{ days }}days, </span
                  ><span v-if="hours != '00'">{{ hours }}hours,</span
                  ><span> {{ minutes }}minutes</span>
                  ago
                </VueCountUp></template
              >
            </q-chat-message>
          </transition-group>
        </q-card-section>
        <q-card-section>
          <div>
            <q-input
              input-style="height: 150px"
              v-model="mainText"
              clearable
              outlined
              rounded
              dense
              type="textarea"
              label="Message"
            />
          </div>
        </q-card-section>
        <q-card-actions align="between">
          <q-btn
            rounded
            label="Submit"
            color="primary"
            :disable="showSubmit"
            @click="sendMessage()"
          />
          <q-btn rounded label="Close" color="negative" @click="close()" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, defineAsyncComponent, inject } from "vue";
import { useMainStore } from "@/store/useMain.js";
let can = inject("can");
let store = useMainStore();
const props = defineProps({
  item: { type: Object, required: false },
});
const VueCountUp = defineAsyncComponent(() => import("../countup/index"));
onMounted(async () => {
  if (can("super_admin")) {
  } else {
    await window.Echo.private("room." + store.supportRoom.id).listen(
      "RoomUpdate",
      (e) => {
        if (e.flag.flag == 1) {
          store.updateUserMessage(e.flag.message);
        }

        if (e.flag.flag == 2) {
        }
        if (e.flag.flag == 3) {
        }

        if (e.flag.flag == 4) {
        }
      }
    );
  }
});
let mainText = $ref();
let showChatWindow = $ref(false);

const sendMessage = async () => {
  const sendRoomId = can("super_admin") ? props.item.id : store.supportRoom.id;
  const data = {
    message: mainText,
  };
  try {
    await axios.post(`/api/support/message/${sendRoomId}`, data, {
      withCredentials: true,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
    mainText = null;
  } catch (error) {}
};

let open = async () => {
  if (can("super_admin")) {
    await store.clearWebWayMessageCount(props.item.id);
  } else {
    await store.clearUserMessageCount();
  }

  const sendRoomId = can("super_admin") ? props.item.id : store.supportRoom.id;
  try {
    await axios.post(`/api/support/messageclear/${sendRoomId}`, {
      withCredentials: true,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
    mainText = null;
  } catch (error) {}
};

let close = async () => {
  const sendRoomId = can("super_admin") ? props.item.id : store.supportRoom.id;
  try {
    await axios.post(`/api/support/closeroom/${sendRoomId}`, {
      withCredentials: true,
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    });
    mainText = null;
    showChatWindow = false;
  } catch (error) {}
};

let age = (val) => {
  let date = new Date(val);
  let timestamp = date.getTime();
  return timestamp;
};

let messageCount = $computed(() => {
  if (can("super_admin")) {
    return store.getWebWayMessageCount(props.item.id);
  } else {
    return store.getUserMessageCount;
  }
});

let messages = $computed(() => {
  if (can("super_admin")) {
    return store.getWebWayMessages(props.item.id);
  } else {
    return store.getUserMessages;
  }
});

let messageIcon = $computed(() => {
  if (messageCount) {
    return "fa-solid fa-message";
  } else {
    return "fa-regular fa-message";
  }
});

let sent = (id) => {
  if (store.user_id == id) {
    return true;
  } else {
    return false;
  }
};

let name = (textname, id) => {
  if (store.user_id == id) {
    return "me";
  } else {
    if (can("super_admin")) {
      return textname;
    } else {
      return "WebWay";
    }
  }
};

let messageColor = (id) => {
  if (store.user_id == id) {
    return "amber-7";
  } else {
    return "positive";
  }
};

let showSubmit = $computed(() => {
  if (mainText) {
    return false;
  }
  return true;
});

let url = (item) => {
  let id = 0;
  if (can("super_admin")) {
    id = item.user.main_character_id ?? item.user.character_id;
  } else {
    if (item.user.id == 25107) {
      return "https://goonfleet.com/public/style_extra/team_icons/pf_bee.png";
    } else {
      id = item.user.main_character_id ?? item.user.character_id;
    }
  }

  return "https://image.eveonline.com/Character/" + id + "_128.jpg";
};
</script>

<style lang="scss">
#messages {
  display: flex;
  flex-direction: column-reverse;
  height: 100px;
  overflow-y: scroll;
}
</style>
