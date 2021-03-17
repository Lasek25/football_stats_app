<template>
  <v-btn-toggle v-model="toggleDaysRange" mandatory dense class="mt-3 d-flex">
    <v-btn
      v-for="(days, i) in daysList"
      :key="i"
      :value="days"
      class="flex-grow-1"
    >
      {{ days == 1 ? days + ' dzień' : days + ' dni' }}
    </v-btn>
  </v-btn-toggle>
</template>

<script>
export default {
  props: {
    daysList: {
      type: Array,
      default() {
        return Array.of(3, 7, 14)
      },
    },
  },
  computed: {
    toggleDaysRange: {
      get() {
        return this.$store.getters.getToggleDaysRange
      },
      set(quantity) {
        this.$store.commit('setToggleDaysRange', quantity)
      },
    },
  },
}
</script>
