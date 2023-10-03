<template>
  <div>
    <div class="q-pa-md">
      <div class="q-gutter-y-md" style="max-width: 600px">
        <q-tabs v-model="tab" class="text-teal" @update:model-value="test()">
          <q-tab :name="1" icon="mail" label="Mails" />
          <q-tab :name="2" icon="alarm" label="Alarms" />
          <q-tab :name="3" icon="movie" label="Movies" />
        </q-tabs>

        <q-tabs v-model="tab" inline-label class="bg-purple text-white shadow-2">
          <q-tab :name="1" icon="mail" label="Mails" />
          <q-tab :name="2" icon="alarm" label="Alarms" />
          <q-tab :name="3" icon="movie" label="Movies" />
        </q-tabs>

        <q-tabs v-model="tab" no-caps class="bg-orange text-white shadow-2">
          <q-tab :name="1" label="Mails" />
          <q-tab :name="2" label="Alarms" />
          <q-tab :name="3" label="Movies" />
        </q-tabs>

        <q-tabs v-model="tab" class="bg-teal text-yellow shadow-2">
          <q-tab :name="1" icon="mail" />
          <q-tab :name="2" icon="alarm" />
          <q-tab :name="3" icon="movie" />
        </q-tabs>

        <q-tabs v-model="tab" inline-label class="bg-primary text-white shadow-2">
          <q-tab :name="1" icon="mail" label="Mails" />
          <q-tab :name="2" icon="alarm" label="Alarms" />
          <q-tab :name="3" icon="movie" label="Movies" />
          <q-tab :name="4" icon="photo" label="Photos" />
          <q-tab :name="5" icon="slow_motion_video" label="Videos" />
          <q-tab :name="6" icon="people" label="Address Book" />
        </q-tabs>
      </div>
    </div>
  </div>
</template>

<script setup>
let tab = $ref(1);
let test = () => {
};
</script>

<style lang="scss"></style>
